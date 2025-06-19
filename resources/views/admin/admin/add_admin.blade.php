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
                                    d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>


                            <div class="px-2">
                                <a href="{{ route('all.admin') }}">
                                    Add User
                                </a>
                            </div>
                        </h2>

                        <div>

                            <form id="myForm" method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
                                @csrf


                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                                    {{-- Column 1 --}}
                                    <div class="space-y-4">
                                        {{-- Name --}}
                                        <div class="form-group">
                                            <label for="name" class="block text-gray-400 text-sm font-medium mb-1  ">
                                                Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="name" name="name"
                                                class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ">
                                        </div>

                                        {{-- Role --}}
                                        <div class="form-group">
                                            <label for="Category" class="block text-gray-400 text-sm font-medium mb-1">
                                                Role <span class="text-red-500">*</span>
                                            </label>
                                            <select name="role"
                                                class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                                id="example-select">
                                                <option selected disabled>Select Role </option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>











                                        {{-- Profile --}}
                                        <div class="mb-2">
                                            <label for="photo" class="block text-gray-400 text-sm font-medium mb-2">
                                                Profile <span class="text-red-500">*</span>
                                            </label>
                                            <input type="file" id="photo" name="photo" accept="image/*"
                                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-500 file:text-white hover:file:bg-gray-600 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500">

                                            <img id="photo_preview" src="#" alt="Photo Preview" class="mt-2 rounded-md max-h-40 hidden" />
                                        </div>

                                    </div>

                                    {{-- Column 2 --}}
                                    <div class="space-y-4">


                                        {{-- Password --}}
                                        <div class="form-group relative mb-4">
                                            <label for="current_password" class="block text-gray-400 text-sm font-medium mb-1">
                                                Password
                                            </label>
                                            <input type="password" id="current_password" name="password" 
                                                class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('password') is-invalid @enderror">

                                            {{-- Eye icon --}}
                                            <span class="absolute right-3 top-9 cursor-pointer toggle-password" data-target="current_password">
                                                <svg class="h-5 w-5 text-gray-500 hover:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </span>

                                            @error('password')
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>




                                        {{-- Email --}}

                                        <div class="form-group">
                                            <label for="email" class="block text-gray-400 text-sm font-medium mb-1">
                                                Email 
                                            </label>
                                            <input type="email" id="email" name="email"
                                                class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        </div>

                                        {{-- Phone --}}
                                        <div class="form-group">
                                            <label for="phone" class="block text-gray-400 text-sm font-medium mb-1  ">
                                                Phone <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="phone" name="phone"
                                                class="input-field-custom w-full py-2.5 px-4 border border-gray-700 rounded-md bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent ">
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

            <script>
            $(document).ready(function () {
                $('#photo').on('change', function (event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const preview = $('#photo_preview');
                        const imageUrl = URL.createObjectURL(file);
                        preview.attr('src', imageUrl).removeClass('hidden');
                        preview.on('load', function () {
                            URL.revokeObjectURL(imageUrl);
                        });
                    } else {
                        $('#photo_preview').addClass('hidden').attr('src', '#');
                    }
                });
            });
        </script>

            <script type="text/javascript">
                $(document).ready(function () {
                    // Image preview script (optional)
                    $('#image').on('change', function (event) {
                        const [file] = event.target.files;
                        if (file) {
                            const preview = $('#image_preview');
                            preview.attr('src', URL.createObjectURL(file));
                            preview.removeClass('hidden');
                            preview.on('load', function () {
                                URL.revokeObjectURL(preview.attr('src')); // free memory
                            })
                        } else {
                            $('#image_preview').addClass('hidden').attr('src', '#');
                        }
                    });
                });


                $(document).ready(function () {
                        $('#myForm').validate({
                            rules: {
                                name: {
                                    Add commentMore actions
                            required: true,
                                },
                                email: {
                                    required: true,
                                },
                                phone: {
                                    required: true,
                                },
                                photo: {
                                    required: true,
                                },
                                password: {
                                    required: true,
                                },
                                roles: {
                                    required: true,
                                },
                            },
                            messages: {
                                name: {
                                    required: 'Please Enter User Name',
                                },
                                email: {
                                    required: 'Please Enter User Email',
                                },
                                phone: {
                                    required: 'Please Enter User Phone',
                                },
                                password: {
                                    required: 'Please Enter User Password',
                                },
                                photo: {
                                    required: 'Please Select User Photo',
                                },
                                roles: {
                                    required: 'Please Select User Role',
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
                            },
                        });
                    });





                    //*


            </script>

            <script> // Password Action Show or hide
                $(document).ready(function () {
                    $('.toggle-password').on('click', function () {
                        const targetId = $(this).data('target');
                        const passwordField = $('#' + targetId);
                        const icon = $(this).find('svg');

                        // Toggle the type attribute
                        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                        passwordField.attr('type', type);

                        // Toggle the eye icon
                        if (type === 'password') {
                            icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'); // Eye open
                        } else {
                            icon.html('<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7A10.05 10.05 0 0112 5c.424 0 .84.037 1.246.109m 3.167 3.167a3 3 0 11-4.243 4.243m4.243-4.243a3 3 0 00-4.243 4.243M3 3l3.59 3.59m0 0a9.953 9.953 0 01.442-.442L21 21"></path>'); // Eye closed
                        }
                    });
                });
    </script>
@endsection