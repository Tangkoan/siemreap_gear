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
    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Background Type</label>
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
<div x-show="{{ $mode }}.bg_type === 'color'" class="pt-2" style="display: none;">
    <label for="{{ $modePrefix }}_bg_color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Choose Background Color</label>
    <input type="color" id="{{ $modePrefix }}_bg_color" name="{{ $modePrefix }}_bg_color" 
           value="{{ $s[$modePrefix.'_bg_color'] }}"
           x-model="{{ $mode }}.bg_color"
           class="mt-1 block w-full h-10 p-1 border border-slate-300 dark:border-slate-600 rounded-md cursor-pointer">
</div>

<!-- Background Image Uploader -->
<div x-show="{{ $mode }}.bg_type === 'image'" class="pt-2" style="display: none;">
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
