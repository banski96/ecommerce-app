<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>

    {{-- Load app.css via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Optional Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    
    <style>
        body {
            background-color: #f5f5f5;
        }
        
    </style>
</head>
<body>
<!-- ✅ Navbar -->
<x-customer-navBar /> 

<!-- ✅ Main Content -->
<div class="container my-4">
    @yield('content')
</div>

<!-- ✅ Footer -->
<footer class="bg-dark text-white text-center p-3 mt-5">
    <small>© 2026 MyShop</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>