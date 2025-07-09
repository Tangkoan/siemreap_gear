@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="grid grid-cols-1">
            <div class="rounded-lg shadow-xl p-6 bg-gray-100 dark:bg-gray-800 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold mb-6 flex items-center text-gray-800 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                    <div class="px-2">Edit Product</div>
                </h2>

                <form method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            <input type="hidden" id="product_code" name="product_code" required
                                value="{{ $product->product_code }}"
                                class="w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                            {{-- Product Name --}}
                            <div>
                                <label for="product_name"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="product_name" name="product_name" required
                                    value="{{ $product->product_name }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @error('product_name')
                                    <span class="text-red-500"> {{ $message }} </span>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="Category"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option selected disabled>Select Category</option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Supplier --}}
                            <div>
                                <label for="supplier"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Supplier <span class="text-red-500">*</span>
                                </label>
                                <select name="supplier_id"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option selected disabled>Select Supplier</option>
                                    @foreach ($supplier as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $cat->id == $product->supplier_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Details --}}
                            <div>
                                <label for="product_detail"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Details <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="product_detail" name="product_detail"
                                    value="{{ $product->product_detail }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>



                            {{-- Image --}}
                            <div>
                                <label for="image"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">
                                    Product Image <span class="text-red-500">*</span>
                                </label>
                                <input type="file" id="image" name="product_image"
                                    class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
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
                            <div>
                                <label for="selling_price"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Price
                                </label>
                                <input type="number" min="0" step="0.01" id="selling_price" name="selling_price"
                                    value="{{ $product->selling_price }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            {{-- Buy Price --}}
                            <div>
                                <label for="buying_price"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Buy Price
                                </label>
                                <input type="number" min="0" step="0.01" id="buying_price" name="buying_price"
                                    value="{{ $product->buying_price }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>


                            {{-- Inventory --}}
                            <div>
                                <label for="product_store"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Inventory <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="product_store" name="product_store" 
                                    value="{{ $product->product_store }}" readonly
                                    class="w-full py-2.5 px-4 rounded-md border border-gray-300 dark:border-gray-600
                                           bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 cursor-not-allowed">
                            </div>
                            {{-- Stock Alert --}}
                            <div>
                                <label for="stock_alert" class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                                    Stock Alert <span class="text-red-500">*</span>
                                </label>
                                <input type="number" min="0" step="0.01" id="stock_alert" name="stock_alert" value="{{ $product->stock_alert }}"
                                    class="w-full py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                            Save
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
    </script>
@endsection
