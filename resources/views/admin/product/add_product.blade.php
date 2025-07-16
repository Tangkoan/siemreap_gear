@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            <div
                class="lg:col-span-full rounded-lg shadow-xl p-6 transition-all duration-300 transform
                       bg-white dark:bg-gray-900">
                <h2 class="text-xl font-semibold mb-6 flex items-center text-gray-900 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>

                    <div class="px-2">
                        <a href="{{ route('all.product') }}"
                            class="text-black hover:text-indigo-500 dark:text-white dark:hover:text-indigo-300 font-semibold">
                            {{ __('messages.add_product') }}
                        </a>
                    </div>
                </h2>

                <form id="myForm" method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            {{-- Product Name --}}
                            <div class="form-group">
                                <label for="product_name"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.product_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="product_name" name="product_name"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            </div>

                            {{-- Category --}}
                            <div class="form-group">
                                <label for="Category"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.category') }} 
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" id="example-select"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option selected disabled>{{ __('messages.select_category') }} </option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Supplier --}}
                            <div class="form-group">
                                <label for="Supplier"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                     {{ __('messages.supplier') }}  <span class="text-red-500">*</span>
                                </label>
                                <select name="supplier_id" id="example-select"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option selected disabled>{{ __('messages.select_supplier') }}</option>
                                    @foreach ($supplier as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Details --}}
                            <div>
                                <label for="product_detail"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.details') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="product_detail" name="product_detail"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>



                            {{-- Image --}}
                            <div class="mb-2">
                                <label for="image"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                                    {{ __('messages.image') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="file" id="image" name="product_image"
                                    class="block w-full text-sm text-gray-600 dark:text-gray-300
                                           file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                           file:text-sm file:font-semibold file:bg-gray-300 dark:file:bg-gray-700
                                           file:text-gray-700 dark:file:text-gray-300 hover:file:bg-gray-400 dark:hover:file:bg-gray-600
                                           cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <img id="image_preview" src="#" alt="Image Preview"
                                    class="mt-2 rounded-md max-h-40 hidden" />
                            </div>
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                            {{-- Price --}}
                            <div class="form-group">
                                <label for="selling_price"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.price') }}
                                </label>
                                <input type="number" min="0" step="0.01" id="selling_price" name="selling_price"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            {{-- Buy Price --}}
                            <div class="form-group">
                                <label for="buying_price"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.buy_price') }}
                                </label>
                                <input type="number" min="0" step="0.01" id="buying_price" name="buying_price"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>


                            {{-- Inventory --}}
                            <div class="form-group">
                                <label for="product_store"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.inventory') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" value="0" name="product_store" readonly class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed">
                            </div>

                            {{-- Stock Alert --}}
                            <div class="form-group">
                                <label for="stock_alert" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.stock_alert') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" min="0" step="0.01" id="stock_alert" name="stock_alert"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                                                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md shadow-lg transition-colors duration-200">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
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
                    stock_alert: {
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

                    stock_alert: {
                        required: 'Please Enter Stock Alert Name',
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
