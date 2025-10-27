@php $modePrefix = $mode; @endphp {{-- 'light' or 'dark' --}}

<!-- Primary Color -->
<div>
    <label for="{{ $modePrefix }}_primary_color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Primary Color (Icons, Buttons)</label>
    <input type="color" id="{{ $modePrefix }}_primary_color" name="{{ $modePrefix }}_primary_color" 
           value="{{ $s[$modePrefix.'_primary_color'] }}"
           x-model="{{ $mode }}.primary_color"
           class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
</div>

<!-- Text Color -->
<div>
    <label for="{{ $modePrefix }}_text_color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Default Text Color</label>
    <input type="color" id="{{ $modePrefix }}_text_color" name="{{ $modePrefix }}_text_color"
           value="{{ $s[$modePrefix.'_text_color'] }}"
           x-model="{{ $mode }}.text_color"
           class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
</div>

<!-- Background Type -->
<div class="pt-2">
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Main Background Type</label>
    <div class="flex flex-wrap items-center gap-4 sm:gap-6 mt-2">
        <label class="flex items-center cursor-pointer">
            <input type="radio" name="{{ $modePrefix }}_bg_type" value="default" class="form-radio text-blue-600" 
                   x-model="{{ $mode }}.bg_type"
                   {{ $s[$modePrefix.'_bg_type'] == 'default' ? 'checked' : '' }}>
            <span class="ml-2 dark:text-slate-300">Default</span>
        </label>
        <label class="flex items-center cursor-pointer">
            <input type="radio" name="{{ $modePrefix }}_bg_type" value="color" class="form-radio text-blue-600"
                   x-model="{{ $mode }}.bg_type"
                   {{ $s[$modePrefix.'_bg_type'] == 'color' ? 'checked' : '' }}>
            <span class="ml-2 dark:text-slate-300">Color</span>
        </label>
        <label class="flex items-center cursor-pointer">
            <input type="radio" name="{{ $modePrefix }}_bg_type" value="image" class="form-radio text-blue-600"
                   x-model="{{ $mode }}.bg_type"
                   {{ $s[$modePrefix.'_bg_type'] == 'image' ? 'checked' : '' }}>
            <span class="ml-2 dark:text-slate-300">Image</span>
        </label>
    </div>
</div>

<!-- Background Color Picker -->
<div x-show="{{ $mode }}.bg_type === 'color'" class="pt-2" x-transition>
    <label for="{{ $modePrefix }}_bg_color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Choose Background Color</label>
    <input type="color" id="{{ $modePrefix }}_bg_color" name="{{ $modePrefix }}_bg_color" 
           value="{{ $s[$modePrefix.'_bg_color'] }}"
           x-model="{{ $mode }}.bg_color"
           class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
</div>

<!-- Background Image Uploader -->
<div x-show="{{ $mode }}.bg_type === 'image'" class="pt-2" x-transition>
    <label for="{{ $modePrefix }}_bg_image" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Upload Image</label>
    <input type="file" id="{{ $modePrefix }}_bg_image" name="{{ $modePrefix }}_bg_image" accept="image/*"
           @change.prevent="{{ $mode }}.image_preview = URL.createObjectURL($event.target.files[0])"
           class="mt-1 block w-full text-sm text-slate-500
                  file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold
                  file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                  dark:file:bg-slate-700 dark:file:text-slate-300 dark:hover:file:bg-slate-600"/>
    <img :src="{{ $mode }}.image_preview" alt="Image Preview" 
         class="mt-4 rounded-lg max-h-48"
         :class="{{ $mode }}.image_preview ? '' : 'hidden'">
</div>

{{-- ✅ START: ផ្នែកថ្មីសម្រាប់ Card Background --}}
<hr class="my-6 border-slate-200 dark:border-slate-700">

