@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>





    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1">

            <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                <div class="flex justify-between">
                    <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <div class="px-2">
                            <a href="{{ route('all.product') }}">
                                Import Product
                            </a>
                            </div>
                    </h2>
                    <div>



                        

                        @can('product.export')
                            <!-- បើមានសិទ្ធ -->
                            <button type="button"
                                class="button-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent   focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
                                <a href="{{ route('export') }}">Download Xisx</a>
                            </button>
                        @else
                            <!-- បើអត់មានសិទ្ធ -->
                            <button
                                class="button-add  py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent    focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none "
                                disabled title="You don't have permission to access Export">
                                Export
                            </button>
                        @endcan

                    </div>
                </div>


                <div>

                    <form id="myForm" method="post" action="{{ route('import') }}" enctype="multipart/form-data">
                        @csrf


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">

                                {{-- Image --}}
                                <div class="mb-2">
                                    <label for="file" class="block text-gray-400 text-sm font-medium mb-2">
                                        Xisx file Import <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="import_file" required
                                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-500 file:text-white hover:file:bg-gray-600 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    {{-- Image preview script can be added here if needed --}}
                                    <img id="image_preview" src="#" alt="Image Preview"
                                        class="mt-2 rounded-md max-h-40 hidden" />
                                </div>

                        </div>














                        <div class="flex justify-end mt-6">
                            <button type="submit"
                                class="button-blue font-bold py-3 px-6 rounded-md transition-colors duration-200 shadow-lg">
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection