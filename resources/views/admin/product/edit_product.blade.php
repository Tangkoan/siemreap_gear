@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6 text-defallut">
        <div class="grid grid-cols-1">
            <div class="rounded-lg shadow-xl p-6 card-dynamic-bg transition-all duration-300 transform">
                <h2 class="text-xl  mb-6 flex items-center ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    <div class="px-2">{{ __('messages.edit_product') }}</div>
                </h2>

                <form id="myForm" method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            <input type="hidden" id="product_code" name="product_code" required
                                value="{{ $product->product_code }}"
                                class="w-full py-2.5 px-4 border border-gray-700 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                            {{-- Product Name --}}
                            <div class="form-group">
                                <label for="product_name"
                                    class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.product_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="product_name" name="product_name" 
                                    value="{{ $product->product_name }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('product_name')
                                    <span class="text-red-500"> {{ $message }} </span>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div class="form-group">
                                <label for="Category"
                                    class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.category') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option selected disabled>{{ __('messages.select_category') }}</option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Supplier --}}
                            <div class="form-group">
                                <label for="supplier"
                                    class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.supplier') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="supplier_id"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option selected disabled>{{ __('messages.select_supplier') }}</option>
                                    @foreach ($supplier as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $cat->id == $product->supplier_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Condition Name --}}
                            <div class="form-group">
                                <label for="condition_id"
                                    class="block text-defalut text-sm font-medium mb-1">
                                     {{ __('messages.condition_name') }}  <span class="text-red-500">*</span>
                                </label>
                                <select name="condition_id" id="example-select"
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option selected disabled>{{ __('messages.select_condition') }}</option>
                                    @foreach ($condition as $con)
                                        <option value="{{ $con->id }}"
                                            {{ $con->id == $product->condition_id ? 'selected' : '' }}>
                                            {{ $con->condition_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            


                            {{-- Image --}}
                            <div class="form-group">
                                <label for="image"
                                    class="block text-defalut text-sm font-medium mb-2">
                                    {{ __('messages.image') }} 
                                </label>
                                
                                {{-- លាក់ Input ពិត --}}
                                <input type="file" id="image" name="product_image" class="hidden" />
                                {{-- ប្រើ Label ធ្វើជាប៊ូតុង --}}
                                <label for="image" 
                                    class="inline-block px-4 py-2 bg-primary text-white rounded-md cursor-pointer hover:bg-primary-focus">
                                    <i class="fa-solid fa-upload mr-2"></i> {{-- ឧទាហរណ៍ ដាក់ Icon --}}
                                    Choose File
                                </label>
                                {{-- (ជាជម្រើស) បង្ហាញឈ្មោះ File ពេលជ្រើសរើសរួច --}}
                                <span id="file-name" class="ml-3 text-sm text-defalut">No file chosen</span>

                                <img id="image_preview" src="{{ asset($product->product_image) }}" alt="Image Preview"
                                    class="mt-2 rounded-md max-h-40 border border-gray-300 dark:border-gray-600" />
                                @error('image')
                                    <span class="text-red-500"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                            <input type="hidden" name="product_code" value="{{ $product->product_code }}">

                            {{-- Price --}}
                            <div class="form-group">
                                <label for="selling_price"
                                    class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.price') }}<span class="text-red-500">*</span>
                                </label>
                                <input type="number" min="0" step="0.01" id="selling_price" name="selling_price"
                                    value="{{ $product->selling_price }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Buy Price --}}
                            <div class="form-group">
                                <label for="buying_price"
                                    class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.cost') }}<span class="text-red-500">*</span>
                                </label>
                                <input type="number" min="0" step="0.01" id="buying_price" name="buying_price"
                                    value="{{ $product->buying_price }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>


                            {{-- Inventory --}}
                            <div class="form-group">
                                <label for="product_store"
                                    class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.inventory') }} 
                                </label>
                                <input type="text" id="product_store" name="product_store" 
                                    value="{{ $product->product_store }}" readonly
                                    class=" w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-white/80 dark:bg-gray-900/80 text-gray-500 dark:text-gray-300 cursor-not-allowed">
                            </div>
                            {{-- Stock Alert --}}
                            <div class="form-group">
                                <label for="stock_alert" class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.stock_alert') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" min="0" step="0.01" id="stock_alert" name="stock_alert" value="{{ $product->stock_alert }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Details --}}
                            <div class="form-group">
                                <label for="product_detail"
                                    class="block text-defalut text-sm font-medium mb-1">
                                    {{ __('messages.details') }}
                                </label>
                                <input type="text" id="product_detail" name="product_detail"
                                    value="{{ $product->product_detail }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>


                            {{-- ✅ START: កូដថ្មីសម្រាប់ Status Toggle (សម្រាប់​ទំព័រ Edit តែ​ប៉ុណ្ណោះ) --}}
                            <div class="form-group">
                                <label for="status" class="block text-defalut text-sm font-medium mb-2">
                                    {{ __('messages.status') }}
                                </label>
                                <label for="status" class="relative inline-flex items-center cursor-pointer">
                                    {{-- តម្លៃ​លំនាំដើម​នៅ​ពេល​មិន​បាន check គឺ 0 --}}
                                    <input type="hidden" name="status" value="0">
                                    
                                    {{-- Checkbox នឹង​បញ្ជូន​តម្លៃ 1 នៅ​ពេល​ត្រូវ​បាន check --}}
                                    <input type="checkbox" name="status" value="1" id="status" class="sr-only peer"
                                        {{-- ពិនិត្យ​តែ status របស់ product បច្ចុប្បន្ន​ប៉ុណ្ណោះ --}}
                                        {{ $product->status == '1' ? 'checked' : '' }}
                                    >
                                    
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                                    <span class="ml-3 text-sm font-medium text-defalut">Active</span>
                                </label>
                            </div>
                            {{-- ✅ END --}}

                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-primary text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                             {{ __('messages.save') }} 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#image').on('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = $('#image_preview');
                    preview.attr('src', URL.createObjectURL(file));
                    preview.removeClass('hidden');
                    preview.on('load', function() {
                        URL.revokeObjectURL(preview.attr('src'));
                    });
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

                    condition_id: {
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
