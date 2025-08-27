@extends('admin/admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-4 sm:p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Side: Profile Summary Card --}}
        <div class="lg:col-span-1 bg-white dark:bg-gray-900 rounded-lg shadow-md p-6 flex flex-col items-center justify-center space-y-4 transition-colors duration-300">
            <img class="h-24 w-24 rounded-full object-cover border-4 border-indigo-500 shadow-lg"
                 src="{{ (!empty($adminData->photo)) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg')}}"
                 onerror="this.onerror=null;this.src='https://placehold.co/96x96/4f46e5/ffffff?text=User';"
                 alt="Admin Profile Picture">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $adminData->name }}</h2>
            <p class="text-gray-500 dark:text-gray-400">{{ $adminData->email }}</p>

            <div class="mt-6 w-full text-left space-y-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                <p class="text-gray-700 dark:text-gray-300 font-medium">{{ __('messages.name') }}: <span class="font-normal text-gray-500 dark:text-gray-400">{{ Auth::user()->name }}</span></p>
                <p class="text-gray-700 dark:text-gray-300 font-medium">{{ __('messages.phone') }}: <span class="font-normal text-gray-500 dark:text-gray-400">{{ Auth::user()->phone }}</span></p>
                <p class="text-gray-700 dark:text-gray-300 font-medium">{{ __('messages.email') }}: <span class="font-normal text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</span></p>
            </div>

            
        </div>

        {{-- Right Side: Edit Profile Form --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-lg shadow-md p-6 transition-colors duration-300">
            <h2 class="text-xl  text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                {{ __('messages.personal_info') }}
            </h2>

            <form method="post" action="{{ route('admin.profile.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.name') }}
                    </label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ $adminData->name }}">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.email') }}
                    </label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ $adminData->email }}">
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.phone') }}
                    </label>
                    <input type="tel" name="phone" id="phone" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ $adminData->phone }}">
                </div>

                {{-- Image Upload --}}
                <div>
                    <label for="image" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.user_profile_image') }}
                    </label>
                    <div class="flex items-center space-x-4">
                        <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600" id="showImage"
                             src="{{ (!empty($adminData->photo)) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}"
                             alt="Current Profile Picture">
                        
                        <div class="flex-grow">
                             <input type="file" name="photo" id="image" class="hidden">
                             <label for="image" class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white  py-2 px-4 rounded-md transition-colors duration-200 shadow-sm">
                                 {{ __('messages.chosse_file') }}
                             </label>
                             <span id="file-name" class="text-gray-500 dark:text-gray-400 ml-3 text-sm">{{ __('messages.no_file_chosse') }}</span>
                        </div>
                        
                        <div class="relative">
                             <button type="button" id="clearImageBtn" class="hidden absolute -top-8 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">
                                 <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                 </svg>
                             </button>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('messages.edit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        const originalImageSrc = $('#showImage').attr('src');
        const noImageSrc = "{{ url('upload/no_image.jpg') }}";
        const imageInput = $('#image');
        const showImage = $('#showImage');
        const fileNameSpan = $('#file-name');
        const clearImageBtn = $('#clearImageBtn');

        function resetImageUpload() {
            imageInput.val('');
            showImage.attr('src', originalImageSrc);
            fileNameSpan.text('{{ __("messages.no_file_chosse") }}');
            // Only hide the clear button if the original image was the placeholder
            if (originalImageSrc === noImageSrc) {
                clearImageBtn.addClass('hidden');
            }
        }

        // Show clear button on load if a real photo exists
        if (originalImageSrc !== noImageSrc) {
            clearImageBtn.removeClass('hidden');
        }

        imageInput.change(function(e){
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e_reader){
                    showImage.attr('src', e_reader.target.result);
                    fileNameSpan.text(e.target.files[0].name);
                    clearImageBtn.removeClass('hidden');
                }
                reader.readAsDataURL(e.target.files[0]);
            } else {
                // This case handles when a user cancels the file dialog
                // We shouldn't reset if they already had a picture, only if they selected one and then cancelled
                if (showImage.attr('src') !== originalImageSrc) {
                     resetImageUpload();
                }
            }
        });

        clearImageBtn.click(function() {
            resetImageUpload();
        });
    });
</script>

@endsection
