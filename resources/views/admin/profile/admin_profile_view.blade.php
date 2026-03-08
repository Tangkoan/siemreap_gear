@extends('admin/admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-4 sm:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ✅ Profile Card --}}
            <div class="lg:col-span-1 card-dynamic-bg rounded-lg shadow-md p-6 flex flex-col items-center justify-center space-y-4 transition-colors duration-300">
                <img class="h-24 w-24 rounded-full object-cover border-4 border-primary shadow-lg"
                    src="{{ !empty($adminData->photo) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}"
                    onerror="this.onerror=null;this.src='https://placehold.co/96x96/4f46e5/ffffff?text=User';"
                    alt="Admin Profile Picture">
                <h2 class="text-2xl font-bold text-default">{{ $adminData->name }}</h2>
                <p class="text-default ">{{ $adminData->email }}</p>

                <div class="mt-6 w-full text-left space-y-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <p class="text-default font-medium">{{ __('messages.name') }}: <span
                            class="font-normal text-default ">{{ Auth::user()->name }}</span></p>
                    <p class="text-default font-medium">{{ __('messages.phone') }}: <span
                            class="font-normal text-default ">{{ Auth::user()->phone }}</span></p>
                    <p class="text-default font-medium">{{ __('messages.email') }}: <span
                            class="font-normal text-default ">{{ Auth::user()->email }}</span></p>
                </div>
            </div>

            {{-- ✅ Profile Edit Form --}}
            <div class="lg:col-span-2 card-dynamic-bg rounded-lg shadow-md p-6 transition-colors duration-300">
                <h2 class="text-xl text-default mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="text-default">{{ __('messages.personal_info') }}</span>
                </h2>

                <form method="post" action="{{ route('admin.profile.store') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-default text-sm font-medium mb-2">
                            {{ __('messages.name') }}
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-3 card-dynamic-bg text-defalut border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            value="{{ $adminData->name }}">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-default text-sm font-medium mb-2">
                            {{ __('messages.email') }}
                        </label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 card-dynamic-bg text-defalut border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            value="{{ $adminData->email }}">
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-default text-sm font-medium mb-2">
                            {{ __('messages.phone') }}
                        </label>
                        <input type="tel" name="phone" id="phone" required
                            class="w-full px-4 py-3 card-dynamic-bg text-defalut border border-primary rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                            value="{{ $adminData->phone }}">
                    </div>

                    {{-- Image Upload --}}
                    <div>
                        <label for="image" class="block text-default text-sm font-medium mb-2">
                            {{ __('messages.user_profile_image') }}
                        </label>
                        <div class="flex items-center space-x-4">
                            <img class="h-20 w-20 rounded-full object-cover border-2 border-primary"
                                id="showImage"
                                src="{{ !empty($adminData->photo) ? url('upload/admin_image/' . $adminData->photo) : url('upload/no_image.jpg') }}"
                                alt="Current Profile Picture">

                            <div class="flex-grow">
                                <input type="file" name="photo" id="image" class="hidden">
                                <label for="image"
                                    class="cursor-pointer bg-primary hover:opacity-90 text-white py-2 px-4 rounded-md transition-colors duration-200 shadow-sm">
                                    {{ __('messages.chosse_file') }}
                                </label>
                                <span id="file-name"
                                    class="text-default ml-3 text-sm">{{ __('messages.no_file_chosse') }}</span>
                            </div>

                            <div class="relative">
                                <button type="button" id="clearImageBtn"
                                    class="hidden absolute -top-8 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>


                    {{-- Submit Button --}}
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            @if(!Auth::user()->can('admin.profile.edit')) disabled @endif
                            class="bg-primary hover:opacity-90 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('messages.edit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ✅ PHP Block: កំណត់ Defaults ឱ្យគ្រប់គ្រាន់ ដើម្បីកុំឱ្យ Error --}}
        @php
            $defaults = [
                // --- Global ---
                'light_primary_color' => '#4F46E5', 'light_text_color' => '#1F2937',
                'light_bg_type' => 'default', 'light_bg_color' => '#F3F4F6', 'light_bg_image' => null,
                'dark_primary_color' => '#6366F1', 'dark_text_color' => '#F9FAFB',
                'dark_bg_type' => 'default', 'dark_bg_color' => '#111827', 'dark_bg_image' => null,

                // --- Card ---
                'light_card_type' => 'default', 'light_card_color1' => '#FFFFFF', 'light_card_opacity' => 80, 'light_card_color2' => '#F9FAFB', 'light_card_gradient_dir' => 'to right',
                'dark_card_type' => 'default', 'dark_card_color1' => '#1F2937', 'dark_card_opacity' => 80, 'dark_card_color2' => '#111827', 'dark_card_gradient_dir' => 'to right',

                // --- Header ---
                'light_header_type' => 'default', 'light_header_bg_color' => '#FFFFFF', 'light_header_opacity' => 100, 'light_header_blur' => 10, 'light_header_color2' => '#FFFFFF', 'light_header_gradient_dir' => 'to right', 'light_header_image' => null,
                'dark_header_type' => 'default', 'dark_header_bg_color' => '#1F2937', 'dark_header_opacity' => 100, 'dark_header_blur' => 10, 'dark_header_color2' => '#111827', 'dark_header_gradient_dir' => 'to right', 'dark_header_image' => null,

                // --- Sidebar ---
                'light_sidebar_type' => 'default', 'light_sidebar_bg_color' => '#FFFFFF', 'light_sidebar_opacity' => 100, 'light_sidebar_blur' => 10, 'light_sidebar_color2' => '#FFFFFF', 'light_sidebar_gradient_dir' => 'to bottom', 'light_sidebar_image' => null,
                'dark_sidebar_type' => 'default', 'dark_sidebar_bg_color' => '#111827', 'dark_sidebar_opacity' => 100, 'dark_sidebar_blur' => 10, 'dark_sidebar_color2' => '#000000', 'dark_sidebar_gradient_dir' => 'to bottom', 'dark_sidebar_image' => null,
            ];

            $s = array_merge($defaults, Auth::user()->appearance_settings ?? []);

            // ✅ បង្កើត Array សម្រាប់ Alpine.js
            $alpineData = [
                'tab' => 'light',
                'light' => [
                    'bg_type' => $s['light_bg_type'],
                    'primary_color' => $s['light_primary_color'],
                    'text_color' => $s['light_text_color'],
                    'bg_color' => $s['light_bg_color'],
                    'image_preview' => ($s['light_bg_type'] == 'image' && !empty($s['light_bg_image'])) ? asset($s['light_bg_image']) : '',
                    
                    'card_type' => $s['light_card_type'],
                    'card_color1' => $s['light_card_color1'],
                    'card_opacity' => $s['light_card_opacity'],
                    'card_color2' => $s['light_card_color2'],
                    'card_gradient_dir' => $s['light_card_gradient_dir'],

                    'header_type' => $s['light_header_type'],
                    'header_bg_color' => $s['light_header_bg_color'],
                    'header_opacity' => $s['light_header_opacity'],
                    'header_blur' => $s['light_header_blur'],
                    'header_color2' => $s['light_header_color2'],
                    'header_gradient_dir' => $s['light_header_gradient_dir'],
                    'header_image_preview' => (($s['light_header_type'] ?? '') == 'image' && !empty($s['light_header_image'])) ? asset($s['light_header_image']) : '',

                    'sidebar_type' => $s['light_sidebar_type'],
                    'sidebar_bg_color' => $s['light_sidebar_bg_color'],
                    'sidebar_opacity' => $s['light_sidebar_opacity'],
                    'sidebar_blur' => $s['light_sidebar_blur'],
                    'sidebar_color2' => $s['light_sidebar_color2'],
                    'sidebar_gradient_dir' => $s['light_sidebar_gradient_dir'],
                    'sidebar_image_preview' => (($s['light_sidebar_type'] ?? '') == 'image' && !empty($s['light_sidebar_image'])) ? asset($s['light_sidebar_image']) : '',
                ],
                'dark' => [
                    'bg_type' => $s['dark_bg_type'],
                    'primary_color' => $s['dark_primary_color'],
                    'text_color' => $s['dark_text_color'],
                    'bg_color' => $s['dark_bg_color'],
                    'image_preview' => ($s['dark_bg_type'] == 'image' && !empty($s['dark_bg_image'])) ? asset($s['dark_bg_image']) : '',

                    'card_type' => $s['dark_card_type'],
                    'card_color1' => $s['dark_card_color1'],
                    'card_opacity' => $s['dark_card_opacity'],
                    'card_color2' => $s['dark_card_color2'],
                    'card_gradient_dir' => $s['dark_card_gradient_dir'],

                    'header_type' => $s['dark_header_type'],
                    'header_bg_color' => $s['dark_header_bg_color'],
                    'header_opacity' => $s['dark_header_opacity'],
                    'header_blur' => $s['dark_header_blur'],
                    'header_color2' => $s['dark_header_color2'],
                    'header_gradient_dir' => $s['dark_header_gradient_dir'],
                    'header_image_preview' => (($s['dark_header_type'] ?? '') == 'image' && !empty($s['dark_header_image'])) ? asset($s['dark_header_image']) : '',

                    'sidebar_type' => $s['dark_sidebar_type'],
                    'sidebar_bg_color' => $s['dark_sidebar_bg_color'],
                    'sidebar_opacity' => $s['dark_sidebar_opacity'],
                    'sidebar_blur' => $s['dark_sidebar_blur'],
                    'sidebar_color2' => $s['dark_sidebar_color2'],
                    'sidebar_gradient_dir' => $s['dark_sidebar_gradient_dir'],
                    'sidebar_image_preview' => (($s['dark_sidebar_type'] ?? '') == 'image' && !empty($s['dark_sidebar_image'])) ? asset($s['dark_sidebar_image']) : '',
                ]
            ];
        @endphp

        {{-- ✅ Appearance Form Container --}}
        <div class="p-6 card-dynamic-bg rounded-xl shadow-md mt-6" x-data='@json($alpineData)'>
            <h3 class="text-lg font-medium text-defalut mb-4">Customize Appearance</h3>

            <div class="border-b border-primary mb-4">
                <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                    <button @click="tab = 'light'"
                        :class="tab === 'light' ? 'border-primary text-primary' : 'border-transparent text-default dark:hover:border-primary'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                        Light Mode
                    </button>
                    <button @click="tab = 'dark'"
                        :class="tab === 'dark' ? 'border-primary text-primary' : 'border-transparent text-default'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                        Dark Mode
                    </button>
                </nav>
            </div>

            <form id="appearanceForm" enctype="multipart/form-data">
                @csrf

                <div x-show="tab === 'light'" class="space-y-4">
                    @include('admin.profile.partials.appearance_form_inputs', ['mode' => 'light', 's' => $s])
                </div>

                <div x-show="tab === 'dark'" class="space-y-4">
                    @include('admin.profile.partials.appearance_form_inputs', ['mode' => 'dark', 's' => $s])
                </div>

                <div id="appearance_error" class="text-red-500 text-sm mt-4"></div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" id="saveAppearanceBtn"
                        @if(!Auth::user()->can('admin.profile.edit')) disabled @endif
                        class="px-4 py-2 bg-primary text-white rounded-md hover:opacity-90 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- ✅ Profile Image Script --}}
    <script type="text/javascript">
        $(document).ready(function() {
            const originalImageSrc = $('#showImage').attr('src');
            const noImageSrc = "{{ url('upload/no_image.jpg') }}";
            const imageInput = $('#image');
            const showImage = $('#showImage');
            const fileNameSpan = $('#file-name');
            const clearImageBtn = $('#clearImageBtn');

            function resetImageUpload() {
                imageInput.val('');
                showImage.attr('src', originalImageSrc);
                fileNameSpan.text('{{ __('messages.no_file_chosse') }}');
                if (originalImageSrc === noImageSrc) {
                    clearImageBtn.addClass('hidden');
                }
            }

            if (originalImageSrc !== noImageSrc) {
                clearImageBtn.removeClass('hidden');
            }

            imageInput.change(function(e) {
                if (e.target.files && e.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e_reader) {
                        showImage.attr('src', e_reader.target.result);
                        fileNameSpan.text(e.target.files[0].name);
                        clearImageBtn.removeClass('hidden');
                    }
                    reader.readAsDataURL(e.target.files[0]);
                } else {
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

    {{-- ✅ Dynamic Styles & Form Script --}}
    <script>
        // Global Helper Functions
        function hexToRgba(hex, alpha = 100) {
            let c;
            if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
                c = hex.substring(1).split('');
                if (c.length == 3) {
                    c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c = '0x' + c.join('');
                return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',' + (alpha / 100) + ')';
            }
            return `rgba(255, 255, 255, ${alpha / 100})`;
        }

        function getBgStyle(type, color1, opacity, color2, dir, imgUrl) {
            if (type === 'solid') return hexToRgba(color1, opacity);
            if (type === 'blur') return hexToRgba(color1, opacity);
            if (type === 'gradient') return `linear-gradient(${dir}, ${color1}, ${color2})`;
            if (type === 'image' && imgUrl) return `url(${imgUrl}) center/cover no-repeat`;
            return null;
        }

        function getBackdropFilter(type, amount) {
            return type === 'blur' ? `blur(${amount}px)` : 'none';
        }

        function updateDynamicStyles(settings) {
            document.body.removeAttribute('style');
            let styleTag = document.getElementById('dynamic-user-styles');
            if (!styleTag) { // Create if missing
                styleTag = document.createElement('style');
                styleTag.id = 'dynamic-user-styles';
                document.head.appendChild(styleTag);
            }

            // Defaults for Fallback (Taken from PHP Logic)
            const defaultLightBg = '{{ $defaults['light_bg_color'] }}';
            const defaultDarkBg = '{{ $defaults['dark_bg_color'] }}';

            // Main Background
            const light_bg_final = settings.light_bg_type === 'color' ? settings.light_bg_color : defaultLightBg;
            const dark_bg_final = settings.dark_bg_type === 'color' ? settings.dark_bg_color : defaultDarkBg;
            const light_image_final = (settings.light_bg_type === 'image' && settings.light_bg_image_url) ? `url(${settings.light_bg_image_url})` : 'none';
            const dark_image_final = (settings.dark_bg_type === 'image' && settings.dark_bg_image_url) ? `url(${settings.dark_bg_image_url})` : 'none';

            // Card Logic
            let light_card_bg_final = 'rgba(255, 255, 255, 0.8)';
            if (settings.light_card_type === 'solid') light_card_bg_final = hexToRgba(settings.light_card_color1, settings.light_card_opacity);
            else if (settings.light_card_type === 'gradient') light_card_bg_final = `linear-gradient(${settings.light_card_gradient_dir}, ${settings.light_card_color1}, ${settings.light_card_color2})`;

            let dark_card_bg_final = 'rgba(30, 41, 59, 0.8)';
            if (settings.dark_card_type === 'solid') dark_card_bg_final = hexToRgba(settings.dark_card_color1, settings.dark_card_opacity);
            else if (settings.dark_card_type === 'gradient') dark_card_bg_final = `linear-gradient(${settings.dark_card_gradient_dir}, ${settings.dark_card_color1}, ${settings.dark_card_color2})`;

            // Header Logic
            const h_light = getBgStyle(settings.light_header_type, settings.light_header_bg_color, settings.light_header_opacity, settings.light_header_color2, settings.light_header_gradient_dir, settings.light_header_image_url);
            const h_dark = getBgStyle(settings.dark_header_type, settings.dark_header_bg_color, settings.dark_header_opacity, settings.dark_header_color2, settings.dark_header_gradient_dir, settings.dark_header_image_url);

            // Sidebar Logic
            const s_light = getBgStyle(settings.light_sidebar_type, settings.light_sidebar_bg_color, settings.light_sidebar_opacity, settings.light_sidebar_color2, settings.light_sidebar_gradient_dir, settings.light_sidebar_image_url);
            const s_dark = getBgStyle(settings.dark_sidebar_type, settings.dark_sidebar_bg_color, settings.dark_sidebar_opacity, settings.dark_sidebar_color2, settings.dark_sidebar_gradient_dir, settings.dark_sidebar_image_url);

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

                    --card-bg-light: ${light_card_bg_final};
                    --card-bg-dark: ${dark_card_bg_final};

                    --header-bg-light: ${h_light || '#ffffff'};
                    --header-backdrop-light: ${getBackdropFilter(settings.light_header_type, settings.light_header_blur)};
                    
                    --header-bg-dark: ${h_dark || '#1f2937'};
                    --header-backdrop-dark: ${getBackdropFilter(settings.dark_header_type, settings.dark_header_blur)};

                    --sidebar-bg-light: ${s_light || '#ffffff'};
                    --sidebar-backdrop-light: ${getBackdropFilter(settings.light_sidebar_type, settings.light_sidebar_blur)};

                    --sidebar-bg-dark: ${s_dark || '#111827'};
                    --sidebar-backdrop-dark: ${getBackdropFilter(settings.dark_sidebar_type, settings.dark_sidebar_blur)};
                }

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

                .card-dynamic-bg {
                    background: var(--card-bg-light);
                }
                .dark .card-dynamic-bg {
                    background: var(--card-bg-dark);
                }

                .header-dynamic-bg {
                    background: var(--header-bg-light) !important;
                    backdrop-filter: var(--header-backdrop-light);
                    -webkit-backdrop-filter: var(--header-backdrop-light);
                }
                .dark .header-dynamic-bg {
                    background: var(--header-bg-dark) !important;
                    backdrop-filter: var(--header-backdrop-dark);
                    -webkit-backdrop-filter: var(--header-backdrop-dark);
                }

                .sidebar-dynamic-bg {
                    background: var(--sidebar-bg-light) !important;
                    backdrop-filter: var(--sidebar-backdrop-light);
                    -webkit-backdrop-filter: var(--sidebar-backdrop-light);
                }
                .dark .sidebar-dynamic-bg {
                    background: var(--sidebar-bg-dark) !important;
                    backdrop-filter: var(--sidebar-backdrop-dark);
                    -webkit-backdrop-filter: var(--sidebar-backdrop-dark);
                }

                /* Helpers */
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
            styleTag.innerHTML = css;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('appearanceForm');
            const saveBtn = document.getElementById('saveAppearanceBtn');
            const errorDiv = document.getElementById('appearance_error');

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveBtn.disabled = true;
                    saveBtn.textContent = 'Saving...';
                    errorDiv.textContent = '';

                    const formData = new FormData(this);

                    fetch("{{ route('appearance.update') }}", {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(response => response.json().then(data => ({ status: response.status, data })))
                        .then(({ status, data }) => {
                            if (status >= 400) {
                                let errorMsg = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Error');
                                errorDiv.textContent = errorMsg;
                                toastr.error(errorMsg);
                                throw new Error(data.message);
                            }
                            toastr.success(data.message);
                            updateDynamicStyles(data.settings);
                        })
                        .catch(error => console.error(error))
                        .finally(() => {
                            saveBtn.disabled = false;
                            saveBtn.textContent = 'Save Changes';
                        });
                });
            }
        });
    </script>
@endsection