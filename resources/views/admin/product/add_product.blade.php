@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>





    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>


                        <div class="px-2">
                            <a href="{{ route('all.product') }}">
                                Add Product
                            </a>
                        </div>
                </h2>

                <div>

                        <form id="myForm" method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
                            @csrf


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                                {{-- Column 1 --}}
                                <div class="space-y-4">
                                    {{-- Product Name --}}
                                    <div class="form-group">
                                        <label for="product_name" class="block text-gray-400 text-sm font-medium mb-1  ">
                                            Product Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="product_name" name="product_name" 
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ">
                                    </div>

                                    {{-- Category --}}
                                    <div class="form-group"> 
                                        <label for="Category" class="block text-gray-400 text-sm font-medium mb-1">
                                            Category <span class="text-red-500">*</span>
                                        </label>
                                        <select name="category_id" class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" id="example-select">
                                            <option selected disabled>Select Category </option>
                                            @foreach($category as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach               </select>
                                    </div>


                                    {{-- Brand --}}
                                    <div class="form-group">
                                        <label for="Brand" class="block text-gray-400 text-sm font-medium mb-1">
                                            Brand <span class="text-red-500">*</span>
                                        </label>
                                        <select name="brand_id"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            id="example-select">
                                            <option selected disabled>Select Brand </option>
                                            @foreach($brand as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Brand --}}
                                    <div class="form-group">
                                        <label for="Brand" class="block text-gray-400 text-sm font-medium mb-1">
                                            Supplier <span class="text-red-500">*</span>
                                        </label>
                                        <select name="supplier_id"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            id="example-select">
                                            <option selected disabled>Select Supplier </option>
                                            @foreach($supplier as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Unit --}}
                                    <div class="form-group">
                                        <label for="Brand" class="block text-gray-400 text-sm font-medium mb-1">
                                            Unit <span class="text-red-500">*</span>
                                        </label>
                                        <select name="unit_id" 
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                            id="example-select">
                                            <option selected disabled>Select Unit </option>
                                            @foreach($unit as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->unit_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    {{-- Image --}}
                                    <div class="mb-2">
                                        <label for="image" class="block text-gray-400 text-sm font-medium mb-2">
                                            Product Image <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" id="image" name="product_image" required
                                            class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-500 file:text-white hover:file:bg-gray-600 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        {{-- Image preview script can be added here if needed --}}
                                        <img id="image_preview" src="#" alt="Image Preview"
                                            class="mt-2 rounded-md max-h-40 hidden" />
                                    </div>

                                </div>

                                {{-- Column 2 --}}
                                <div class="space-y-4">
                                    {{-- Price --}}
                                    <div class="form-group">
                                        <label for="selling_price" class="block text-gray-400 text-sm font-medium mb-1">
                                            Price
                                        </label>
                                        <input type="number" min="0" step="0.01" id="selling_price" name="selling_price"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                                    </div>

                                    {{-- Buy Price --}}
                                    <div class="form-group">
                                        <label for="buying_price" class="block text-gray-400 text-sm font-medium mb-1">
                                            Buy Price
                                        </label>
                                        <input type="number" min="0" step="0.01" id="buying_price" name="buying_price"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                                    </div>

                                    {{-- Cost --}}
                                    <div>
                                        <label for="buying_price" class="block text-gray-400 text-sm font-medium mb-1">
                                            Cost
                                        </label>
                                        <input type="number" min="0" step="0.01" id="cost" name="cost"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                                    </div>

                                    {{-- Buying Date --}}
                                    <div>
                                        <label for="buying_date" class="block text-gray-400 text-sm font-medium mb-1">
                                            Buying Date
                                        </label>
                                        <input type="date" id="buying_date" name="buying_date"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>

                                    {{-- Product Invetory --}}

                                    <div class="form-group">
                                        <label for="product_store" class="block text-gray-400 text-sm font-medium mb-1">
                                            Inventory <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" min="0" step="0.01" id="product_store" name="product_store"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="product_detail" class="block text-gray-400 text-sm font-medium mb-1">
                                            Details <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" id="product_detail" name="product_detail"
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>

                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="submit"
                                    class="button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                    Save
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            // Image preview script (optional)
            $('#image').on('change', function (event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = $('#image_preview');
                    preview.attr('src', URL.createObjectURL(file));
                    preview.removeClass('hidden');
                    preview.on('load', function () {
                        URL.revokeObjectURL(preview.attr('src')); // free memory
                    })
                } else {
                    $('#image_preview').addClass('hidden').attr('src', '#');
                }
            }); 
        });


        $(document).ready(function () {
                $('#myForm').validate({
                    rules: {
                        product_name: {
                            required: true,
                        },
                        category_id: {
                            required: true,
                        },
                        brand_id: {
                            required: true,
                        },
                        unit_id: {
                            required: true,
                        },
                        supplier_id: {
                            required: true,
                        },
                        selling_price: {
                            required: true,
                        },
                        product_store: {
                            required: true,
                        },
                    },
                    messages: {
                        product_name: {
                            required: 'Please Enter Product Name',
                        },
                        category_id: {
                            required: 'Please Select Category Name',
                        },
                        brand_id: {
                            required: 'Please Select Brand Name',
                        },
                        supplier_id: {
                            required: 'Please Select Supplier Name',
                        },
                        unit_id: {
                            required: 'Please Select Unit Name',
                        },
                        selling_price: {
                            required: 'Please Enter Price Name',
                        },
                        product_store: {
                            required: 'Please Enter Inventory Name',
                        },

                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    },
                });
            });

    </script>

@endsection