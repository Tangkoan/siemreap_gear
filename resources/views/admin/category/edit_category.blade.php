@extends('admin/admin_dashboard')
@section('admin')

<div class="container mx-auto p-6">
    <div class="grid grid-cols-1"> 

        <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
            <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
                <div class="px-2">Edit Category</div>
            </h2>
            
            <div>
                <form method="post" action="{{ route('category.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $category->id }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                        
                        {{-- Column 1 --}}
                        <div class="space-y-4">
                            {{-- Category Name --}}
                            <div>
                                <label for="name" class="block text-gray-400 text-sm font-medium mb-1">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="category_name" name="category_name" required value="{{ $category->category_name }}"
                                    class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 
                                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent 
                                            @error('category_name') is-invalid @enderror">

                                <x-input-error :messages="$errors->get('category_name')" class="mt-2" />
                            </div>

                            
                            
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="color-primary hover:bg-red-600 text-white font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection