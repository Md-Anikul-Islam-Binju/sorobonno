<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function showCheckoutPage()
    {
        // Retrieve the cart from the session
        $cart = session('cart', []);
        $coupon = session('coupon', null);
        // Initialize subtotal
        $subtotal = 0;

        foreach ($cart as $item) {
            if (isset($item['product_id'])) {
                // For products, calculate price, duration, and device access
                $subtotal += $item['price'] * $item['duration'] * $item['device_access'];
            } elseif (isset($item['package_id'])) {
                // For packages, calculate package price and device access
                $subtotal += $item['package_price'] * $item['device_access'];
            }
        }

        // Delivery fee
        $deliveryFee = 10.00;

        // Total amount
        $total = $subtotal + $deliveryFee;


        //$authUser = auth()->user();
        $siteSettings = SiteSetting::where('id', 1)->first();

        return Inertia::render('Checkout', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'total' => $total,
            'delivery_fee' => $deliveryFee,
            //'authUser' => $authUser,
            'siteSettings' => $siteSettings,
            'coupon' => $coupon,

        ]);
    }


    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);
        $user = auth()->user();
        $couponData = session('coupon', null);

        // Check if the cart is empty
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        // Calculate the total amount
        $total = 0;
        foreach ($cart as $item) {
            // If the item is a product
            if (isset($item['product_id'])) {
                // Ensure 'name' exists for products
                $product = Product::find($item['product_id']);
                if ($product) {
                    $item['name'] = $product->name; // Add the product name
                    $total += $item['price'] * $item['duration'] * $item['device_access'];
                }
            }
            // If the item is a package
            elseif (isset($item['package_id'])) {
                // Ensure 'name' exists for packages
                $package = Package::find($item['package_id']);
                if ($package) {
                    $item['name'] = $package->name; // Add the package name
                    $total += $item['package_price'] * $item['device_access'];
                } else {
                    // Handle case where the package doesn't exist in the database
                    return redirect()->route('cart')->with('error', 'Invalid package.');
                }
            }
        }

        unset($item); // avoid reference issues

        $coupon_code = null;
        $coupon_user_id = null;

        if ($couponData && isset($couponData['code'])) {
            $coupon_code = $couponData['code'];

            // Find coupon from DB by coupon_code
            $coupon = Coupon::where('coupon_code', $coupon_code)->first();

            if ($coupon) {
                $coupon_user_id = $coupon->agent_admin_id;
            }
        }

        // Create the order
        $order = Order::create([
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'payment_method' => $request->payment_method,
            'total' => $total,
            'coupon_code' => $coupon_code,
            'coupon_user_id' => $coupon_user_id,
            'status' => 'pending',  // Set order status to pending
        ]);

        // Insert order items
        foreach ($cart as $item) {
            // Handle product items
            if (isset($item['product_id'])) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],  // Set product name
                    'duration' => $item['duration'],  // Duration for product
                    'device_access' => $item['device_access'],
                    'price' => $item['price'],  // Price of the product
                    'type' => 'product',
                    'is_free_or_paid' => 'paid', // Type is product
                ]);
            }

            // Handle package items
            elseif (isset($item['package_id'])) {
                // Ensure package name is set before creating the order item
                if (!isset($item['name'])) {
                    $package = Package::find($item['package_id']);
                    if ($package) {
                        $item['name'] = $package->name;
                    }
                }

               // dd($item);

                OrderItem::create([
                    'order_id' => $order->id,
                    'package_id' => $item['package_id'],
                    'name' => $item['name'],  // Set package name
                    'duration' => $item['package_duration'] ?? null,  // Duration for package
                    'device_access' => $item['device_access'],
                    'price' => $item['package_price'],  // Price of the package
                    'type' => 'package',  // Type is package
                    'is_free_or_paid' => $item['package_type'],
                ]);
            }
        }

        // Clear the cart session
        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('payment');  // Redirect to payment page (mock)
    }



    public function payment()
    {
        // Logic for the payment page or processing
        $siteSettings = SiteSetting::latest()->first();
        $cart = session('cart', []);
        $authUser = auth()->user();
        return inertia('Payment',compact('siteSettings','cart','authUser')); // Assuming you have a payment.blade.php view
    }






}
