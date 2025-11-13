<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>teste de View de Administrador</h2>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
            Logout
        </button>
    </form>

</body>

</html>
