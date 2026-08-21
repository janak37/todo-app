<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; }
        .task { padding: 10px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; }
        .completed { text-decoration: line-through; color: gray; }
        .success { color: green; }
    </style>
</head>
<body>
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    @yield('content')
</body>
</html>