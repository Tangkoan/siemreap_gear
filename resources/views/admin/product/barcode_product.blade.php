@extends('admin/admin_dashboard')
@section('admin')
        <div class="container mx-auto p-6">
            <div class="grid grid-cols-1">

                <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform">
                    <h2 class="text-xl  text-default mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                        <div class="px-2">
                            <a href="{{ route('all.product') }}">
                                 {{ __('messages.barcode') }} 
                            </a>
                        </div>
                    </h2>

                    <!-- Move this out of the <h2> -->
                    <div class="overflow-x-auto">
                        <div class="text-xl  text-default mb-6 flex items-center">

                            <label for="firstname" class="form-label dark:text-white text-black"> {{ __('messages.product_code') }} </label>
                            <div class="px-2">
                                <h3 class="font-medium">{{ $product->product_code }}</h3>
                            </div>

                        </div>
                    </div>

                    @php
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                    @endphp

                    <div class="overflow-x-auto">
                        <div class="text-xl  text-default mb-6 flex items-center">

                            
                            <div class="px-2">
                                <h3> {!! $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128)  !!} </h3>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
@endsection
