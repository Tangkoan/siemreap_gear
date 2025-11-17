@forelse($employees as $key => $item)
    <tr class="text-defalut">
        <td class="p-4 border-b border-slate-200">
            <p class="font-normal">{{ $employees->firstItem() + $key }}</p>
        </td>
        <td class="p-4 border-b border-slate-200">
            <p class="font-bold">{{ $item->name }}</p>
        </td>
        <td class="p-4 border-b border-slate-200">
            <p class="font-normal">{{ $item->phone ?? '...' }}</p>
        </td>
        <td class="p-4 border-b border-slate-200">
            <p class="font-normal">{{ $item->position }}</p>
        </td>
        <td class="p-4 border-b border-slate-200">
            <p class="font-bold text-green-600">${{ number_format($item->base_salary, 2) }}</p>
        </td>
        <td class="p-4 border-b border-slate-200">
             @if($item->status == 'active')
                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-800 dark:text-green-200">
                    Active
                </span>
            @else
                <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-800 dark:text-red-200">
                    Inactive
                </span>
            @endif
        </td>
        <td class="p-4 border-b border-slate-200">
            <div class="flex gap-2">
                @can('employee.edit')
                    <button type="button" 
                            class="text-blue-600 hover:text-blue-900 edit-btn"
                            data-id="{{ $item->id }}"
                            title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </button>
                @endcan
                @can('employee.delete')
                    <button type="button" 
                            class="text-red-600 hover:text-red-900 delete-btn"
                            data-id="{{ $item->id }}"
                            title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.576 0c-.229.03-.455.068-.677.108M5.28 10.706c.07.135.138.27.208.405l.002.003h12.918l.002-.003c.07-.135.138-.27.208-.405M6.634 11.25H17.37m-10.736 0a48.108 48.108 0 0 0-3.478-.397m12.576 0c.229.03.455.068.677.108" />
                        </svg>
                    </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="p-4 text-center text-defalut border-b border-slate-200">
            {{ __('messages.no_data_found') }}
        </td>
    </tr>
@endforelse