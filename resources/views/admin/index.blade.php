@extends('admin/admin_dashboard')
@section('admin')



                                            @php
    $date = date('d-F-Y');
    $today_paid = App\Models\Order::where('order_date', $date)->sum('pay');

    $total_paid = App\Models\Order::sum('pay');
    $total_due = App\Models\Order::sum('due');

    $completeorder = App\Models\Order::where('order_status', 'complete')->get();

    $pendingorder = App\Models\Order::where('order_status', 'pending')->get(); 

                                            @endphp














                                            <main class="flex-1 p-6 transition-colors duration-300">
                                                <h2 class="text-3xl font-semibold text-default mb-6">Dashboard</h2>

                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                                                    <div class="card-bg p-6 rounded-lg shadow-md transition-colors duration-300">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h3 class="text-xl font-semibold text-default">Total Paid</h3>
                                                            <div class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">New</div>
                                                        </div>
                                                        <p class="text-4xl font-bold text-green-600">$<span data-plugin="counterup">{{ $total_paid }}</span></p>



                                                    </div>

                                                    <div class="card-bg p-6 rounded-lg shadow-md transition-colors duration-300">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h3 class="text-xl font-semibold text-default">Total Due</h3>
                                                            <div class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full">New</div>
                                                        </div>
                                                        <p class="text-4xl font-bold text-blue-600">$<span data-plugin="counterup">{{ $total_due  }}</span></p>




                                                        <a href="#" class="text-sm text-blue-500 hover:underline flex items-center mt-3">
                                                            More Info 
                                                            <svg class="icon h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                                        </a>
                                                    </div>

                                                    <div class="card-bg p-6 rounded-lg shadow-md transition-colors duration-300">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h3 class="text-xl font-semibold text-default">Complete Order</h3>
                                                            <div class="bg-teal-100 text-teal-700 text-xs font-semibold px-2 py-1 rounded-full">New</div>
                                                        </div>
                                                        <p class="text-4xl font-bold text-teal-600"><span data-plugin="counterup">{{ count($completeorder)  }}</span></p>

                                                        <a href="#" class="text-sm text-blue-500 hover:underline flex items-center mt-3">
                                                            More Info 
                                                            <svg class="icon h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                                        </a>
                                                    </div>

                                                    <div class="card-bg p-6 rounded-lg shadow-md transition-colors duration-300">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <h3 class="text-xl font-semibold text-default">Pending Order</h3>
                                                            <div class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">New</div>
                                                        </div>
                                                        <p class="text-4xl font-bold text-red-600"><span data-plugin="counterup">{{ count($pendingorder)  }}</span></p>


                                                        

                                                        <a href="#" class="text-sm text-blue-500 hover:underline flex items-center mt-3">
                                                            More Info 
                                                            <svg class="icon h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                                                    <div class="card-bg p-6 rounded-lg shadow-md transition-colors duration-300">
                                                        <h3 class="text-xl font-semibold text-default mb-4">Sales by Category</h3>
                                                        <div class="chart-container" style="height: 250px;">
                                                            <canvas id="pieChart"></canvas>
                                                        </div>
                                                    </div>
                                                    <div class="card-bg p-6 rounded-lg shadow-md transition-colors duration-300">
                                                        <h3 class="text-xl font-semibold text-default mb-4">Weekly Sales</h3>
                                                        <div class="chart-container" style="height: 250px;">
                                                            <canvas id="barChart"></canvas>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-bg p-6 rounded-lg shadow-md transition-colors duration-300">
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h3 class="text-xl font-semibold text-default">Recent Sales</h3>
                                                        <button class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                                            <svg class="icon h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full card-bg text-default">
                                                            <thead>
                                                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                                                    <th class="py-3 px-4 text-left text-xs font-semibold text-table-header-text uppercase tracking-wider">Sale Id</th>
                                                                    <th class="py-3 px-4 text-left text-xs font-semibold text-table-header-text uppercase tracking-wider">Customer</th>
                                                                    <th class="py-3 px-4 text-left text-xs font-semibold text-table-header-text uppercase tracking-wider">Seller</th>
                                                                    <th class="py-3 px-4 text-left text-xs font-semibold text-table-header-text uppercase tracking-wider">Sub Total</th>
                                                                    <th class="py-3 px-4 text-left text-xs font-semibold text-table-header-text uppercase tracking-wider">Grand Total</th>
                                                                    <th class="py-3 px-4 text-left text-xs font-semibold text-table-header-text uppercase tracking-wider">Total Paid</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                                                    <td class="py-3 px-4 whitespace-nowrap">#001</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">Mr. Sarun</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">Sokchea</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">$100.00</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">$110.00</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap text-green-500">$110.00</td>
                                                                </tr>
                                                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                                                    <td class="py-3 px-4 whitespace-nowrap">#002</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">Ms. SreyLeak</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">Dara</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">$75.50</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">$80.00</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap text-red-500">$70.00</td>
                                                                </tr>
                                                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                                                    <td class="py-3 px-4 whitespace-nowrap">#003</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">Mr. Sovann</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">Sokchea</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">$250.00</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap">$270.00</td>
                                                                    <td class="py-3 px-4 whitespace-nowrap text-green-500">$270.00</td>
                                                                </tr>
                                                                </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </main>

                                            <script type="text/javascript">
                                                // Get original image source
                                                // This variable should be defined once when the page loads, outside of any functions that might redefine it.
                                                const originalImageSrc = document.getElementById('showImage').src;

                                                $(document).ready(function(){
                                                    const imageInput = $('#image');
                                                    const showImage = $('#showImage');
                                                    const fileNameSpan = $('#file-name');
                                                    const clearImageBtn = $('#clearImageBtn'); // Get the new clear button

                                                    // Function to reset image and file input to its original state
                                                    function resetImageUpload() {
                                                        imageInput.val(''); // Clear the file input
                                                        showImage.attr('src', originalImageSrc); // Reset image to original
                                                        fileNameSpan.text('No file chosen'); // Reset file name text
                                                        clearImageBtn.addClass('hidden'); // Hide the clear button
                                                    }

                                                    // Initialize state on page load: Show clear button if there's an actual profile photo
                                                    if (showImage.attr('src') !== "{{ url('upload/no_image.jpg') }}") {
                                                        clearImageBtn.removeClass('hidden');
                                                    }

                                                    // jQuery for Image Preview
                                                    imageInput.change(function(e){
                                                        // Check if a file is selected before proceeding
                                                        if (e.target.files && e.target.files[0]) {
                                                            var reader = new FileReader();
                                                            reader.onload = function(e_reader){
                                                                showImage.attr('src', e_reader.target.result);
                                                                fileNameSpan.text(e.target.files[0].name);
                                                                clearImageBtn.removeClass('hidden'); // Show the clear button
                                                            }
                                                            reader.readAsDataURL(e.target.files[0]);
                                                        } else {
                                                            // If the user opened the file dialog and then cancelled without choosing a file
                                                            // Or if the input value was somehow cleared directly
                                                            resetImageUpload();
                                                        }
                                                    });

                                                    // Event listener for Clear button
                                                    clearImageBtn.click(function() {
                                                        resetImageUpload();
                                                    });
                                                });
                                            </script>

@endsection

