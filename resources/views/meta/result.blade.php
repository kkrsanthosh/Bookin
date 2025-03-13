<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Tag Results</title>
</head>
<body>
    <h2>Meta Tags for: {{ $url }}</h2>

    @if(empty($metaTags))
        <p>No meta tags found.</p>
    @else
        <ul>
            @foreach($metaTags as $name => $content)
                <li><strong>{{ $name }}:</strong> {{ $content }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ url('/fetch-meta') }}">Try Another URL</a>
</body>
</html>
