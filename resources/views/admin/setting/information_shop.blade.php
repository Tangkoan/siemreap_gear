@extends('admin.admin_dashboard')
@section('admin')

{{-- Use jQuery for image preview --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container mx-auto p-4 sm:p-6">
        
        <div class="bg-white/80 dark:bg-gray-900/80 rounded-lg shadow-md p-6">
            
            <div class="flex justify-between">
                                <h2 class="text-xl  text-default mb-0 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                    </svg>

                                    <div class="px-2">{{ __('messages.information_shop') }}</div>
                                </h2>
                                
                            </div>

            
            <form method="post" action="{{ route('admin.info.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Name (KH) --}}
                <div>
                    <label for="name_kh" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.shop_name_kh') }}</label>
                    <input type="text" id="name_kh" name="name_kh" value="{{ $info->name_kh }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white/80 dark:bg-gray-900/80 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Name (EN) --}}
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.shop_name_en') }}</label>
                    <input type="text" id="name_en" name="name_en" value="{{ $info->name_en }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white/80 dark:bg-gray-900/80 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.address') }}</label>
                    <input type="text" id="address" name="address" value="{{ $info->address }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white/80 dark:bg-gray-900/80 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.phone') }}</label>
                    <input type="text" id="phone" name="phone" value="{{ $info->phone }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white/80 dark:bg-gray-900/80 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Note --}}
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.notes') }}</label>
                    <textarea id="note" name="note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white/80 dark:bg-gray-900/80 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">{{ $info->note }}</textarea>
                </div>

                {{-- Terms and Condition --}}
                <div>
                        <label for="terms_and_condition" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.terms_and_condition') }}</label>
                        <textarea id="terms_and_condition" name="terms_and_condition" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white/80 dark:bg-gray-900/80 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">{{ $info->terms_and_condition }}</textarea>
                    </div>

                {{-- Shop Logo Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.logo') }}</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <img class="h-24 w-24 rounded-lg object-cover border-2 border-gray-300" id="showLogo"
                             src="{{ (!empty($info->logo)) ? url('upload/shop_info/' . $info->logo) : url('upload/no_image.jpg') }}"
                             alt="Current Shop Logo">
                        
                        <div class="flex-grow">
                            <input type="file" name="logo" id="logo_input" class="hidden">
                            <label for="logo_input" class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white  py-2 px-4 rounded-md shadow-sm">
                                {{ __('messages.choose') }}
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-lg">
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript to show image preview before uploading --}}
<script type="text/javascript">
    $(document).ready(function(){
        $('#logo_input').change(function(e){
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e_reader){
                    $('#showLogo').attr('src', e_reader.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
</script>

@endsection