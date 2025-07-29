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

                            {{-- Condition Name --}}
                            <div class="form-group">
                                <label for="condition_id"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                     {{ __('messages.condition_name') }}  <span class="text-red-500">*</span>
                                </label>
                                <select name="condition_id" id="example-select"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option selected disabled>{{ __('messages.select_condition') }}</option>
                                    @foreach ($condition as $con)
                                        <option value="{{ $con->id }}">{{ $con->condition_name }}</option>
                                    @endforeach
                                </select>
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
                                    {{ __('messages.price') }}<span class="text-red-500">*</span>
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
                                    {{ __('messages.cost') }}<span class="text-red-500">*</span>
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
                                    {{ __('messages.inventory') }} 
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




                            {{-- ✅ បន្ថែម Status Toggle Button --}}
                            <div class="form-group">
                                <label for="status" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                                    {{ __('messages.status') }}
                                </label>
                                <label for="status" class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="status" value="0"> {{-- Default value if checkbox is not checked --}}
                                    <input type="checkbox" name="status" value="1" id="status" class="sr-only peer"
                                        {{-- សម្រាប់ Edit Page --}}
                                        @if(isset($product) && $product->status == '1') checked @endif
                                        {{-- សម្រាប់ Add Page --}}
                                        @if(!isset($product)) checked @endif
                                    >
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Active</span>
                                </label>
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

                    condition_id: {
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
                    buying_price: {
                        required: true,
                    },
                },
                messages: {
                    product_name: {
                        required: '{{ __('messages.please_enter_product_name') }}',
                    },
                    category_id: {
                        required: '{{ __('messages.please_select_category') }}',
                    },

                    supplier_id: {
                        required: '{{ __('messages.please_select_supplier') }}',
                    },

                    condition_id: {
                        required: '{{ __('messages.please_select_condition') }}',
                    },

                    selling_price: {
                        required: '{{ __('messages.please_enter_price_selling_price') }}',
                    },
                    product_store: {
                        required: '{{ __('messages.please_enter_inventory') }}',
                    },

                    stock_alert: {
                        required: '{{ __('messages.please_enter_stock_alert') }}',
                    },
                    buying_price: {
                        required: '{{ __('messages.please_enter_buying_price') }}',
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
