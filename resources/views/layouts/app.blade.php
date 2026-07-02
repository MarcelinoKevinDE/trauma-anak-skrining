<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Deteksi Dini Trauma Anak')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite('resources/css/minimal.css')
</head>
<body>
    <div class="app-shell">
        @yield('content')
    </div>
</body>
</html>