<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Meta Tags</title>
</head>
<body>
    <h2>Enter a URL to Fetch Meta Tags</h2>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ url('/fetch-meta') }}" method="POST">
        @csrf
        <input type="url" name="url" placeholder="Enter website URL" required>
        <button type="submit">Fetch Meta</button>
    </form>
</body>
</html>
