@extends('layouts.app')

@section('content')

    
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="text-success fw-bold display-5 ms-3">Dashboard</h1>

    <!-- Notification Bell -->
    <div class="dropdown me-4">
            <button class="btn position-relative" style="background:none; border:none;" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-bell fs-3 text-success"></i>
                <!-- Badge -->
                <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    0
                </span>
            </button>
           <ul id="notificationDropdownMenu" 
                class="dropdown-menu dropdown-menu-end shadow-lg p-2" 
                aria-labelledby="notificationDropdown" 
                style="width: 370px;"> 

                <li class="dropdown-header fw-bold d-flex justify-content-between align-items-center">
                    <span>Notifications</span>
                    <a href="{{ route('notifications.index') }}" class="small text-success">View All</a>
                </li>
                <li><hr class="dropdown-divider"></li>

                <!-- Notifications will be loaded here by JS -->
                <li><a class="dropdown-item text-muted">Loading...</a></li>
            </ul>
        </div>
    </div>

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
                        <h1 class="text-light fw-bold fs-3 mb-1">{{ number_format($totalProducts) }}</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Products</h2>
                    </div>
                    <i class="fa-solid fa-cart-shopping text-light fs-1"></i>
                </div>
                <div class="card-footer bg-success d-flex justify-content-center">
                    <a href="{{ route('products.index') }}" class="text-white fw-semibold text-decoration-none d-flex align-items-center">
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
                        
                        <h1 class="text-light fw-bold fs-3 mb-1">{{ number_format($totalIngredientStock) }}</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Inventories</h2>
                    </div>
                    <i class="fa-solid fa-warehouse text-light fs-1"></i>
                </div>
                <div class="card-footer bg-success d-flex justify-content-center">
                    <a href="{{ route('inventories.index') }}" class="text-white fw-semibold text-decoration-none d-flex align-items-center">
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
                        
                        <h1 class="text-light fw-bold fs-3 mb-1">{{ number_format($totalAccounts) }}</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Accounts</h2>
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
                        
                        <h1 class="text-light fw-bold fs-3 mb-1">{{ number_format($totalPurchases) }}</h1>
                        <h2 class="text-light fw-bold fs-4 mb-0">Purchases</h2>
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

<script>
    async function fetchDashboardStats() {
        let response = await fetch("{{ route('dashboard.stats') }}");
        let data = await response.json();

        document.getElementById('totalProducts').innerText = data.totalProducts.toLocaleString();
        document.getElementById('totalInventory').innerText = data.totalInventory.toLocaleString();
        document.getElementById('totalAccounts').innerText = data.totalAccounts.toLocaleString();
        document.getElementById('totalPurchases').innerText = data.totalPurchases.toLocaleString();
    }

    // Fetch immediately
    fetchDashboardStats();
    // Refresh every 5 seconds
    setInterval(fetchDashboardStats, 5000);
</script>

<script>
   async function fetchNotifications() {
    try {
        let response = await fetch("{{ route('notifications.unread') }}");
        let data = await response.json();

        // Update badge
        document.getElementById("notificationBadge").innerText = data.count;

        // Dropdown container
        let dropdown = document.getElementById("notificationDropdownMenu");

        dropdown.innerHTML = `
            <li class="dropdown-header fw-bold d-flex justify-content-between align-items-center">
                <span>Notifications</span>
                <a href="{{ route('notifications.index') }}" class="small text-success">View All</a>
            </li>
            <li><hr class="dropdown-divider"></li>
        `;

        if (data.notifications.length === 0) {
            dropdown.innerHTML += `<li><a class="dropdown-item text-muted">No new notifications</a></li>`;
        } else {
            data.notifications.forEach(notif => {
                // Shorten text (40 chars max)
                let shortMsg = notif.message.length > 40 
                    ? notif.message.substring(0, 40) + "..." 
                    : notif.message;

                dropdown.innerHTML += `
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="fa-solid fa-bell text-warning me-2"></i>
                            <span>${shortMsg}</span>
                        </a>
                    </li>`;
            });
        }
    } catch (error) {
        console.error("Error fetching notifications:", error);
    }
}

    // Mark notification as read
    async function markAsRead(id) {
        await fetch(`/notifications/read/${id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        });
        fetchNotifications(); // refresh after marking read
    }

    // Fetch immediately + every 5 seconds
    fetchNotifications();
    setInterval(fetchNotifications, 5000);
</script>