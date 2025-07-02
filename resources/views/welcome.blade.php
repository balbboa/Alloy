<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Teste Alloy - Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet">
        <!-- Webflow CSS only -->
        <link href="/webflow/css/normalize.css" rel="stylesheet" type="text/css">
        <link href="/webflow/css/webflow.css" rel="stylesheet" type="text/css">
        <link href="/webflow/css/alloy-tests.webflow.css" rel="stylesheet" type="text/css">
        <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="/images/webclip.png" rel="apple-touch-icon">
        <!-- Vite/Laravel app -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="body">
        <div id="app"></div>
    </body>
</html>
