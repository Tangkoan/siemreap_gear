@extends('admin/admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-4 sm:p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Side: Profile Summary Card --}}
        <div class="lg:col-span-1 bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-md p-6 flex flex-col items-center justify-center space-y-4 transition-colors duration-300">
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
        <div class="lg:col-span-2 bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-md p-6 transition-colors duration-300">
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
                    <input type="text" id="name" name="name" required class="w-full px-4 py-3 bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ $adminData->name }}">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.email') }}
                    </label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ $adminData->email }}">
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.phone') }}
                    </label>
                    <input type="tel" name="phone" id="phone" required class="w-full px-4 py-3 bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ $adminData->phone }}">
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


    {{-- បន្ថែមផ្នែកនេះទៅក្នុងទំព័រ Profile របស់អ្នក --}}
<div class="p-6 bg-white/80 dark:bg-gray-900/80 rounded-xl shadow-md mt-6">
    <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Customize Background</h3>

    <form id="appearanceForm" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">

            <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="background_type" value="default" class="form-radio text-blue-600" {{ Auth::user()->background_type == 'default' ? 'checked' : '' }}>
                    <span class="ml-2 dark:text-slate-300">Default</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="background_type" value="color" class="form-radio text-blue-600" {{ Auth::user()->background_type == 'color' ? 'checked' : '' }}>
                    <span class="ml-2 dark:text-slate-300">Color</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="background_type" value="image" class="form-radio text-blue-600" {{ Auth::user()->background_type == 'image' ? 'checked' : '' }}>
                    <span class="ml-2 dark:text-slate-300">Image</span>
                </label>
            </div>

            <div id="colorPickerWrapper" class="hidden pt-2">
                <label for="background_color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Choose Color</label>
                <input type="color" id="background_color" name="background_color" 
                       value="{{ Auth::user()->background_type == 'color' ? Auth::user()->background_value : '#ffffff' }}" 
                       class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
            </div>

            <div id="imageUploaderWrapper" class="hidden pt-2">
                <label for="background_image" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Upload Image</label>
                <input type="file" id="background_image" name="background_image" accept="image/*"
                       class="mt-1 block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100
                              dark:file:bg-slate-700 dark:file:text-slate-300 dark:hover:file:bg-slate-600"/>
                <img id="imagePreview" src="{{ (Auth::user()->background_type == 'image' && Auth::user()->background_value) ? asset(Auth::user()->background_value) : '' }}" alt="Image Preview" 
                     class="mt-4 rounded-lg max-h-48 {{ (Auth::user()->background_type == 'image' && Auth::user()->background_value) ? '' : 'hidden' }}">
            </div>

            <div id="appearance_error" class="text-red-500 text-sm"></div>

        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" id="saveAppearanceBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none disabled:opacity-75 transition-all">
                Save Changes
            </button>
        </div>
    </form>
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


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('appearanceForm');
    if (!form) return;

    const typeRadios = form.querySelectorAll('input[name="background_type"]');
    const colorWrapper = document.getElementById('colorPickerWrapper');
    const imageWrapper = document.getElementById('imageUploaderWrapper');
    const imageInput = document.getElementById('background_image');
    const imagePreview = document.getElementById('imagePreview');
    const saveBtn = document.getElementById('saveAppearanceBtn');
    const errorDiv = document.getElementById('appearance_error');

    // Function ដើម្បីបង្ហាញ/លាក់ Input
    function toggleInputs() {
        const selectedType = form.querySelector('input[name="background_type"]:checked').value;
        colorWrapper.classList.toggle('hidden', selectedType !== 'color');
        imageWrapper.classList.toggle('hidden', selectedType !== 'image');
    }

    // ហៅ Function ពេលទំព័របើក
    toggleInputs();

    // ថែម Event Listeners ទៅលើ Radio Buttons
    typeRadios.forEach(radio => radio.addEventListener('change', toggleInputs));

    // Logic សម្រាប់បង្ហាញរូប Preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // ដំណើរការ Form បែប AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';
        errorDiv.textContent = '';

        const formData = new FormData(this);

        fetch("{{ route('appearance.update') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                // CSRF token ត្រូវបានរួមបញ្ចូលក្នុង FormData រួចហើយ
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, data })))
        .then(({ status, data }) => {
            if (status >= 400) { 
                // ដោះស្រាយ Validation errors
                if (data.errors) {
                    let errorMsg = Object.values(data.errors).flat().join(' ');
                    errorDiv.textContent = errorMsg;
                    toastr.error(errorMsg); // អ្នកកំពុងប្រើ Toastr
                } else {
                    errorDiv.textContent = data.message || 'An error occurred.';
                    toastr.error(data.message || 'An error occurred.');
                }
                throw new Error(data.message);
            }

            // --- ជោគជ័យ ---
            toastr.success(data.message);

            // អាប់ដេត Background ភ្លាមៗដោយមិនបាច់ Reload ទំព័រ
            if (data.background_type === 'color') {
                document.body.style.backgroundImage = 'none';
                document.body.style.backgroundColor = data.background_value;
            } else if (data.background_type === 'image') {
                document.body.style.backgroundColor = '';
                document.body.style.backgroundImage = `url(${data.background_value})`;
                document.body.style.backgroundSize = 'cover';
                document.body.style.backgroundPosition = 'center';
                document.body.style.backgroundRepeat = 'no-repeat';
                document.body.style.backgroundAttachment = 'fixed';
            } else {
                // Default
                document.body.removeAttribute('style'); // លុប inline style ចោល
            }

            // អាប់ដេត src របស់ Preview ក្នុងករណី Upload រូបថ្មី
            if (data.background_type === 'image') {
                imagePreview.src = data.background_value;
                imagePreview.classList.remove('hidden');
            } else {
                imagePreview.classList.add('hidden');
            }

        })
        .catch(error => {
            console.error('Appearance Update Error:', error);
            toastr.error('An unexpected error occurred. Please try again.');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Changes';
        });
    });
});
</script>

@endsection
