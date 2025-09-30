<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kabaleyan Cove Resort')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/KCR.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        body {
            background-color: #FFFDF6;
        }

        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            height: 100%;
            width: 250px;
            background-color: #73946B;
            color: white;
            transition: all 0.3s ease;
            z-index: 1030;
        }

        #sidebar.show {
            left: 0;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #A0C878;
            color: #FAF6E9;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            z-index: 1040;
        }

        .header img {
            height: 40px;
            margin-right: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.2rem;
            font-style: italic;
            font-weight: 600;
        }

        /* Main Content */
        #mainContent {
            margin-top: 70px; /* space for header */
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        #mainContent.shifted {
            margin-left: 250px;
        }
    </style>
</head>

@stack('scripts')
<body>
    {{-- Sidebar --}}
    <div id="sidebar">
        @include('partials.sidebar')
    </div>

    {{-- Header --}}
    <div class="header shadow">
        <i id="toggleBtn" class="fa-solid fa-bars me-3 fs-4 cursor-pointer"></i>
        <img src="{{ asset('assets/images/KCR.png') }}" alt="Logo">
        <h1>Kabaleyan Cove Resort</h1>
    </div>

    {{-- Main Content --}}
    <main id="mainContent">
        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('shifted');
        });
    </script>
</body>

</html>
