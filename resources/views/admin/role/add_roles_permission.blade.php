@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>




    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>


                    <div class="px-2">
                        <a href="{{ route('all.roles') }}">
                            Add Roles Permission
                        </a>
                    </div>
                </h2>

                <div>

                    <form id="myForm" method="post" action="{{ route('roles.store') }}">
                        @csrf


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="form-group">
                                <label for="group_name" class="block text-gray-400 text-sm font-medium mb-1">
                                    Group Name
                                    {{-- <span class="text-red-500">*</span> --}}
                                </label>
                                <select name="group_name"
                                    class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    id="example-select" required>
                                    <option value="" selected disabled>Select Roles </option>
                                    @foreach($roles as $role) 
                                        <option value="{{ $role->id }}"> {{ $role->name }}</option>
                                    @endforeach

                                </select>



                            </div>

                            <div class="form-group">

                                <input type="hidden">



                            </div>

                            <div class="form-group flex items-center space-x-3 mb-4">

                                <div class="inline-flex items-center">
                                    <label class="flex items-center cursor-pointer relative">
                                        <input type="checkbox" checked
                                            class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-red-600 checked:border-red-600"
                                            id="check2" />
                                        <span
                                            class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"
                                                stroke="currentColor" stroke-width="1">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </label>                            
                                </div>
                                <label for="group_name" class="block text-gray-400 text-sm font-medium mb-1">
                                    All Permission
                                    {{-- <span class="text-red-500">*</span> --}}
                                </label>



                            </div>

                            <div class="form-group flex items-center space-x-3 mb-4">

                                <div class="inline-flex items-center">
                                    <label class="flex items-center cursor-pointer relative">
                                        <input type="checkbox" checked
                                            class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-red-600 checked:border-red-600"
                                            id="check2" />
                                        <span
                                            class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"
                                                stroke="currentColor" stroke-width="1">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </label>
                                </div>
                                <label for="group_name" class="block text-gray-400 text-sm font-medium mb-1">
                                    All Permission
                                    {{-- <span class="text-red-500">*</span> --}}
                                </label>



                            </div>
                            







                            {{-- Column 2 --}}
                            <div class="space-y-4">




                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit"
                                class="button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




@endsection