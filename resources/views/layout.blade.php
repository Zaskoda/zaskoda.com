<!doctype html>
<html lang="{{ $site->shortLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline first: Firefox may paint before external CSS finishes downloading --}}
        <style>
            html, body { background-color: #0a0806; color: #ede0cc; margin: 0; }
            a { color: #d4854f; text-decoration: none; }
            svg { display: inline-block; vertical-align: middle; max-width: none; }
            nav { position: fixed; top: 0; left: 0; right: 0; z-index: 50; background-color: rgba(10, 8, 6, 0.92); border-bottom: 1px solid #4a3828; }
        </style>

        {{-- Blocking stylesheet in <head> (never at bottom — that guarantees a flash) --}}
        @vite(['resources/css/site.css'])

        <title>{{ isset($title) && (string) $title !== '' ? $title . ' — ' . $site->name() : $site->name() }}</title>

        {{-- Web fonts load async so they don't block layout or trigger a Firefox repaint flash --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            rel="preload"
            as="style"
            href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@400;600;800;900&family=Space+Grotesk:wght@300;400;500;600&family=Source+Serif+4:ital,opsz,wght@0,8..60,300;0,8..60,400;0,8..60,600;1,8..60,300;1,8..60,400&display=swap"
            onload="this.onload=null;this.rel='stylesheet'"
        >
        <noscript>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@400;600;800;900&family=Space+Grotesk:wght@300;400;500;600&family=Source+Serif+4:ital,opsz,wght@0,8..60,300;0,8..60,400;0,8..60,600;1,8..60,300;1,8..60,400&display=swap">
        </noscript>
    </head>
    <body>
        @include('partials.nav')

        <main>
            @yield('content')
        </main>

        @include('partials.footer')

        @vite(['resources/js/site.js'])
    </body>
</html>
