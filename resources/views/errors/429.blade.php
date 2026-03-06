<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Too Many Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="text-center">
        <div class="flex justify-center mb-8">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-700 text-lg font-bold text-white">PSK</div>
        </div>
        <p class="text-7xl font-bold text-gray-200">429</p>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Too Many Requests</h1>
        <p class="mt-2 text-gray-600">You've made too many requests. Please wait a moment and try again.</p>
        <div class="mt-8 flex flex-col items-center gap-3">
            <a href="/" class="inline-block rounded-lg bg-indigo-700 px-6 py-3 text-white hover:bg-indigo-800 transition-colors">Go Home</a>
            <a href="#" onclick="history.back(); return false;" class="text-gray-500 hover:text-gray-700 transition-colors">Go Back</a>
        </div>
    </div>
</body>
</html>
