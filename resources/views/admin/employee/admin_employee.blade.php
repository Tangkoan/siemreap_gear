@extends('admin/admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-full card-bg rounded-lg shadow-xl p-6 transition-all duration-300 transform ">
            <h2 class="text-xl font-semibold text-default mb-6 flex items-center">
                <svg class="h-6 w-6 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                Employee
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y-2 divide-gray-200">
                  <thead class="ltr:text-left rtl:text-right">
                    <tr class="*:font-medium *:text-gray-900">
                      <th class="px-3 py-2 whitespace-nowrap">Name</th>
                      <th class="px-3 py-2 whitespace-nowrap">DoB</th>
                      <th class="px-3 py-2 whitespace-nowrap">Role</th>
                      <th class="px-3 py-2 whitespace-nowrap">Salary</th>
                    </tr>
                  </thead>
              
                  <tbody class="divide-y divide-gray-200 *:even:bg-gray-50">
                    <tr class="*:text-gray-900 *:first:font-medium">
                      <td class="px-3 py-2 whitespace-nowrap">Nandor the Relentless</td>
                      <td class="px-3 py-2 whitespace-nowrap">04/06/1262</td>
                      <td class="px-3 py-2 whitespace-nowrap">Vampire Warrior</td>
                      <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>
              
                    <tr class="*:text-gray-900 *:first:font-medium">
                      <td class="px-3 py-2 whitespace-nowrap">Laszlo Cravensworth</td>
                      <td class="px-3 py-2 whitespace-nowrap">19/10/1678</td>
                      <td class="px-3 py-2 whitespace-nowrap">Vampire Gentleman</td>
                      <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>
              
                    <tr class="*:text-gray-900 *:first:font-medium">
                      <td class="px-3 py-2 whitespace-nowrap">Nadja</td>
                      <td class="px-3 py-2 whitespace-nowrap">15/03/1593</td>
                      <td class="px-3 py-2 whitespace-nowrap">Vampire Seductress</td>
                      <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>
              
                    <tr class="*:text-gray-900 *:first:font-medium">
                      <td class="px-3 py-2 whitespace-nowrap">Colin Robinson</td>
                      <td class="px-3 py-2 whitespace-nowrap">01/09/1971</td>
                      <td class="px-3 py-2 whitespace-nowrap">Energy Vampire</td>
                      <td class="px-3 py-2 whitespace-nowrap">$53,000</td>
                    </tr>
              
                    <tr class="*:text-gray-900 *:first:font-medium">
                      <td class="px-3 py-2 whitespace-nowrap">Guillermo de la Cruz</td>
                      <td class="px-3 py-2 whitespace-nowrap">18/11/1991</td>
                      <td class="px-3 py-2 whitespace-nowrap">Familiar/Vampire Hunter</td>
                      <td class="px-3 py-2 whitespace-nowrap">$0</td>
                    </tr>
                  </tbody>
                </table>
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