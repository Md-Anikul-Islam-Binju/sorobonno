@extends('admin.app')
@section('admin_content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Wings</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Package Product</a></li>
                        <li class="breadcrumb-item active">Package Product!</li>
                    </ol>
                </div>
                <h4 class="page-title">Package Product!</h4>
            </div>

        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-end">
                    <!-- Large modal -->
                    @can('package-create')
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addNewModalId">Add New</button>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Package Type</th>
                        <th>Package Duration</th>
                        <th>Amount</th>
                        <th>Downlode</th>
                        <th>Product</th>
                        <th>Discount Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($package as $key=>$packageData)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$packageData->name}}</td>
                            <td>{{$packageData->category->name}}</td>
                            <td>{{$packageData->package_type}}</td>
                            <td>{{$packageData->package_duration}}</td>
                            <td>{{$packageData->amount? $packageData->amount :'N/A'}}</td>
                            <td>Downlode</td>
                            <td>
                                @if ($packageData->products->isNotEmpty())
                                    <ul>
                                        @foreach ($packageData->products as $product)
                                            <li>{{ $product->product }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No Products
                                @endif
                            </td>
                            <td>{{$packageData->discount_amount? $packageData->discount_amount :'N/A'}}</td>
                            <td>{{$packageData->status==1? 'Active':'Inactive'}}</td>
                            <td style="width: 100px;">
                                <div class="d-flex justify-content-end gap-1">
                                    @can('package-edit')
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editNewModalId{{$packageData->id}}">Edit</button>
                                    @endcan
                                    @can('package-delete')
                                    <a href="{{route('package.destroy',$packageData->id)}}"class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#danger-header-modal{{$packageData->id}}">Delete</a>
                                    @endcan
                                </div>
                            </td>
                            <!--Edit Modal -->
                            <div class="modal fade" id="editNewModalId{{$packageData->id}}" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editNewModalLabel{{$packageData->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="addNewModalLabel{{$packageData->id}}">Edit</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{route('package.update',$packageData->id)}}">
                                                @csrf
                                                @method('PUT')
                                                <div class="row">

                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="category-select-{{$packageData->id}}" class="form-label">Category</label>
                                                            <select name="category_id" id="category-select-{{$packageData->id}}" class="form-select edit-category-select" data-product-id="{{ $packageData->id }}">
                                                                @foreach($categories as $categoryData)
                                                                    <option value="{{ $categoryData->id }}" {{ $packageData->category_id == $categoryData->id ? 'selected' : '' }}>
                                                                        {{ $categoryData->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Package Name</label>
                                                            <input type="text" id="name" name="name" value="{{ $packageData->name }}"
                                                                   class="form-control" placeholder="Enter Package Name" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="example-select" class="form-label">Package Type</label>
                                                            <select name="package_type" class="form-select">
                                                                <option value="Monthly" {{ $packageData->package_type === "Monthly" ? 'selected' : '' }}>Monthly</option>
                                                                <option value="Half Yearly" {{ $packageData->package_type === "Half Yearly" ? 'selected' : '' }}>Half Yearly</option>
                                                                <option value="Yearly" {{ $packageData->package_type === "Yearly" ? 'selected' : '' }}>Yearly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="example-fileinput" class="form-label">Package Duration</label>
                                                            <input type="text" name="package_duration" id="package_duration" class="form-control" value="{{$packageData->package_duration}}"
                                                                   placeholder="Enter Package Duration" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="example-fileinput" class="form-label">Amount</label>
                                                            <input type="text" name="amount" id="amount" class="form-control" value="{{$packageData->amount}}"
                                                                   placeholder="Enter Amount" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="example-fileinput" class="form-label">Discount Amount</label>
                                                            <input type="text" id="discount_amount" name="discount_amount" value="{{$packageData->discount_amount}}"
                                                                   class="form-control" placeholder="Enter Discount Amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-3">
                                                            <label for="example-select" class="form-label">Status</label>
                                                            <select name="status" class="form-select">
                                                                <option value="1" {{ $packageData->status === 1 ? 'selected' : '' }}>Active</option>
                                                                <option value="0" {{ $packageData->status === 0 ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label>Details  </label>
                                                            <textarea id="summernoteEdit{{ $packageData->id }}" name="details">{{ $packageData->details }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="edit-name-fields-{{$packageData->id}}">
                                                    <!-- Hidden JSON Field -->
                                                    <input type="hidden" name="products_json" id="products-json-{{$packageData->id}}" value="{{ json_encode($packageData->products->pluck('product')->toArray()) }}">
                                                    @foreach ($packageData->products as $key => $product)
                                                        <div class="row name-field">
                                                            <div class="col-10 mb-3">
                                                                <label for="product" class="form-label">Product Name</label>
                                                                <input type="text" class="form-control product-input" value="{{ $product->product }}" placeholder="Enter Product Name">
                                                            </div>
                                                            <div class="col-2 d-flex align-items-end mb-3">
                                                                <button type="button" class="btn btn-danger remove-field">Remove</button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <button type="button" class="btn btn-secondary mb-3" id="add-more-{{$packageData->id}}">Add More</button>


                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-primary" type="submit">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Modal -->
                            <div id="danger-header-modal{{$packageData->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="danger-header-modalLabel{{$packageData->id}}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header modal-colored-header bg-danger">
                                            <h4 class="modal-title" id="danger-header-modalLabe{{$packageData->id}}l">Delete</h4>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h5 class="mt-0">Are You Went to Delete this ? </h5>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <a href="{{route('package.destroy',$packageData->id)}}" class="btn btn-danger">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--Add Modal -->
    <div class="modal fade" id="addNewModalId" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addNewModalLabel">Add</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('package.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="category-select" class="form-label">Category</label>
                                    <select name="category_id" id="category-select" class="form-select" required>
                                        <option selected value="">Select Category</option>
                                        @foreach($categories as $categoryData)
                                            <option value="{{ $categoryData->id }}">{{ $categoryData->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Package Name</label>
                                    <input type="text" id="name" name="name"
                                           class="form-control" placeholder="Enter Package Name" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Package Type</label>
                                    <select name="package_type" class="form-select">
                                        <option value="Monthly">Monthly</option>
                                        <option value="Half Yearly">Half Yearly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Package Duration</label>
                                    <input type="text" name="package_duration" id="package_duration" class="form-control"
                                           placeholder="Enter Package Duration" required>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Amount</label>
                                    <input type="text" name="amount" id="amount" class="form-control"
                                           placeholder="Enter Amount" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Discount Amount</label>
                                    <input type="text" id="discount_amount" name="discount_amount"
                                           class="form-control" placeholder="Enter Discount Amount">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label> Details </label>
                                    <textarea id="summernoteBn" name="details"></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="name-fields">
                            <div class="row name-field">
                                <div class="col-10 mb-3">
                                    <label for="product" class="form-label">Product Name</label>
                                    <input type="text" name="product[]" class="form-control" placeholder="Enter Product Name" required>
                                </div>
                                <div class="col-2 d-flex align-items-end mb-3">
                                    <button type="button" class="btn btn-danger remove-field">Remove</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" id="add-more">Add More</button>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add Fields (Add Modal)
        document.getElementById('add-more').addEventListener('click', function () {
            const nameFieldsContainer = document.getElementById('name-fields');
            const newField = document.createElement('div');
            newField.classList.add('row', 'name-field', 'mb-3');
            newField.innerHTML = `
            <div class="col-10">
                <label for="product" class="form-label">Product Name</label>
                <input type="text" name="product[]" class="form-control" placeholder="Enter Product Name" required>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-field">Remove</button>
            </div>
        `;
            nameFieldsContainer.appendChild(newField);
        });

        document.getElementById('name-fields').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-field')) {
                e.target.closest('.name-field').remove();
            }
        });


        // Add More Fields in Edit Modal
        document.querySelectorAll('[id^=add-more-]').forEach(button => {
            button.addEventListener('click', function () {
                const packageId = this.id.split('-')[2]; // Extract package ID
                const nameFieldsContainer = document.getElementById(`edit-name-fields-${packageId}`);

                // Add a new product input field
                const newField = document.createElement('div');
                newField.classList.add('row', 'name-field', 'mb-3');
                newField.innerHTML = `
            <div class="col-10">
                <label for="product" class="form-label">Product Name</label>
                <input type="text" class="form-control product-input" placeholder="Enter Product Name">
            </div>
            <div class="col-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-field">Remove</button>
            </div>
        `;
                nameFieldsContainer.appendChild(newField);
            });
        });

        // Remove Fields
        document.querySelectorAll('[id^=edit-name-fields-]').forEach(container => {
            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-field')) {
                    e.target.closest('.name-field').remove();
                }
            });
        });

        // Serialize Product Inputs into Hidden JSON Field Before Form Submission
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                const packageId = form.getAttribute('action').split('/').pop(); // Get package ID from action URL
                const hiddenField = document.getElementById(`products-json-${packageId}`);
                const nameFieldsContainer = document.getElementById(`edit-name-fields-${packageId}`);
                const inputs = nameFieldsContainer.querySelectorAll('.product-input');

                const products = Array.from(inputs).map(input => input.value.trim()).filter(value => value !== '');
                hiddenField.value = JSON.stringify(products); // Serialize into JSON
            });
        });


    </script>

@endsection
