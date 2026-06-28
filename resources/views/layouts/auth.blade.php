<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login - PT WAGS')</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="auth-page">
    @yield('content')
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
