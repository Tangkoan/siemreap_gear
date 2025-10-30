@extends('admin.admin_dashboard')
@section('admin')

{{-- jQuery is required for the existing logic --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-4 sm:p-6">
    <div class="container mx-auto py-8 px-4 md:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8">
            <h1 class="text-3xl lg:text-4xl font-bold text-defalut flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 ">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                </svg>
                {{ __('messages.orders_report') }}
            </h1>
        </div>

        {{-- Pill-style Tabs --}}
        <div class="mb-6">
            <div class="inline-block card-dynamic-bg  p-1.5 rounded-xl shadow-sm">
                <ul class="flex items-center space-x-2" id="reportTab" role="tablist">
                    <li role="presentation">
                        <button class="tab-button text-sm px-6 py-2.5 rounded-lg" type="button" role="tab" data-tab-target="#day-tab-content">{{ __('messages.by_day') }}</button>
                    </li>
                    <li role="presentation">
                        <button class="tab-button text-sm px-6 py-2.5 rounded-lg" type="button" role="tab" data-tab-target="#month-tab-content">{{ __('messages.by_month') }}</button>
                    </li>
                    <li role="presentation">
                        <button class="tab-button text-sm px-6 py-2.5 rounded-lg" type="button" role="tab" data-tab-target="#year-tab-content">{{ __('messages.by_year') }}</button>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Tab Content Area --}}
        <div id="reportTabContent">
            <div class="tab-pane" id="day-tab-content" role="tabpanel">
                @include('admin.report.sale.partials._order_by_day')
            </div>
            <div class="tab-pane hidden" id="month-tab-content" role="tabpanel">
                @include('admin.report.sale.partials._order_by_month')
            </div>
            <div class="tab-pane hidden" id="year-tab-content" role="tabpanel">
                @include('admin.report.sale.partials._order_by_year')
            </div>
        </div>
    </div>
</div>

{{-- Include the redesigned modal --}}
@include('admin.report.sale.partials._order_details_modal')

{{-- All JavaScript logic goes here --}}
@include('admin.report.sale.partials._report_scripts')

@endsection


