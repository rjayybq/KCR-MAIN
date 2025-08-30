@extends('layouts.app')

@section('content')
    <h1 class="text-[#A0C878] font-bold text-5xl ml-3 text-shadow-lg mb-10">Account List</h1>
    <div class=" mt-4 overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-shadow-lg">
            <thead class="text-xs text-[#FAF6E9] uppercase bg-[#537D5D]">
                <tr>
                    <th scope="col" class="px-6 py-3 text-shadow-lg text-center">
                        Account Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Account Name
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Email
                    </th>
                     <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Action
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr class="odd:bg-[#9EBC8A] even:bg-[#73946B] text-[#FAF6E9]">
                        <th scope="row"
                            class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $user->status }}
                        </th>
                        <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $user->name ?? 'N/A' }}
                        </td>
                            <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $user->email}}
                        </td>
                        <td class="px-6 py-4 w-36">
                            <button type="button"
                                class="text-white bg-[#701d1d] hover:bg-[#BF3131] font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none text-shadow-lg shadow-lg w-full">
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach

            <tfoot class="bg-[#FAF6E9]">
                <tr>
                    <td colspan="10" class="pt-4">
                        <div class="w-full px-4 mb-4">
                            <div
                                class="">
                                {!! $users->links('vendor.pagination.custom') !!}
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>

            </tbody>
        </table>
    </div>
@endsection
