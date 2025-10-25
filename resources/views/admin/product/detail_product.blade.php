@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6  text-gray-900 dark:text-gray-100">
        <div class="grid grid-cols-1">

            <div
                class="lg:col-span-full rounded-lg shadow-xl p-6 bg-white/80 dark:bg-gray-900/80 transition-all duration-300 transform">
                <h2 class="text-xl  mb-6 flex items-center text-gray-800 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    <div class="px-2">
                        <a href="{{ route('all.product') }}"
                            class="hover:underline ">
                             {{ __('messages.product_details') }} 
                            </a>
                    </div>
                </h2>

                <form>
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            {{-- Hidden Product Code --}}
                            <input type="hidden" name="product_code" value="{{ $product->product_code }}">

                            {{-- Product Name --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.product_name') }}
                                </label>
                                <input type="text" name="product_name" required value="{{ $product->product_name }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Category --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.category') }} 
                                </label>
                                <select name="category_id"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option selected disabled>Select Category</option>
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Supplier --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __(key: 'messages.supplier') }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="supplier_id"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option selected disabled>{{ __('messages.select_supplier') }} </option>
                                    @foreach($supplier as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $product->supplier_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
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
                                           bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option selected disabled>{{ __('messages.select_condition') }}</option>
                                    @foreach ($condition as $con)
                                        <option value="{{ $cat->id }}"
                                            {{ $con->id == $product->condition_id ? 'selected' : '' }}>
                                            {{ $con->condition_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- Image --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                                    {{ __('messages.image') }}  <span class="text-red-500">*</span>
                                </label>
                                
                                <img id="image_preview" src="{{ asset($product->product_image) }}" alt="Image Preview"
                                    class="mt-2 rounded-md max-h-40 border border-gray-300 dark:border-gray-600" />
                            </div>
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                            <div></div>

                            {{-- Selling Price --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.price') }} 
                                </label>
                                <input type="number" step="0.01" name="selling_price" value="{{ $product->selling_price }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Buying Price --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.cost') }} 
                                </label>
                                <input type="number" step="0.01" name="buying_price" value="{{ $product->buying_price }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            
                            {{-- Inventory --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.inventory') }}  <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="product_store" value="{{ $product->product_store }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Details --}}
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    {{ __('messages.details') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="product_detail" value="{{ $product->product_detail }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                        </div>
                    </div>

                    {{-- Uncomment this if you want a Save button --}}
                    {{--
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                            Save
                        </button>
                    </div>
                    --}}
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#image').on('change', function (event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = $('#image_preview');
                    preview.attr('src', URL.createObjectURL(file));
                    preview.removeClass('hidden');
                    preview.on('load', function () {
                        URL.revokeObjectURL(preview.attr('src'));
                    });
                } else {
                    $('#image_preview').addClass('hidden').attr('src', '#');
                }
            });
        });
    </script>

@endsection