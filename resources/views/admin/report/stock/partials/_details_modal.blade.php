{{-- <div id="detailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-70 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-1 border border-gray-700 rounded-lg w-full max-w-3xl shadow-2xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 scale-95 transition-transform duration-300">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4 px-5 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-semibold" id="modal-title">Transaction Details</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-700 dark:hover:text-white transition p-2 rounded-full -mr-2 text-2xl font-bold">&times;</button>
            </div>

            <div class="px-5 py-3">
                <div class="overflow-y-auto max-h-[60vh] border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full text-left">
                        <thead class="sticky top-0 bg-gray-100 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="p-3">{{ __('messages.date') }}</th>
                                <th class="p-3">{{ __('messages.type_stock') }}</th>
                                <th class="p-3 text-right">Quantity</th> {{-- Added text-right for consistency --}}
                                <th class="p-3">Reference</th>
                            </tr>
                        </thead>
                        <tbody id="modal-table-body" class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            {{-- Details will be loaded here by AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="items-center px-5 py-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                <button id="closeModalBtn" class="px-6 py-2 bg-red-600 dark:bg-red-700 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 dark:hover:bg-red-800 transition duration-150 ease-in-out">Close</button>
            </div>
        </div>
    </div>
{{-- </div> --}} --}}