<!doctype html>
<html lang="{{ $site->shortLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ ($title ?? null) ? $title . ' — ' . $site->name() : $site->name() }}</title>
        @vite(['resources/css/site.css', 'resources/js/site.js'])
    </head>
    <body class="bg-zinc-100 dark:bg-zinc-900 font-sans leading-normal text-zinc-800 dark:text-zinc-400">
        <div class="mx-auto px-2 lg:min-h-screen flex flex-col items-center justify-center">
            {!! $template_content !!}
        </div>
    </body>
</html>
