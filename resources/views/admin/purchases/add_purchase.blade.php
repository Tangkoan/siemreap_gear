@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>





    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>


                    <div class="px-2">
                        <a href="{{ route('all.product') }}">
                            Add Purchase
                        </a>
                    </div>
                </h2>

                <div>

                    <form id="myForm" method="post" action="{{ url('/purchase-create') }}" >
                        @csrf


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">


                                {{-- Product --}}
                                <div class="form-group">
                                    <label for="Supplier" class="block text-gray-400 text-sm font-medium mb-1">
                                        Product <span class="text-red-500">*</span>
                                    </label>
                                    <select name="product_id"
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        id="example-select">
                                        <option selected disabled>Select Product </option>
                                        @foreach($product as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Supplier --}}
                                <div class="form-group">
                                    <label for="Supplier" class="block text-gray-400 text-sm font-medium mb-1">
                                        Supplier <span class="text-red-500">*</span>
                                    </label>
                                    <select name="supplier_id"
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                        id="example-select">
                                        <option selected disabled>Select Supplier </option>
                                        @foreach($supplier as $sup)
                                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Discount --}}
                                <div class="form-group">
                                    <label for="discount" class="block text-gray-400 text-sm font-medium mb-1  ">
                                        Discount <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="discount" name="discount"
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ">
                                </div>


                                {{-- Discount --}}
                                <div class="form-group">
                                    <label for="discount" class="block text-gray-400 text-sm font-medium mb-1  ">
                                        Discount <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="discount" name="discount"
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ">
                                </div>

                            </div>

                            {{-- Column 2 --}}
                            <div class="space-y-4">


                                {{-- Price --}}
                                <div class="form-group">
                                    <label for="pay" class="block text-gray-400 text-sm font-medium mb-1">
                                        Price
                                    </label>
                                    <input type="number" min="0" step="0.01" id="price" name="price" disabled
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>

                                {{-- Pay --}}
                                <div class="form-group">
                                    <label for="pay" class="block text-gray-400 text-sm font-medium mb-1">
                                        Pay
                                    </label>
                                    <input type="number" min="0" step="0.01" id="pay" name="pay"
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

                                <!-- Hidden Fields -->

                                <input type="hidden" name="purchase_date" value="{{ date('d-F-Y') }}">
                                <input type="hidden" name="purchase_status" value="pending">

                                <!-- អាចបន្សល់ sub_total / vat / total បើគណនាចេញនៅ JS -->
                                <input type="hidden" name="sub_total" id="subTotal" value="">
                                <input type="hidden" name="vat" value="">
                                <input type="hidden" name="total" value="">


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

                    supplier_id: {
                        required: 'Please Select Supplier Name',
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


        /// Get Price product
        $(document).ready(function () {
                $('#example-select').on('change', function () {
                    var productId = $(this).val();

                    if (productId) {
                        $.ajax({
                            url: '/get-product-price/' + productId,
                            type: 'GET',
                            dataType: 'json',
                            success: function (response) {
                                $('#price').val(response.price);
                            },
                            error: function () {
                                $('#price').val('');
                            }
                        });
                    } else {
                        $('#price').val('');
                    }
                });
            });

    </script>

@endsection