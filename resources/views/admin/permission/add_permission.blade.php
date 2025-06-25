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
                                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                        </svg>


                        <div class="px-2">
                            <a href="{{ route('all.permission') }}">
                                Add Permission
                            </a>
                        </div>
                    </h2>

                    <div>

                        <form  id="myForm" method="post" action="{{ route('permission.store') }}" >
                            @csrf


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                                {{-- Column 1 --}}
                                <div class="space-y-4">
                                    {{--  Permissions Name --}}
                                    <div class="form-group">
                                        <label for="name" class="block text-gray-400 text-sm font-medium mb-1  ">
                                           Permission Name 
                                           {{-- <span class="text-red-500">*</span> --}}
                                        </label>
                                        <input type="text" id="name" name="name" required
                                            class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                            @error('name') is-invalid @enderror">
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>
                                </div>


                                {{-- Column 2 --}}
                                <div class="space-y-4">



                                    {{-- Category --}}
                                    <div class="form-group">
                                            <label for="group_name" class="block text-gray-400 text-sm font-medium mb-1">
                                                Group Name 
                                                {{-- <span class="text-red-500">*</span> --}}
                                            </label>
                                            <select name="group_name"
                                                class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                                id="example-select" required>
                                                <option value="" selected disabled>Select Group Name </option>
                                                <option value="pos"> Pos</option>
                                                <option value="customer"> Customer</option>
                                                <option value="supplier"> Supplier</option>
                                                <option value="category"> Category </option>
                                                <option value="product"> Product </option>
                                                <option value="expense"> Expense </option>
                                                <option value="orders"> Orders</option>
                                                <option value="stock"> Stock </option>
                                                <option value="roles"> Roles</option>
                                                <option value="purchases"> Purchases</option>
                                                <option value="report"> Report</option>
                                                <option value="user"> User</option>
                                                <option value="permission"> Permission</option>
                                                <option value="backup"> Backup</option>
                                            </select>

                                        </div>
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