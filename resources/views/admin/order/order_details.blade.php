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
                                d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>


                        <div class="px-2">
                            <a href="{{ route('pending.order') }}">
                                 {{ __('messages.order_details') }}
                            </a>
                        </div>


                    </h2>

                    <div>

                        <form method="post" action="{{ route('order.status.update') }}">
                            @csrf

                            <input type="hidden" name="id" value="{{ $order->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                                {{-- Column 1 --}}
                                <div class="space-y-4">
                                    <label class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.customer_name') }} : <span>{{ $order->customer->name }}</span>
                                    </label>
                                    <label class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.email') }} : <span>{{ $order->customer->email ?? 'null' }}</span>
                                    </label>
                                    <label class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.phone') }} : <span>{{ $order->customer->phone }}</span>
                                    </label>
                                    <label class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.order_date') }} : <span>{{ $order->order_date }}</span>
                                    </label>
                                </div>

                                {{-- Column 2 --}}
                                <div class="space-y-4">
                                    <label class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.order_invoice') }} : <span>{{ $order->invoice_no }}</span>
                                    </label>
                                    <label for="product_name" class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.payment_method') }} : <span>{{ $order->payment_status }}</span>
                                    </label>
                                    <label for="product_name" class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.paid_amount') }} : <span>{{ $order->pay }}</span>
                                    </label>
                                    <label for="product_name" class="block text-gray-400 text-sm font-medium mb-1">
                                        {{ __('messages.due_amount') }} : <span>{{ $order->due }}</span>
                                    </label>
                                </div>
                            </div>


                            <div class="overflow-x-auto mb-4">
                                <table class="w-full border-collapse border border-gray-300 text-xs shadow-sm">
                                    <thead class="bg-gray-200 dark:bg-gray-800">
                                        <tr>
                                            <th class="border border-gray-300 px-2 py-1">{{ __('messages.no') }}</th>
                                            <th class="border border-gray-300 px-2 py-1">{{ __('messages.image') }}</th>
                                            <th class="border border-gray-300 px-2 py-1">{{ __('messages.product_name') }}</th>
                                            <th class="border border-gray-300 px-2 py-1">{{ __('messages.product_code') }}</th>
                                            <th class="border border-gray-300 px-2 py-1">{{ __('messages.qty') }}</th>
                                            <th class="border border-gray-300 px-2 py-1">{{ __('messages.subtotal') }}</th>
                                            <th class="border border-gray-300 px-2 py-1">{{ __('messages.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderItem as $key => $item)
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $loop->iteration }}</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center "> <img
                                                        src="{{ asset($item->product->product_image) ?? "NO IMAGE" }}"
                                                        style="width:50px; height: 40px; ">
                                                </td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->product->product_name ?? 'null' }}</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->product->product_code }}</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->quantity }}</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->product->selling_price }} $</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">{{ $item->product->selling_price * $item->quantity }} $</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-end mt-6">
                                {{-- ✅ CHANGE HERE: Added id and data-due attribute --}}
                                <button type="submit" id="complete-order-btn" data-due="{{ $order->due }}"
                                    class="button-add button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                    {{ __('messages.complete_order') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ CHANGE HERE: Added new script for confirmation --}}
        {{-- วางไว้ก่อน @endsection --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- SweetAlert2 JS --}}
        <script type="text/javascript">
    $(document).ready(function() {
        

        // 👨‍💻 SCRIPT FOR ORDER COMPLETION WITH CUSTOM MODAL
        $('#complete-order-btn').on('click', function(e) {
            e.preventDefault(); 

            let due = $(this).data('due');
            let form = $(this).closest('form');

            if (due > 0) {
                // ប្រសិនបើនៅជំពាក់លុយ, បង្ហាញ SweetAlert2 Modal
                Swal.fire({
                    title: 'Are you sure to complete this order?',
                    html: `
                        <p>this order have due <strong>${due} $</strong>. please enter you due</p>
                        <div class="mt-4 text-left">
                            <label for="swal-payment-amount" class="block text-sm font-medium text-gray-700">Pay Now ($)</label>
                            <input id="swal-payment-amount" type="number" value="${due}" class="swal2-input w-full" placeholder="pay now...">

                            <label for="swal-payment-method" class="block text-sm font-medium text-gray-700 mt-2">Payment Method</label>
                            <select id="swal-payment-method" class="swal2-input w-full">
                                <option value="Cash">Handcash</option>
                                <option value="or_code">qr code</option>
                            </select>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'យល់ព្រម & បញ្ចប់',
                    cancelButtonText: 'ยกเลิก',
                    
                   
                    preConfirm: () => {
                        const amount = document.getElementById('swal-payment-amount').value;
                        const method = document.getElementById('swal-payment-method').value;
                        if (!amount || amount <= 0) {
                            Swal.showValidationMessage(`Pay Now`);
                            return false;
                        }
                        return { amount: amount, method: method };
                    }

                }).then((result) => {
                    
                    if (result.isConfirmed) {
                        
                        form.append(`<input type="hidden" name="final_payment_amount" value="${result.value.amount}">`);
                        form.append(`<input type="hidden" name="final_payment_method" value="${result.value.method}">`);
                        
                        
                        form.submit();
                    }
                });

            } else {
                
                form.submit();
            }
        });
    });
</script>
@endsection