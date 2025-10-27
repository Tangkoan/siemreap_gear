@extends('admin/admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-4 sm:p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Side: Profile Summary Card --}}
        <div class="lg:col-span-1 bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-md p-6 flex flex-col items-center justify-center space-y-4 transition-colors duration-300">
            <img class="h-24 w-24 rounded-full object-cover border-4 border-primary shadow-lg"
                 src="{{ (!empty($adminData->photo)) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg')}}"
                 onerror="this.onerror=null;this.src='https://placehold.co/96x96/4f46e5/ffffff?text=User';"
                 alt="Admin Profile Picture">
            <h2 class="text-2xl font-bold text-default">{{ $adminData->name }}</h2>
            <p class="text-gray-500 dark:text-gray-400">{{ $adminData->email }}</p>

            <div class="mt-6 w-full text-left space-y-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                <p class="text-gray-700 dark:text-gray-300 font-medium">{{ __('messages.name') }}: <span class="font-normal text-gray-500 dark:text-gray-400">{{ Auth::user()->name }}</span></p>
                <p class="text-gray-700 dark:text-gray-300 font-medium">{{ __('messages.phone') }}: <span class="font-normal text-gray-500 dark:text-gray-400">{{ Auth::user()->phone }}</span></p>
                <p class="text-gray-700 dark:text-gray-300 font-medium">{{ __('messages.email') }}: <span class="font-normal text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</span></p>
            </div>

            
        </div>

        {{-- Right Side: Edit Profile Form --}}
        <div class="lg:col-span-2 bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-md p-6 transition-colors duration-300">
            <h2 class="text-xl text-primary mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span class="text-default">{{ __('messages.personal_info') }}</span>
            </h2>

            <form method="post" action="{{ route('admin.profile.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.name') }}
                    </label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-3 bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 ring-primary" value="{{ $adminData->name }}">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.email') }}
                    </label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 ring-primary" value="{{ $adminData->email }}">
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">
                        {{ __('messages.phone') }}
                    </label>
                    <input type="tel" name="phone" id="phone" required class="w-full px-4 py-3 bg-white/80 dark:bg-gray-900/80 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 ring-primary" value="{{ $adminData->phone }}">
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
                            <label for="image" class="cursor-pointer bg-primary hover:opacity-90 text-white py-2 px-4 rounded-md transition-colors duration-200 shadow-sm">
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
                    <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 ring-primary">
                        {{ __('messages.edit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- =============================================== --}}
    {{-- START: NEW APPEARANCE FORM (ទម្រង់ថ្មី) --}}
    {{-- =============================================== --}}
    @php
    // យក Settings មកបង្ហាញក្នុង Form
    // យើងត្រូវការ Default ដូចគ្នានឹងអ្វីដែលមានក្នុង <head>
    $defaults = [
        'light_primary_color' => '#4F46E5', 'light_text_color' => '#1F2937', 'light_bg_type' => 'default', 'light_bg_color' => '#F3F4F6', 'light_bg_image' => null,
        'dark_primary_color' => '#6366F1', 'dark_text_color' => '#F9FAFB', 'dark_bg_type' => 'default', 'dark_bg_color' => '#111827', 'dark_bg_image' => null,
    ];
    $s = array_merge($defaults, Auth::user()->appearance_settings ?? []);
    @endphp

    <div class="p-6 bg-white/80 dark:bg-gray-900/80 rounded-xl shadow-md mt-6" 
         x-data="{ 
            tab: 'light',
            light: {
                bg_type: '{{ $s['light_bg_type'] }}',
                primary_color: '{{ $s['light_primary_color'] }}',
                text_color: '{{ $s['light_text_color'] }}',
                bg_color: '{{ $s['light_bg_color'] }}',
                image_preview: '{{ ($s['light_bg_type'] == 'image' && $s['light_bg_image']) ? asset($s['light_bg_image']) : '' }}'
            },
            dark: {
                bg_type: '{{ $s['dark_bg_type'] }}',
                primary_color: '{{ $s['dark_primary_color'] }}',
                text_color: '{{ $s['dark_text_color'] }}',
                bg_color: '{{ $s['dark_bg_color'] }}',
                image_preview: '{{ ($s['dark_bg_type'] == 'image' && $s['dark_bg_image']) ? asset($s['dark_bg_image']) : '' }}'
            }
         }">
        
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Customize Appearance</h3>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
            <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                <button @click="tab = 'light'"
                        :class="tab === 'light' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                    Light Mode
                </button>
                <button @click="tab = 'dark'"
                        :class="tab === 'dark' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                    Dark Mode
                </button>
            </nav>
        </div>

        <form id="appearanceForm" enctype="multipart/form-data">
            @csrf
            
            <!-- Light Mode Panel -->
            <div x-show="tab === 'light'" class="space-y-4" style="display: none;">
                @include('admin.profile.partials.appearance_form_inputs', ['mode' => 'light', 's' => $s])
            </div>

            <!-- Dark Mode Panel -->
            <div x-show="tab === 'dark'" class="space-y-4" style="display: none;">
                @include('admin.profile.partials.appearance_form_inputs', ['mode' => 'dark', 's' => $s])
            </div>

            <div id="appearance_error" class="text-red-500 text-sm mt-4"></div>

            <div class="mt-6 flex justify-end">
                <button type="submit" id="saveAppearanceBtn" class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 focus:outline-none disabled:opacity-75 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
    {{-- =============================================== --}}
    {{-- END: NEW APPEARANCE FORM --}}
    {{-- =============================================== --}}

</div>

{{-- SCRIPT សម្រាប់ Profile Photo (កូដចាស់របស់អ្នក) --}}
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

{{-- SCRIPT សម្រាប់ Appearance Form (កូដថ្មីដែលបានកែ) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('appearanceForm');
    if (!form) return;

    const saveBtn = document.getElementById('saveAppearanceBtn');
    const errorDiv = document.getElementById('appearance_error');
    
    // Function ថ្មី៖ ដើម្បី Update CSS Variables ភ្លាមៗ
    function updateDynamicStyles(settings) {
        
        // ✅ START FIX #1: លុប Inline Style ចាស់ចោល
        // ត្រូវតែលុប style ចាស់ដែលជាប់នៅលើ <body> 
        // បើមិនដូច្នេះទេ វានឹង "ឈ្នះ" CSS Variables ថ្មីរបស់យើង
        document.body.removeAttribute('style');
        // ✅ END FIX #1

        // ស្វែងរក <style> tag របស់យើង
        let styleTag = document.getElementById('dynamic-user-styles');
        if (!styleTag) {
            styleTag = document.createElement('style');
            styleTag.id = 'dynamic-user-styles';
            document.head.appendChild(styleTag);
        }

        // កំណត់ Background ពិតប្រាកដដោយផ្អែកលើ Type
        // ប្រើ Default ពី PHP ដែលបាន print ចូលក្នុង JS
        const light_bg_final = settings.light_bg_type === 'color' ? settings.light_bg_color : '{{ $defaults['light_bg_color'] }}'; 
        const dark_bg_final = settings.dark_bg_type === 'color' ? settings.dark_bg_color : '{{ $defaults['dark_bg_color'] }}'; 
        
        // កំណត់ Background Image
        const light_image_final = (settings.light_bg_type === 'image' && settings.light_bg_image_url) ? `url(${settings.light_bg_image_url})` : 'none';
        const dark_image_final = (settings.dark_bg_type === 'image' && settings.dark_bg_image_url) ? `url(${settings.dark_bg_image_url})` : 'none';

        // បង្កើត CSS string
        const css = `
            :root {
                --primary-light: ${settings.light_primary_color};
                --text-light: ${settings.light_text_color};
                --bg-light: ${light_bg_final};
                --bg-image-light: ${light_image_final};

                --primary-dark: ${settings.dark_primary_color};
                --text-dark: ${settings.dark_text_color};
                --bg-dark: ${dark_bg_final};
                --bg-image-dark: ${dark_image_final};
            }

            /* ផ្នែកខាងក្រោមនេះត្រូវតែដូចគ្នានឹងអ្វីដែលមានក្នុង <head> */
            body {
                background-color: var(--bg-light);
                color: var(--text-light);
                background-image: var(--bg-image-light);
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-repeat: no-repeat;
            }
            .dark body {
                background-color: var(--bg-dark);
                color: var(--text-dark);
                background-image: var(--bg-image-dark);
            }
            .text-primary { color: var(--primary-light); }
            .dark .text-primary { color: var(--primary-dark); }
            .bg-primary { background-color: var(--primary-light); }
            .dark .bg-primary { background-color: var(--primary-dark); }
            .border-primary { border-color: var(--primary-light); }
            .dark .border-primary { border-color: var(--primary-dark); }
            .ring-primary { --tw-ring-color: var(--primary-light); }
            .dark .ring-primary { --tw-ring-color: var(--primary-dark); }
            .icon-primary { stroke: var(--primary-light); fill: var(--primary-light); }
            .dark .icon-primary { stroke: var(--primary-dark); fill: var(--primary-dark); }
            .text-default { color: var(--text-light); }
            .dark .text-default { color: var(--text-dark); }
        `;

        // បញ្ចូល CSS ថ្មីទៅក្នុង <style> tag
        styleTag.innerHTML = css;
    }


    // ដំណើរការ Form បែប AJAX (បានកែសម្រួល)
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
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, data })))
        .then(({ status, data }) => {
            if (status >= 400) { 
                if (data.errors) {
                    let errorMsg = Object.values(data.errors).flat().join(' ');
                    errorDiv.textContent = errorMsg;
                    toastr.error(errorMsg);
                } else {
                    errorDiv.textContent = data.message || 'An error occurred.';
                    toastr.error(data.message || 'An error occurred.');
                }
                throw new Error(data.message);
            }

            // --- ជោគជ័យ ---
            toastr.success(data.message);

            // អាប់ដេត Background ភ្លាមៗដោយហៅ Function ថ្មីរបស់យើង
            updateDynamicStyles(data.settings);

            // ✅ START FIX #2: លុបកូដ Alpine.js ដែលបង្ក Error ចេញ
            // យើងមិនត្រូវការ Update preview ភ្លាមៗទេ ព្រោះ Page ទាំងមូលបាន Update ហើយ
            
            /*
            const alpineData = form.closest('[x-data]').__x.data;
            if (data.settings.light_bg_image_url) {
                alpineData.light.image_preview = data.settings.light_bg_image_url;
            } else if (data.settings.light_bg_type !== 'image') {
                 alpineData.light.image_preview = ''; 
            }
            if (data.settings.dark_bg_image_url) {
                alpineData.dark.image_preview = data.settings.dark_bg_image_url;
            } else if (data.settings.dark_bg_type !== 'image') {
                 alpineData.dark.image_preview = '';
            }
            */
           // ✅ END FIX #2


        })
        .catch(error => {
            console.error('Appearance Update Error:', error);
            // កុំបង្ហាញ Error ក្រហម បើសិនជា 'success' ទើបនឹងបង្ហាញ
            // នេះជាការជួសជុលបណ្តោះអាសន្នចំពោះ Error ក្រហម
            if (!error.message.includes('data is not defined')) {
                 toastr.error('An unexpected error occurred. Please try again.');
            }
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Changes';
        });
    });
});
</script>


@endsection
