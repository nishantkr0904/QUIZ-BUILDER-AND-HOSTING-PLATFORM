<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication - Quiz Platform')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif
    
    @if(session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    <main class="w-full max-w-md">
        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
