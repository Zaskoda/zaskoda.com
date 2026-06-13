<!doctype html>
<html lang="{{ $site->shortLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline first: Firefox may paint before external CSS finishes downloading --}}
        <style>
            html, body { background-color: #131720; color: #ede0cc; margin: 0; }
            /* Link default goes in the base layer so Tailwind utilities can override it */
            @layer base {
                a { color: #d4854f; text-decoration: none; }
            }
            svg { display: inline-block; vertical-align: middle; max-width: none; }
            /* Nav colors stay out of the transparent-nav home page: this rule is
               unlayered, so it would beat the Tailwind utilities that site.js toggles */
            #site-nav { position: fixed; top: 0; left: 0; right: 0; z-index: 50; }
            #site-nav:not([data-nav-transparent]) { background-color: rgba(19, 23, 32, 0.92); border-bottom: 1px solid #33404a; }
        </style>

        {{-- Blocking stylesheet in <head> (never at bottom — that guarantees a flash) --}}
        @vite(['resources/css/site.css'])

        <title>{{ isset($title) && (string) $title !== '' ? $title . ' — ' . $site->name() : $site->name() }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

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
