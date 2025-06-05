@extends('admin/admin_dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="container mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform ">
    <div class="flex justify-between">
    <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
    </svg>
    <div class="px-2">Product</div>
    </h2>
    <div>

    <button type="button" class=" button-imaport  py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent    focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
    Import
    </button>

    <button type="button" class="button-export py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent  focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
    Export
    </button>

    <button type="button" class="button-add py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent   focus:outline-hidden  disabled:opacity-50 disabled:pointer-events-none">
    Add Product
    </button>
    </div>
    </div>



    <div class="overflow-x-auto">
    <div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
    <div>

    </div>
    <div class="ml-3">
    <div class="w-full max-w-sm min-w-[200px] relative">
    <div class="relative">
      <input
      class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-200 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
      placeholder="Search for invoice..."
      />
      <button
      class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
      type="button"
      >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
      <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
      </svg>
      </button>
    </div>
    </div>
    </div>
    </div>

    <div class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
    <table class="w-full text-left table-auto min-w-max">
    <thead>
    <tr>
    <th class="p-4 border-b border-slate-200 bg-slate-50">
      <p class="text-sm font-normal leading-none text-slate-500">
      Invoice Number
      </p>
    </th>
    <th class="p-4 border-b border-slate-200 bg-slate-50">
      <p class="text-sm font-normal leading-none text-slate-500">
      Customer
      </p>
    </th>
    <th class="p-4 border-b border-slate-200 bg-slate-50">
      <p class="text-sm font-normal leading-none text-slate-500">
      Amount
      </p>
    </th>
    <th class="p-4 border-b border-slate-200 bg-slate-50">
      <p class="text-sm font-normal leading-none text-slate-500">
      Issued
      </p>
    </th>
    <th class="p-4 border-b border-slate-200 bg-slate-50">
      <p class="text-sm font-normal leading-none text-slate-500">
      Due Date
      </p>
    </th>

    <th class="p-4 border-b border-slate-200 bg-slate-50">
      <p class="text-sm font-normal leading-none text-slate-500">
      Action
      </p>
    </th>
    </tr>
    </thead>
    <tbody>
    <tr class="hover:bg-slate-50 border-b border-slate-200">
    <td class="p-4 py-5">
      <p class="block font-semibold text-sm text-slate-800">PROJ1001</p>
    </td>
    <td class="p-4 py-5">
      <p class="text-sm text-slate-500">John Doe</p>
    </td>
    <td class="p-4 py-5">
      <p class="text-sm text-slate-500">$1,200.00</p>
    </td>
    <td class="p-4 py-5">
      <p class="text-sm text-slate-500">2024-08-01</p>
    </td>
    <td class="p-4 py-5">
      <p class="text-sm text-slate-500">2024-08-15</p>
    </td>

    <td class="px-4 py-4 text-sm whitespace-nowrap">
      <div class="flex items-center gap-x-6">
      <button class="text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
      </svg>
      </button>

      <button class="text-gray-500 transition-colors duration-200 dark:hover:text-gray-800 dark:text-gray-300 hover:text-gray-800 focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M3 5v14m3-14v14m4-14v14m4-14v14m3-14v14m3-14v14" />
      </svg>
      </button>



      <button class="text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
      </svg>
      </button>


      </div>
    </td>


    </tr>






    </tbody>
    </table>

    <div class="flex justify-between items-center px-4 py-3">
    <div class="text-sm text-slate-500">
    Showing <b>1-5</b> of 45
    </div>
    <div class="flex space-x-1">
    <button class="px-3 py-1 min-w-9 min-h-9 text-sm font-normal text-slate-500 bg-white border border-slate-200 rounded hover:bg-slate-50 hover:border-slate-400 transition duration-200 ease">
    Prev
    </button>
    <button class="px-3 py-1 min-w-9 min-h-9 text-sm font-normal text-white bg-slate-800 border border-slate-800 rounded hover:bg-slate-600 hover:border-slate-600 transition duration-200 ease">
    1
    </button>
    <button class="px-3 py-1 min-w-9 min-h-9 text-sm font-normal text-slate-500 bg-white border border-slate-200 rounded hover:bg-slate-50 hover:border-slate-400 transition duration-200 ease">
    2
    </button>
    <button class="px-3 py-1 min-w-9 min-h-9 text-sm font-normal text-slate-500 bg-white border border-slate-200 rounded hover:bg-slate-50 hover:border-slate-400 transition duration-200 ease">
    3
    </button>
    <button class="px-3 py-1 min-w-9 min-h-9 text-sm font-normal text-slate-500 bg-white border border-slate-200 rounded hover:bg-slate-50 hover:border-slate-400 transition duration-200 ease">
    Next
    </button>
    </div>
    </div>
    </div>

    </div>



    </div>
    </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
    $('.toggle-password').on('click', function() {
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