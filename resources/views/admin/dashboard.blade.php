@extends('layouts.app')

@section('content')
    <h1 class="text-success fw-bold display-5 ms-3">Dashboard</h1>
    @auth
        <div class="text-center my-2">
        @if(auth()->user()->role === 'admin')
            <h4>Welcome {{ auth()->user()->name }}</h4>
        @elseif(auth()->user()->role === 'cashier')
            <h4>Welcome {{ auth()->user()->name }}</h4>
        @else
            <h4>Welcome User {{ auth()->user()->name }}</h4>
        @endif
    </div>
    @endauth






    <div class="row mt-4 g-4">
        {{-- TOTAL PRODUCT --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-lg border-0" style="background-color: #A0C878;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="text-light fw-bold fs-3 mb-1">2,304</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Total Product</h2>
                    </div>
                    <i class="fa-solid fa-cart-shopping text-light fs-1"></i>
                </div>
                <div class="card-footer bg-success d-flex justify-content-center">
                    <a href="{{ route('products') }}" class="text-white fw-semibold text-decoration-none d-flex align-items-center">
                        More Info
                        <i class="fa-solid fa-arrow-right ms-2 p-2 rounded-circle" style="background:#537D5D; font-size:12px;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- TOTAL INVENTORY --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-lg border-0" style="background-color: #D2D0A0;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="text-light fw-bold fs-3 mb-1">1,234</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Total Inventory</h2>
                    </div>
                    <i class="fa-solid fa-warehouse text-light fs-1"></i>
                </div>
                <div class="card-footer bg-success d-flex justify-content-center">
                    <a href="{{ route('inventory') }}" class="text-white fw-semibold text-decoration-none d-flex align-items-center">
                        More Info
                        <i class="fa-solid fa-arrow-right ms-2 p-2 rounded-circle" style="background:#537D5D; font-size:12px;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- TOTAL ACCOUNT --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-lg border-0" style="background-color: #9EBC8A;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="text-light fw-bold fs-3 mb-1">5,304</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Total Account</h2>
                    </div>
                    <i class="fa-solid fa-circle-user text-light fs-1"></i>
                </div>
                <div class="card-footer bg-success d-flex justify-content-center">
                    <a href="{{ route('accountList') }}" class="text-white fw-semibold text-decoration-none d-flex align-items-center">
                        More Info
                        <i class="fa-solid fa-arrow-right ms-2 p-2 rounded-circle" style="background:#537D5D; font-size:12px;"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- TOTAL PURCHASE HISTORY --}}
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-lg border-0" style="background-color: #DDEB9D;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="text-light fw-bold fs-3 mb-1">2,304</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Total Purchase</h2>
                    </div>
                    <i class="fa-solid fa-shop text-light fs-1"></i>
                </div>
                <div class="card-footer bg-success d-flex justify-content-center">
                    <a href="{{ route('purchaseHistory') }}" class="text-white fw-semibold text-decoration-none d-flex align-items-center">
                        More Info
                        <i class="fa-solid fa-arrow-right ms-2 p-2 rounded-circle" style="background:#537D5D; font-size:12px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
