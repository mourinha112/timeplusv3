<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Videochamada' }} - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>html,body{margin:0;height:100%}</style>
</head>
<body>
    {{ $slot }}
</body>
</html>