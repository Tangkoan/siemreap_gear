@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 card-bg rounded-lg shadow-xl p-6 flex flex-col items-center justify-center space-y-4 transition-all duration-300 transform">
                <img class="h-24 w-24 rounded-full object-cover border-4 border-indigo-500 shadow-lg" 
                     src="{{ (!empty($adminData->photo)) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg')}}" 
                     onerror="this.onerror=null;this.src='https://placehold.co/96x96/4f46e5/ffffff?text=User';" 
                     alt="Admin Profile Picture">
                <h2 class="text-2xl font-bold text-default">{{ $adminData->name }}</h2>
                {{-- <p class="text-gray-400">{{ old('name', Auth::user()->email) }}</p> --}}
                <p class="text-gray-400">{{ $adminData->email }}</p>

                <div class="flex space-x-3 mt-4">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-full transition-colors duration-200 shadow-md">
                        Follow
                    </button>
                    <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-full transition-colors duration-200 shadow-md">
                        Message
                    </button>
                </div>

                <div class="mt-6 w-full text-left space-y-2">
                    <p class="text-default text-lg font-medium">Name: <span class="font-normal text-gray-400">{{ old('name', Auth::user()->name) }}</span></p>
                    <p class="text-default text-lg font-medium">Phone: <span class="font-normal text-gray-400">{{ old('name', Auth::user()->phone) }}</span></p>
                    <p class="text-default text-lg font-medium">Email: <span class="font-normal text-gray-400">{{ old('name', Auth::user()->email) }}</span></p>
                </div>

                <div class="flex space-x-4 mt-6">
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.62H8.067v-2.903h2.371V9.89c0-2.35 1.424-3.642 3.545-3.642 1.023 0 1.903.076 2.168.11V8.4h-1.25c-1.22 0-1.458.579-1.458 1.423v1.89h2.598l-.423 2.903h-2.175V22c4.781-.751 8.438-4.888 8.438-9.879C22 6.477 17.523 2 12 2z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.5 14.5c-.82 0-1.5-.68-1.5-1.5s.68-1.5 1.5-1.5 1.5.68 1.5 1.5-.68 1.5-1.5 1.5zm-9 0c-.82 0-1.5-.68-1.5-1.5s.68-1.5 1.5-1.5 1.5.68 1.5 1.5-.68 1.5-1.5 1.5zm4.5-9c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5 4.5-2.01 4.5-4.5-2.01-4.5-4.5-4.5z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.46 6c-.77.344-1.6.577-2.46.68.89-.533 1.56-1.378 1.88-2.38-.83.49-1.75.84-2.72.99-.78-.83-1.89-1.35-3.13-1.35-2.37 0-4.3 1.93-4.3 4.3 0 .34.04.67.11.98-3.57-.18-6.74-1.89-8.86-4.48-.37.64-.58 1.39-.58 2.19 0 1.49.76 2.81 1.91 3.59-.7-.02-1.35-.21-1.92-.53v.05c0 2.08 1.48 3.82 3.45 4.22-.36.1-.74.15-1.13.15-.28 0-.55-.03-.8-.08.55 1.71 2.14 2.95 4.02 2.98-1.47 1.15-3.32 1.84-5.33 1.84-.35 0-.69-.02-1.03-.06 1.9 1.22 4.15 1.94 6.58 1.94 7.9 0 12.2-6.55 12.2-12.2 0-.18-.01-.36-.01-.54.84-.6 1.57-1.35 2.14-2.2z"/></svg>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-2 card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform ">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />  </svg>
                    PERSONAL INFO
                </h2>


                {{-- class="grid grid-cols-1 md:grid-cols-2 gap-6" --}}
                <form method="post" action="{{ route('admin.profile.store') }}" enctype="multipart/form-data">
                        @csrf
                    <div>
                        <label for="name" class="block text-gray-400 text-sm font-medium mb-2">
                             Name
                        </label>
                        <input type="text" id="name"  name="name" required class="input-field-custom w-full px-4 py-3 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" value=" {{ $adminData->name }}">
                    </div>

                    <div class="py-2">
                        <label for="email" class="block text-gray-400 text-sm font-medium mb-2">
                            Email
                        </label>
                        <input type="email" id="email" name="email" required class="input-field-custom w-full px-4 py-3 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" value="{{ $adminData->email }}">
                    </div>

                    <div class="py-2">
                        <label for="phone" class="block text-gray-400 text-sm font-medium mb-2">
                            Phone Number
                        </label>
                        <input type="tel" name="phone" id="phone" required class="input-field-custom w-full px-4 py-3 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" value="{{ $adminData->phone }}">
                    </div>



                    <div class="md:col-span-2">
                        <label for="image" class="block text-gray-400 text-sm font-medium mb-2">
                            Admin Profile Image
                        </label>
                        <div class="flex items-center space-x-2">
                            <input type="file" name="photo" id="image" class="hidden">
                            <label for="image" class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200 shadow-md">
                                Choose File
                            </label>
                            <span id="file-name" class="text-gray-400 px-2">No file chosen</span>
                        </div>
                        <div class="relative inline-block mt-4"> <img class="h-20 w-20 rounded-full object-cover border-2 border-indigo-700"  id="showImage"
                                 src="{{ (!empty($adminData->photo)) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}"
                                 alt="Current Profile Picture">

                            <button type="button" id="clearImageBtn" class="hidden absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                </button>
                        </div>
                    </div>


                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
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