<!-- Card Background Type -->
<div class="pt-2">
    <h4 class="text-md font-medium text-slate-800 dark:text-slate-200">Card Background</h4>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">Controls the background of elements like Profile Card, Info Boxes, etc.</p>
    <div class="flex flex-wrap items-center gap-4 sm:gap-6 mt-2">
        <label class="flex items-center cursor-pointer">
            <input type="radio" name="{{ $modePrefix }}_card_type" value="default" class="form-radio text-blue-600" 
                   x-model="{{ $mode }}.card_type"
                   {{ $s[$modePrefix.'_card_type'] == 'default' ? 'checked' : '' }}>
            <span class="ml-2 dark:text-slate-300">Default (like bg-white/80)</span>
        </label>
        <label class="flex items-center cursor-pointer">
            <input type="radio" name="{{ $modePrefix }}_card_type" value="solid" class="form-radio text-blue-600"
                   x-model="{{ $mode }}.card_type"
                   {{ $s[$modePrefix.'_card_type'] == 'solid' ? 'checked' : '' }}>
            <span class="ml-2 dark:text-slate-300">Solid Color</span>
        </label>
        <label class="flex items-center cursor-pointer">
            <input type="radio" name="{{ $modePrefix }}_card_type" value="gradient" class="form-radio text-blue-600"
                   x-model="{{ $mode }}.card_type"
                   {{ $s[$modePrefix.'_card_type'] == 'gradient' ? 'checked' : '' }}>
            <span class="ml-2 dark:text-slate-300">Gradient</span>
        </label>
    </div>
</div>

<!-- Card Solid Color Picker -->
<div x-show="{{ $mode }}.card_type === 'solid'" class="pt-2 space-y-4" x-transition>
    <div>
        <label for="{{ $modePrefix }}_card_color1" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Card Color</label>
        <input type="color" id="{{ $modePrefix }}_card_color1" name="{{ $modePrefix }}_card_color1" 
               value="{{ $s[$modePrefix.'_card_color1'] }}"
               x-model="{{ $mode }}.card_color1"
               class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
    </div>
    <div>
        <label for="{{ $modePrefix }}_card_opacity" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            Card Opacity: <span x-text="{{ $mode }}.card_opacity"></span>%
        </label>
        <input type="range" id="{{ $modePrefix }}_card_opacity" name="{{ $modePrefix }}_card_opacity" 
               min="0" max="100" step="10"
               value="{{ $s[$modePrefix.'_card_opacity'] }}"
               x-model="{{ $mode }}.card_opacity"
               class="mt-1 block w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer">
    </div>
</div>

<!-- Card Gradient Color Picker -->
<div x-show="{{ $mode }}.card_type === 'gradient'" class="pt-2 space-y-4" x-transition>
    <div>
        <label for="{{ $modePrefix }}_card_color1_grad" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gradient Color 1 (Start)</label>
        <input type="color" id="{{ $modePrefix }}_card_color1_grad" name="{{ $modePrefix }}_card_color1" 
               value="{{ $s[$modePrefix.'_card_color1'] }}"
               x-model="{{ $mode }}.card_color1"
               class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
    </div>
     <div>
        <label for="{{ $modePrefix }}_card_color2_grad" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gradient Color 2 (End)</label>
        <input type="color" id="{{ $modePrefix }}_card_color2_grad" name="{{ $modePrefix }}_card_color2" 
               value="{{ $s[$modePrefix.'_card_color2'] }}"
               x-model="{{ $mode }}.card_color2"
               class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
    </div>
    <div>
         <label for="{{ $modePrefix }}_card_gradient_dir" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gradient Direction</label>
        <select id="{{ $modePrefix }}_card_gradient_dir" name="{{ $modePrefix }}_card_gradient_dir" 
                x-model="{{ $mode }}.card_gradient_dir"
                class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white dark:bg-slate-800 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="to right" {{ $s[$modePrefix.'_card_gradient_dir'] == 'to right' ? 'selected' : '' }}>Left to Right</option>
            <option value="to bottom" {{ $s[$modePrefix.'_card_gradient_dir'] == 'to bottom' ? 'selected' : '' }}>Top to Bottom</option>
            <option value="to top right" {{ $s[$modePrefix.'_card_gradient_dir'] == 'to top right' ? 'selected' : '' }}>Bottom-Left to Top-Right</option>
            <option value="to bottom right" {{ $s[$modePrefix.'_card_gradient_dir'] == 'to bottom right' ? 'selected' : '' }}>Top-Left to Bottom-Right</option>
        </select>
    </div>
</div>
{{-- ✅ END: ផ្នែកថ្មីសម្រាប់ Card Background --}}
