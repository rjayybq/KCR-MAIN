<aside class="bg-success text-white vh-100 shadow-lg" style="width:250px;">
    <!-- Sidebar Header / User Info -->
    <div class="d-flex align-items-center py-4 px-3 bg-success bg-gradient shadow-sm">
        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-3" 
             style="width:60px; height:60px;">
            <i class="fa-solid fa-user fs-2 text-secondary"></i>
        </div>
        <div>
            <h1 class="h5 fw-bold mb-0 text-white">Counter 1</h1>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <ul class="nav flex-column mt-3">
        {{-- <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('dashboard') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-house me-2"></i> Dashboard
            </a>
        </li> --}}

        <li class="nav-item">
            <a href="{{ route('products.index') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('products') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-cart-shopping me-2"></i> Products
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('inventory') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('inventory') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-warehouse me-2"></i> Inventory
            </a>
        </li>

        {{-- <li class="nav-item">
            <a href="{{ route('accountList') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('accountList') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-circle-user me-2"></i> Account List
            </a>
        </li> --}}

        <li class="nav-item">
            <a href="{{ route('purchaseHistory') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('purchaseHistory') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-shop me-2"></i> Purchase History
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('profile') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('profile') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-user me-2"></i> Profile
            </a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                    @csrf
                <button type="submit" 
                    class="nav-link text-white fw-semibold border-0 bg-transparent w-100 text-start {{ request()->routeIs('logout') ? 'active bg-success bg-opacity-50' : '' }}">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</aside>
