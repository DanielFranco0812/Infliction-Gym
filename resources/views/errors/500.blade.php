<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something went wrong | Infliction Gym</title>
    <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#121212;color:#fff;font:16px/1.6 Arial,sans-serif}
        main{max-width:560px;padding:32px}
        .code{color:#e63946;font-size:14px;font-weight:700;letter-spacing:.16em}
        h1{margin:.4em 0;font-size:clamp(2rem,7vw,4rem);line-height:1.05}
        p{color:#b8b8b8}
        a{display:inline-block;margin-top:16px;padding:12px 18px;background:#e63946;color:#fff;text-decoration:none;font-weight:700}
        a:focus-visible{outline:3px solid #fff;outline-offset:3px}
    </style>
</head>
<body>
    <main>
        <p class="code">INFLICTION GYM · 500</p>
        <h1>We're having trouble loading this page.</h1>
        <p>Please try again in a moment. If the problem continues, contact Infliction Gym.</p>
        <a href="{{ route('home') }}">Return to the home page</a>
    </main>
</body>
</html>