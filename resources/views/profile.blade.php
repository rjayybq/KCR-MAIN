@extends('layouts.app')

@section('content')
    <h1 class="text-[#A0C878] font-bold text-5xl ml-3 text-shadow-lg mb-10">Account Profile</h1>


    <div class="bg-[#FAF6E9] shadow-lg/20 py-10">
        <div class="flex flex-1">
            {{-- Profile Icon --}}
            <div class="w-full flex justify-center py-12">
                <div class="relative w-52 h-52 overflow-hidden bg-[#9EBC8A] rounded-full items-center mx-4 shadow-lg">
                    <svg class="absolute w-52 h-52 text-[#537D5D] -left-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>

                {{-- Edit Profile Icon --}}
                <div class="flex items-end ml-[-3.5rem] mb-[-0.8rem]">
                    <button class="cursor-pointer p-2 z-30">
                        <svg class="w-8 h-8 text-[#73946B] hover:text-[#9EBC8A]" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                            <path
                                d="M17 0h-5.768a1 1 0 1 0 0 2h3.354L8.4 8.182A1.003 1.003 0 1 0 9.818 9.6L16 3.414v3.354a1 1 0 0 0 2 0V1a1 1 0 0 0-1-1Z" />
                            <path
                                d="m14.258 7.985-3.025 3.025A3 3 0 1 1 6.99 6.768l3.026-3.026A3.01 3.01 0 0 1 8.411 2H2.167A2.169 2.169 0 0 0 0 4.167v11.666A2.169 2.169 0 0 0 2.167 18h11.666A2.169 2.169 0 0 0 16 15.833V9.589a3.011 3.011 0 0 1-1.742-1.604Z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex text-center items-center justify-center w-full">
                <h1 class="text-[#A0C878] font-bold text-8xl ml-3 text-shadow-lg mb-10">{{ $user->name ?? 'N/A' }}</h1>
            </div>
        </div>
    </div>

    <div class="bg-[#FAF6E9] shadow-lg/20 mt-10">
        <table class="w-full text-sm text-left text-shadow-lg">
            <thead class="text-xs text-[#FAF6E9] uppercase bg-[#537D5D]">
                <tr>
                    <th scope="col" class="px-6 py-3 text-shadow-lg text-center">
                        Status Counter
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Counter Name
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Email
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr class="odd:bg-[#9EBC8A] even:bg-[#73946B] text-[#FAF6E9]">
                    <th scope="row"
                        class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                        Cashier
                    </th>
                    <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                        John Doe
                    </td>
                    <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                        Johndoe@gmail.com
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class=" w-full text-end mt-6">
        <button type="button"
            class="text-white bg-[#701d1d] hover:bg-[#BF3131] font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none text-shadow-lg shadow-lg">
            <i class="fa-solid fa-power-off"></i> LOG OUT
        </button>
    </div>
@endsection
