<div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
    <label for="primary-color-picker" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        ជ្រើសរើសពណ៌សំខាន់ (Primary Color)
    </label>
    <input type="color" 
           id="primary-color-picker" 
           name="primary-color-picker" 
           value="{{ Auth::user()->primary_color_hex ?? '#3b82f6' }}" 
           class="mt-1 p-1 w-full h-10 border-2 border-gray-300 rounded-lg cursor-pointer">

    <div class="mt-4 flex items-center justify-between">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            របៀបងងឹត (Dark Mode)
        </span>
        <button id="dark-mode-toggle" 
                class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors {{ (Auth::user()->theme_mode ?? 'light') == 'dark' ? 'bg-primary-500' : 'bg-gray-200' }}"
                aria-checked="{{ (Auth::user()->theme_mode ?? 'light') == 'dark' ? 'true' : 'false' }}">
            <span class="sr-only">Toggle Dark Mode</span>
            <span class="inline-block w-4 h-4 transform bg-white rounded-full transition ease-in-out duration-200 
                {{ (Auth::user()->theme_mode ?? 'light') == 'dark' ? 'translate-x-6' : 'translate-x-1' }}"></span>
        </button>
    </div>
</div>