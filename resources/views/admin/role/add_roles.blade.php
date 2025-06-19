@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>




    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>


                    <div class="px-2">
                        <a href="{{ route('all.roles') }}">
                            Add Roles
                        </a>
                    </div>
                </h2>

                <div>

                    <form id="myForm" method="post" action="{{ route('roles.store') }}">
                        @csrf


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                            {{-- Column 1 --}}
                            <div class="space-y-4">
                                {{-- Permissions Name --}}
                                <div class="form-group">
                                    <label for="name" class="block text-gray-400 text-sm font-medium mb-1  ">
                                        Permission Name
                                        {{-- <span class="text-red-500">*</span> --}}
                                    </label>
                                    <input type="text" id="name" name="name" required class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                                @error('name') is-invalid @enderror">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
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

    <script type="text/javascript">

        $(document).ready(function () {
            $('#myForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    group_name: {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: 'Please Enter Permission Name',
                    },
                    group_name: {
                        required: 'Please Select Group Name',
                    },


                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }, Add commentMore actions
            });
        });

    </script>


@endsection