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
                <div class="px-2">Add Employee</div>
            </h2>
            
            <div>
                {{-- You might want to add an action and enctype for file uploads --}}
                {{-- e.g., <form method="post" action="{{ route('employees.store') }}" enctype="multipart/form-data"> --}}
                <form method="post" action="{{ route('employee.store') }}"  enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                        
                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            {{-- Employee Name --}}
                            <div>
                                <label for="name" class="block text-gray-400 text-sm font-medium mb-1">
                                    Employee Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required
                                       class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') is-invalid @enderror">
                                    @error('name')
                                    <span class="text-danger text-red-500"> {{ $message }} </span>
                                    @enderror
                            </div>

                            {{-- Employee Email --}}
                            <div>
                                <label for="email" class="block text-gray-400 text-sm font-medium mb-1">
                                    Employee Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required
                                       class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') is-invalid @enderror">
                                       {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}
                                    @error('email')
                                        <span class="text-danger text-red-500"> {{ $message }} </span>
                                    @enderror
                            </div>

                            {{-- Employee Phone --}}
                            <div>
                                <label for="phone" class="block text-gray-400 text-sm font-medium mb-1">
                                    Employee Phone <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" required
                                       class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            {{-- Image --}}
                            <div class="mb-2">
                                <label for="image" class="block text-gray-400 text-sm font-medium mb-2">
                                    Employee Image <span class="text-red-500">*</span>
                                </label>
                                <input type="file" id="image" name="image" required
                                       class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-500 file:text-white hover:file:bg-gray-600 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                {{-- Image preview script can be added here if needed --}}
                                <img id="image_preview" src="#" alt="Image Preview" class="mt-2 rounded-md max-h-40 hidden"/>
                            </div>
                            
                        </div>

                        {{-- Column 2 --}}
                        <div class="space-y-4">
                             {{-- Experience --}}
                            <div>
                                <label for="experience" class="block text-gray-400 text-sm font-medium mb-1">
                                    Experience
                                </label>
                                <input type="text" id="experience" name="experience"
                                       class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                {{-- Consider <textarea> for more detailed experience --}}
                                {{-- <textarea id="experience" name="experience" rows="3" class="w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea> --}}
                            </div>

                            {{-- City --}}
                            <div>
                                <label for="city" class="block text-gray-400 text-sm font-medium mb-1">
                                    City
                                </label>
                                <input type="text" id="city" name="city"
                                       class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            {{-- Address --}}
                            <div>
                                <label for="address" class="block text-gray-400 text-sm font-medium mb-1">
                                    Address
                                </label>
                                <input type="text" id="address" name="address"
                                       class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="color-primary hover:bg-red-600 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
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