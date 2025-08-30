@extends('layouts.app')

@section('content')
    <h1 class="text-[#A0C878] font-bold text-5xl ml-3 text-shadow-lg mb-10">Purchase History</h1>
    <div class=" mt-4 overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-shadow-lg ">
            <thead class="text-xs text-[#FAF6E9] uppercase bg-[#537D5D]">
                <tr>
                    <th scope="col" class="px-6 py-3 text-shadow-lg text-center">
                        Product Name
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Categories
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Kg/gram
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Stock
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Price
                    </th>
                    <th scope="col" class="px-6 py-4 text-shadow-lg text-center">
                        Date Purchased
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($purchaseHistories as $purchaseHistory)
                    <tr class="odd:bg-[#9EBC8A] even:bg-[#73946B] text-[#FAF6E9]">
                        <th scope="row"
                            class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $purchaseHistory->ProductName }}
                        </th>
                        <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $purchaseHistory->category->name ?? 'N/A' }}
                        </td>
                        <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $purchaseHistory->weight . " " . $purchaseHistory->unit }}
                        </td>
                        <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $purchaseHistory->stock }}
                        </td>
                        <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            ₱{{ number_format($purchaseHistory->price, 2) }}
                        </td>
                         <td class="px-7 py-4 font-medium text-shadow-lg text-lg w-48 break-words whitespace-normal text-center">
                            {{ $purchaseHistory->created_at->format('F j, Y') }}
                        </td>
                    </tr>
                @endforeach

            <tfoot class="bg-[#FAF6E9]">
                <tr>
                    <td colspan="10" class="pt-4">
                        <div class="w-full px-4 mb-4">
                            <div
                                class="">
                                {!! $purchaseHistories->links('vendor.pagination.custom') !!}
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>

            </tbody>
        </table>
    </div>
@endsection
