@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6 dark:bg-gray-800 min-h-screen text-gray-900 dark:text-gray-100">
        <div class="grid grid-cols-1">
            <div
                class="lg:col-span-full bg-white dark:bg-gray-900 rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold mb-6 flex items-center text-gray-800 dark:text-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                    </svg>
                    <div class="px-2">
                        <a href="{{ route('pending.purchase') }}" class="hover:underline text-blue-600 dark:text-blue-400">
                            Purchase Details
                        </a>
                    </div>
                </h2>

                <form method="post" action="{{ route('purchase.status.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $purchase->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Supplier Name: <span class="font-semibold">{{ $purchase->supplier->name }}</span>
                            </label>
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Email: <span class="font-semibold">{{ $purchase->supplier->email ?? 'null' }}</span>
                            </label>
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Phone: <span class="font-semibold">{{ $purchase->supplier->phone }}</span>
                            </label>
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Purchase Date: <span class="font-semibold">{{ $purchase->purchase_date }}</span>
                            </label>
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Purchase Invoice: <span class="font-semibold">{{ $purchase->invoice_no }}</span>
                            </label>
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Payment Status: <span class="font-semibold">{{ $purchase->payment_status }}</span>
                            </label>
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Paid Amount: <span class="font-semibold">{{ $purchase->pay }}</span>
                            </label>
                            <label class="block text-gray-500 dark:text-gray-300 text-sm font-medium">
                                Due Amount: <span class="font-semibold">{{ $purchase->due }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto mb-4">
                        <table class="w-full text-sm border-collapse border border-gray-300 dark:border-gray-600 shadow-sm">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1">No</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1">Image</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1">Product & Description
                                    </th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1">Product Code</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1">QTY</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1">Subtotal</th>
                                    <th class="border border-gray-300 dark:border-gray-600 px-2 py-1">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseItem as $key => $item)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center">
                                            {{ $loop->iteration }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center">
                                            @if($item->product)
                                                <img src="{{ asset($item->product->product_image) }}"
                                                    class="w-[50px] h-[40px] object-cover mx-auto"
                                                    alt="{{ $item->product->product_name }}">
                                            @else
                                                <span class="text-red-500">No Image</span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center">
                                            {{ $item->product->product_name ?? 'N/A' }}
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center">
                                            {{ $item->product->product_code ?? 'N/A' }}
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center">
                                            {{ $item->product->selling_price ?? 0 }} $
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-center">
                                            {{ ($item->product->selling_price ?? 0) * $item->quantity }} $
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                            Complete Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#image').on('change', function (event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = $('#image_preview');
                    preview.attr('src', URL.createObjectURL(file)).removeClass('hidden');
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