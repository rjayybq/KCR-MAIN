@extends('layouts.app')

@section('content')
    <div class="text-center my-5">
        <h1 class="fw-bold text-primary">Cashier Dashboard</h1>
        <p>Welcome, {{ auth()->user()->name }} (Cashier)</p>
    </div>
@endsection
