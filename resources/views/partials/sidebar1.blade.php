<aside class="bg-success text-white vh-100 shadow-lg" style="width:250px;">

    @auth
        <!-- Sidebar Header / User Info -->
        <div class="text-center py-4 px-3 bg-success bg-gradient shadow-sm">
            <!-- Profile Picture -->
            <a href="{{ route('profile') }}" class="d-inline-block position-relative mb-2">
                <img src="{{ auth()->user()->profile_pic 
                                ? asset('storage/' . auth()->user()->profile_pic) 
                                : asset('images/default-avatar.png') }}"
                     alt="Profile Picture"
                     class="rounded-circle border border-2 border-white shadow"
                     style="width:70px; height:70px; object-fit:cover;">
            </a>

            <!-- User Info -->
            <h6 class="fw-bold mb-0">{{ auth()->user()->name }}</h6>
            <small class="text-white-50">{{ auth()->user()->email }}</small>
            <div>
                <span class="badge bg-light text-success mt-2">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>
    @else
        <!-- If guest -->
        <div class="text-center py-4 px-3 bg-success bg-gradient shadow-sm">
            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center mx-auto mb-2"
                 style="width:70px; height:70px;">
                <i class="fa-solid fa-user text-secondary fs-2"></i>
            </div>
            <h6 class="fw-bold mb-0">Guest</h6>
            <small class="text-white-50">Please log in</small>
        </div>
    @endauth

    <!-- Sidebar Menu -->
    <ul class="nav flex-column mt-3">
        {{-- <li class="nav-item">
            <a href="{{ route('products.index') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('products.*') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-cart-shopping me-2"></i> Products
            </a>
        </li> --}}

        {{-- <li class="nav-item">
            <a href="{{ route('inventories.index') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('inventories.*') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-warehouse me-2"></i> Inventory
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ route('cashier.dashboard') }}" 
               class="nav-link text-white fw-semibold {{ request()->routeIs('admin.dashboard') ? 'active bg-success bg-opacity-50' : '' }}">
                <i class="fa-solid fa-house me-2"></i> Dashboard
            </a>
        </li>
        
        <li class="nav-item">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('purchaseHistory') }}" 
                class="nav-link text-white fw-semibold {{ request()->routeIs('purchaseHistory') ? 'active bg-success bg-opacity-50' : '' }}">
                    <i class="fa-solid fa-shop me-2"></i> Purchase History
                </a>
            @elseif(auth()->user()->role === 'cashier')
                <a href="{{ route('cashier.purchaseHistory') }}" 
                class="nav-link text-white fw-semibold {{ request()->routeIs('cashier.purchaseHistory') ? 'active bg-success bg-opacity-50' : '' }}">
                    <i class="fa-solid fa-shop me-2"></i> My Sales
                </a>
            @endif
        </li>

        @auth
            <li class="nav-item">
                <a href="{{ route('profile') }}" 
                   class="nav-link text-white fw-semibold {{ request()->routeIs('profile*') ? 'active bg-success bg-opacity-50' : '' }}">
                    <i class="fa-solid fa-user me-2"></i> Profile
                </a>
            </li>
            <li class="nav-item mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="nav-link text-white fw-semibold border-0 bg-transparent w-100 text-start">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</aside>
