@extends('admin/admin_dashboard')
@section('admin')

<div class="w-full p-4 pt-6 mx-auto md:p-6">
    <div class="container-fluid">

        {{-- =================================== --}}
        {{-- START: Page Title & Breadcrumb --}}
        {{-- =================================== --}}
        <div class="flex flex-col items-start justify-between mb-6 sm:flex-row sm:items-center">
            <div>
                <h4 class="text-2xl font-semibold text-default">{{ __('messages.close_shift') }}</h4>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.end_of_shift_reconciliation') }}
                </p>
            </div>
            <div class="mt-2 text-sm sm:mt-0">
                <ol class="flex items-center space-x-1 text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-primary">{{ __('messages.dashboard') }}</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </li>
                    <li class="font-medium text-default">{{ __('messages.close_shift') }}</li>
                </ol>
            </div>
        </div>
        {{-- =================================== --}}
        {{-- END: Page Title & Breadcrumb --}}
        {{-- =================================== --}}


        {{-- =================================== --}}
        {{-- START: Main Form Card --}}
        {{-- =================================== --}}
        <div class="flex justify-center">
            {{-- ខ្ញុំបានប្តូរ 'col-lg-8' ទៅ 'max-w-4xl' ព្រោះ Form នេះទូលាយជាង --}}
            <div class="w-full max-w-4xl">
                <div class="overflow-hidden shadow-lg card-dynamic-bg rounded-xl">
                    
                    <div class="p-6 md:p-8">

                        <div class="text-center">
                            <h4 class="mb-2 text-2xl font-semibold text-default">{{ __('messages.end_of_shift_reconciliation') }}</h4>
                            <p class="mb-6 text-gray-600 dark:text-gray-400">
                                {{ __('messages.verify_cash_prompt') }}
                            </p>
                        </div>
                        
                        <hr class="border-slate-200 dark:border-slate-700">
                        
                        {{-- ================================================= --}}
                        {{-- ផ្នែកទី១៖ របាយការណ៍សង្ខេបពីប្រព័ន្ធ (Read-Only) --}}
                        {{-- ================================================= --}}
                        <div class="mt-6">
                            <h5 class="text-xl font-semibold text-primary mb-4">{{ __('messages.system_calculation') }}</h5>
                            
                            <div class="space-y-3">
                                {{-- ខ្ញុំបានប្តូរ Bootstrap 'row' ទៅជា Tailwind 'flex' --}}
                                <div class="flex justify-between items-center py-3 border-b border-slate-200 dark:border-slate-700">
                                    <span class="font-medium text-default">{{ __('messages.starting_cash_label') }}</span>
                                    <span class="font-bold text-default">$ {{ number_format($shift->starting_cash, 2) }}</span>
                                </div>

                                <div class="flex justify-between items-center py-3 border-b border-slate-200 dark:border-slate-700">
                                    <span class="font-medium text-default">{{ __('messages.total_cash_sales_label') }}</span>
                                    <span class="font-bold text-green-500">+ $ {{ number_format($totalCash, 2) }}</span>
                                </div>

                                {{-- ខ្ញុំបានប្តូរ 'bg-light' ទៅជា Tailwind Class --}}
                                <div class="flex justify-between items-center p-4 mt-4 rounded-lg bg-slate-100 dark:bg-slate-700/50">
                                    <span class="text-lg font-semibold text-primary">{{ __('messages.expected_cash_label') }}</span>
                                    <span class="text-lg font-bold text-primary">$ {{ number_format($expectedCash, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- ផ្នែកបង្ហាញ Non-Cash (សម្រាប់ផ្ទៀងផ្ទាត់) --}}
                        <hr class="my-6 border-slate-200 dark:border-slate-700">
                        
                        <div class="mt-6">
                            <h5 class="text-lg font-semibold text-default mb-4">{{ __('messages.non_cash_totals_label') }}</h5>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.total_card_sales_label') }}</span>
                                    <span class="text-sm font-medium text-default">$ {{ number_format($totalCard, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.total_qr_sales_label') }}</span>
                                    <span class="text-sm font-medium text-default">$ {{ number_format($totalQR, 2) }}</span>
                                </div>
                            </div>
                        </div>


                        {{-- ================================================= --}}
                        {{-- ផ្នែកទី២៖ Form សម្រាប់អ្នកគិតលុយបញ្ចូល --}}
                        {{-- ================================================= --}}
                        <form method="POST" action="{{ route('shift.close') }}" class="mt-8">
                            @csrf
                            
                            {{-- ខ្ញុំបានប្តូរ 'border-danger' ទៅជា Tailwind Class --}}
                            <hr class="my-8 border-red-400 dark:border-red-600 border-dashed">
                            
                            <h5 class="text-xl font-semibold text-red-600 dark:text-red-400 mb-4">{{ __('messages.cashier_declaration_label') }}</h5>
                            
                            <div class="mb-6">
                                {{-- ខ្ញុំបានប្តូរ 'fs-5 fw-bold' ទៅជា Tailwind Class --}}
                                <label for="actual_cash" class="block mb-2 text-lg font-medium text-default">{{ __('messages.actual_cash_label') }}</label>
                                
                                {{-- ខ្ញុំបានប្តូរ 'form-control-lg' ទៅជា Tailwind Class --}}
                                <input class="block w-full px-4 py-3 text-lg border rounded-lg shadow-sm bg-inherit text-default card-dynamic-bg border-pimary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary @error('actual_cash') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       type="number" 
                                       name="actual_cash" 
                                       id="actual_cash" 
                                       value="{{ old('actual_cash') }}" 
                                       step="0.01" 
                                       required 
                                       autofocus 
                                       placeholder="{{ __('messages.actual_cash_placeholder') }}">
                                
                                @error('actual_cash')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ខ្ញុំបានប្តូរ 'alert alert-warning' ទៅជា Tailwind Class --}}
                            <div class="relative px-4 py-3 text-yellow-700 bg-yellow-100 border border-yellow-400 rounded-lg dark:bg-yellow-900/30 dark:border-yellow-600 dark:text-yellow-300" role="alert">
                                <strong class="font-bold">{{ __('messages.warning') }}:</strong>
                                <span class="block sm:inline">{{ __('messages.close_shift_warning') }}</span>
                            </div>

                            <div class="mt-6 pt-2">
                                {{-- ខ្ញុំបានប្តូរ 'btn-danger btn-lg' ទៅជា Tailwind Class --}}
                                <button class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition duration-150 ease-in-out bg-primary border border-transparent rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" type="submit">
                                    {{ __('messages.confirm_close_shift_btn') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{-- =================================== --}}
        {{-- END: Main Form Card --}}
        {{-- =================================== --}}

    </div> </div>
@endsection