@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>




    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            {{-- <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform"> --}}
                <div class="lg:col-span-full p-0">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                    </svg>
                    <div class="px-2">Add Expense</div>
                </h2>

                <div>
                    <form method="post" action="{{ route('expense.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">
                                {{-- Expense Details --}}
                                <div>
                                    <label for="details" class="block text-gray-400 text-sm font-medium mb-1">
                                        Expense Details
                                    </label>
                                    <input type="text" id="details" name="details" required 
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                                    @error('details')
                                        <span class="text-danger text-red-500"> {{ $message }} </span>
                                    @enderror
                                </div>




                                {{-- amount --}}
                                <div>
                                    <label for="amount" class="block text-gray-400 text-sm font-medium mb-1">
                                        Amount <Address></Address>
                                    </label>
                                    <input type="text" id="amount" name="amount" required
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>










                                {{-- Date --}}
                                <div>

                                    <input type="hidden" name="date" class="form-control" value="{{ date('d-m-Y') }}">

                                </div>

                                {{-- amount --}}
                                <div>
                                    <input type="hidden" name="month" class="form-control" value="{{ date('F') }}">
                                </div>

                                {{-- amount --}}
                                <div>
                                    <input type="hidden" name="year" class="form-control" value="{{ date('Y') }}">

                                </div>





                            </div>







                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit"
                                class="button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                Save
                            </button>
                        </div>
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