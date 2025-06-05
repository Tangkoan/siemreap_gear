@extends('admin/admin_dashboard')
@section('admin')

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1"> 

            <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linejoin="round"
                            d="M6.75 3.744h-.753v8.25h7.125a4.125 4.125 0 0 0 0-8.25H6.75Zm0 0v.38m0 16.122h6.747a4.5 4.5 0 0 0 0-9.001h-7.5v9h.753Zm0 0v-.37m0-15.751h6a3.75 3.75 0 1 1 0 7.5h-6m0-7.5v7.5m0 0v8.25m0-8.25h6.375a4.125 4.125 0 0 1 0 8.25H6.75m.747-15.38h4.875a3.375 3.375 0 0 1 0 6.75H7.497v-6.75Zm0 7.5h5.25a3.75 3.75 0 0 1 0 7.5h-5.25v-7.5Z" />
                    </svg>

                    <div class="px-2">Add Brand</div>
                </h2>

                <div>
                    <form method="post" action="{{ route('brand.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">
                                {{-- Employee Name --}}
                                <div>
                                    <label for="brand_name" class="block text-gray-400 text-sm font-medium mb-1">
                                        Brand Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="brand_name" name="brand_name" required
                                        class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 
                                                focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent 
                                                @error('brand_name') is-invalid @enderror">

                                    <x-input-error :messages="$errors->get('brand_name')" class="mt-2" />
                                </div>



                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection