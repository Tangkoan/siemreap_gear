@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>




    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1"> 

            <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <div class="px-2">Edit Supplier</div>
                </h2>

                <div>
                    <form method="post" action="{{ route('supplier.update') }}" >
                        @csrf
                        <input type="hidden" name="id" value="{{ $supplier->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">
                                {{-- Supplier Name --}}
                                <div>
                                    <label for="name" class="block text-gray-400 text-sm font-medium mb-1">
                                        Supplier Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" required value="{{ $supplier->name }}"
                                           class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ">
                                        @error('name')
                                        <span class="text-danger text-red-500"> {{ $message }} </span>
                                        @enderror
                                </div>

                                {{-- Supplier Email --}}
                                <div>
                                    <label for="email" class="block text-gray-400 text-sm font-medium mb-1">
                                        Supplier Email <span class="text-red-500"></span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ $supplier->email }}"
                                           class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ">
                                           {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}

                                </div>




                            </div>

                            {{-- Column 2 --}}
                            <div class="space-y-4">
                                 {{-- Note --}}
                                <div>
                                    <label for="notes" class="block text-gray-400 text-sm font-medium mb-1">
                                        Note
                                    </label>
                                    <input type="text" id="notes" name="notes" value="{{ $supplier->notes }}"
                                           class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                                </div>

                                {{-- Supplier Phone --}}
                                <div>
                                    <label for="phone" class="block text-gray-400 text-sm font-medium mb-1">
                                        Supplier Phone <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone" name="phone" required value="{{ $supplier->phone }}"
                                           class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>



                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            // Image preview script (optional)
            $('#image').on('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    const preview = $('#image_preview');
                    preview.attr('src', URL.createObjectURL(file));
                    preview.removeClass('hidden');
                    preview.on('load', function() {
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