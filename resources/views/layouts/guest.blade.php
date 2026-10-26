<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPK Driver Blitz') }} · PT Blitz Electric Mobility</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

        <style>
            :root {
                --blitz-cyan: #00ADEF;
                --blitz-cyan-hover: #0098d3;
                --blitz-navy: #0B1B2B;
                --blitz-navy-light: #152E48;
            }
            body {
                font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            }
        </style>

        <!-- Scripts & Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased min-h-screen text-slate-800 bg-[#0B1B2B] flex flex-col justify-between">
        {{ $slot }}
    </body>
</html>
