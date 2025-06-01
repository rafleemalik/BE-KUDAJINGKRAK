<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    @vite('resources/js/app.js')
    @inertiaHead
    @routes
    <link rel="icon" href="{{ asset('images/kudajingkrak.png') }}" type="image/png" sizes="any">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jingkrak Autosport</title>
</head>

<body>
    @inertia
</body>

</html>
