@extends('admin/admin_dashboard')
@section('admin')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Outer padding for a clean layout --}}
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        {{-- This is our card with support for both Light and Dark Mode --}}
        <div class="card-dynamic-bg overflow-hidden shadow-xl sm:rounded-lg">
            
            <div class="p-6 lg:p-8">
                
                {{-- Card Header --}}
                <div class="border-b border-primary pb-4">
                    <h2 class="text-2xl  text-defalut">
                        {{ __('messages.title') }}
                    </h2>
                    <p class="mt-1 text-sm text-defalut">
                        {{ __('messages.subtitle') }}
                    </p>
                </div>

                <div class="mt-6">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900/50 border-l-4 border-green-500 dark:border-green-600 text-green-700 dark:text-green-300 p-4 mb-6 rounded-md" role="alert">
                            <p class="">{{ __('messages.success_title') }}</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if (session('error'))
                        <div class="bg-red-100 dark:bg-red-900/50 border-l-4 border-red-500 dark:border-red-600 text-red-700 dark:text-red-300 p-4 mb-6 rounded-md" role="alert">
                            <p class="">{{ __('messages.error_title') }}</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('db.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Redesigned File Upload Input --}}
                        <div>
                            <label for="database_file" class="block mb-2 text-sm font-medium text-defalut">{{ __('messages.form_label') }}</label>
                            
                            <label for="database_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-primary border-dashed rounded-lg cursor-pointer card-dynamic-bg transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">{{ __('messages.upload_click') }}</span> {{ __('messages.upload_drag') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.upload_file_type') }}</p>
                                </div>
                                <input id="database_file" name="database_file" type="file" class="hidden" required accept=".sql, .txt">
                            </label>
                            
                            <div id="file-name-display" class="mt-2 text-sm text-gray-600 dark:text-gray-400"></div>

                            @error('database_file')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Warning Message --}}
                        <div class="mt-6 card-dynamic-bg border-l-4 border-yellow-500/80 dark:border-yellow-600 text-yellow-800 dark:text-yellow-300 p-4 rounded-md" role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="">{{ __('messages.warning_title') }}</p>
                                    <p class="text-sm">{{ __('messages.warning_message') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                {{ __('messages.submit_button') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Add JavaScript to display the selected file name --}}
<script>
    const fileInput = document.getElementById('database_file');
    const fileNameDisplay = document.getElementById('file-name-display');
    // Pass the translation from PHP to JavaScript
    const selectedFileLabel = "{{ __('messages.selected_file_label') }}";

    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            fileNameDisplay.textContent = `${selectedFileLabel} ${e.target.files[0].name}`;
        } else {
            fileNameDisplay.textContent = '';
        }
    });
</script>

@endsection