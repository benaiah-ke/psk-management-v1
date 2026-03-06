<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased flex items-center justify-center px-4">
    <div class="text-center">
        <div class="flex justify-center mb-8">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-700 text-lg font-bold text-white">PSK</div>
        </div>
        <p class="text-7xl font-bold text-gray-200">404</p>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Page Not Found</h1>
        <p class="mt-2 text-gray-600">The page you're looking for doesn't exist or has been moved.</p>
        <div class="mt-8 flex flex-col items-center gap-3">
            <a href="/" class="inline-block rounded-lg bg-primary-700 px-6 py-3 text-white hover:bg-primary-800 transition-colors">Go Home</a>
            <a href="#" onclick="history.back(); return false;" class="text-gray-500 hover:text-gray-700 transition-colors">Go Back</a>
        </div>
    </div>
</body>
</html>
