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
                            d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                    </svg>


                    <div class="px-2">
                        <a href="{{ route('pending.order') }}">
                            Order Details
                        </a>
                    </div>


                </h2>

                <div>




                    <form>
                        @csrf


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">


                                {{-- Customer Name --}}


                                <label  class="block text-gray-400 text-sm font-medium mb-1">
                                    Product Name :  <span >{{ $order->customer->name }}</span>
                                </label>

                                <label  class="block text-gray-400 text-sm font-medium mb-1">
                                    Email : <span>{{ $order->customer->email ?? 'null' }}</span>
                                </label>

                                <label  class="block text-gray-400 text-sm font-medium mb-1">
                                    Phone : <span>{{ $order->customer->phone }}</span>
                                </label>

                                <label  class="block text-gray-400 text-sm font-medium mb-1">
                                    Order Date : <span>{{ $order->order_date }}</span>
                                </label>











                            </div>

                            {{-- Column 2 --}}
                            <div class="space-y-4">

                                <label class="block text-gray-400 text-sm font-medium mb-1">
                                    Order Invoice : <span>{{ $order->invoice_no }}</span>
                                </label>

                                <label for="product_name" class="block text-gray-400 text-sm font-medium mb-1">
                                    Payment Status : <span>{{ $order->payment_status }}</span>
                                </label>

                                <label for="product_name" class="block text-gray-400 text-sm font-medium mb-1">
                                    Paid Amount : <span>{{ $order->pay}}</span>
                                </label>

                                <label for="product_name" class="block text-gray-400 text-sm font-medium mb-1">
                                    Due Amount : <span>{{ $order->due}}</span>
                                </label>

                            </div>
                        </div>

                        {{-- <div class="flex justify-end mt-6">
                            <button type="submit"
                                class="button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                Save
                            </button>
                        </div> --}}
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

            // Note: The password toggle script from your original code was removed
            // as there are no password fields in this "Add Employee" form.
            // If you have other forms with password fields, you can use that script there.
        });
    </script>

@endsection