<script>
import Layout from "../frontend/Layout.vue";

export default {
    name: "SearchProduct",
    layout: Layout,
    props: {
        siteSettings: Object,
        authUser: Object,
        products: Array,
        cart: Array,
        currency: Array
    },
    data() {
        return {
            currentCurrency: localStorage.getItem('currency') || 'TAKA',
            // exchangeRates: {
            //     TAKA: { rate: 1, symbol: '৳' },
            //     USD: { rate: 0.0082, symbol: '$' },
            //     EUR: { rate: 0.0072, symbol: '€' },
            //     INR: { rate: 0.69, symbol: '₹' }
            // },
            exchangeRates: {},
        };
    },

    created() {
        this.currency.forEach(item => {
            this.exchangeRates[item.name] = {
                rate: parseFloat(item.value),
                symbol: this.getCurrencySymbol(item.name)
            };
        });
        // Listen for currency changes
        window.addEventListener('currency-changed', (e) => {
            this.currentCurrency = e.detail;
        });
    },

    methods:{

        getCurrencySymbol(name) {
            switch (name) {
                case 'BDT': return '৳';
                case 'USD': return '$';
                case 'EUR': return '€';
                case 'INR': return '₹';
                default: return '';
            }
        },
        formatPrice(price) {
            const currency = this.exchangeRates[this.currentCurrency] || { rate: 1, symbol: '৳' };
            return `${currency.symbol}${(price * currency.rate).toFixed(2)}`;
        },
        getProductImageUrl(productImagePath) {
            if (!productImagePath) {
                return 'frontend/images/file.jpg'; // Fallback image
            }
            return `${window.location.origin}/images/product/${productImagePath}`;
        },
    }
}
</script>

<template>
    <div class="section-products py-5">
        <div class="container">
            <div class="section-title text-center mb-1">
                <h2 class="text-center h6 d-inline-block bg-prmry fw-medium mb-2 px-2 py-1">
                    Search Results
                </h2>
            </div>
            <div class="row mt-5">
                <div v-for="(product, index) in products" :key="index" class="col-md-6 col-lg-4 col-xl-3">
                    <div :id="'product-' + product.id" class="single-product">
                        <div class="part-1">
                             <span v-if="product.discount_amount" class="discount">
                                   {{ ( product.discount_amount / product.amount)*100 }}% off
                             </span>
                            <Link class="d-inline-block" :href="`/product-details/${product.id}`">
                                <img :src="getProductImageUrl(product.file)" alt="Product Image">
                            </Link>
                        </div>

<!--                        <div class="part-2" v-if="product.discount_amount">-->
<!--                            <h3 class="product-title">{{ product.name }}</h3>-->
<!--                            <h4 class="product-old-price text-decoration-line-through">-->
<!--                                ${{ product.amount }}-->
<!--                            </h4>-->
<!--                            <h4 class="product-price">${{ product.discount_amount }}</h4>-->
<!--                        </div>-->

<!--                        <div class="part-2" v-else>-->
<!--                            <h3 class="product-title">{{ product.name }}</h3>-->
<!--                            <h4 class="product-price">${{ product.amount }}</h4>-->
<!--                        </div>-->
                        <div class="part-2" v-if="product.discount_amount">
                            <h3 class="product-title">{{ product.name }}</h3>
                            <h4 class="product-old-price text-decoration-line-through">
                                {{ formatPrice(product.amount) }}
                            </h4>
                            <h4 class="product-price">{{ formatPrice(product.discount_amount) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
