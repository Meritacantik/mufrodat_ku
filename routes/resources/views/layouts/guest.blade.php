<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MufrodatKu') }} - MTs YPPU Karimunting</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Baloo+2:wght@600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Poppins', 'Segoe UI', sans-serif !important; }
            .brand-font { font-family: 'Baloo 2', 'Poppins', sans-serif; }
        </style>
    </head>
    <body class="text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background:#FEFAF3">
            <div class="flex items-center gap-2">
                <div style="width:40px;height:40px;background:#5C9C7D;border-radius:10px;display:flex;align-items:center;justify-content:center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 6.5c-1.5-1-3.6-1.5-6-1.5v13c2.4 0 4.5.5 6 1.5"/>
                        <path d="M12 6.5c1.5-1 3.6-1.5 6-1.5v13c-2.4 0-4.5.5-6 1.5"/>
                        <path d="M12 6.5v13"/>
                    </svg>
                </div>
                <div class="brand-font" style="font-size:18px;font-weight:700;color:#111827">MufrodatKu</div>
            </div>
            <div class="text-xs text-gray-500 mt-1 mb-4">MTs YPPU Karimunting</div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-2xl" style="border:1px solid #e5e7eb">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
