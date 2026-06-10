@extends('layout')

@section('content')
<article class="max-w-2xl mx-auto px-6 pt-28 pb-20">
    <p class="font-ui text-sm text-cowboy-500 mb-3">{{ $page->date()->format('F j, Y') }}</p>
    <h1 class="font-display font-bold text-4xl md:text-5xl text-cowboy-100 mb-10">{{ $title }}</h1>

    <div class="prose prose-invert font-body text-cowboy-100 leading-relaxed max-w-none">
        {!! $page->augmentedValue('content') !!}
    </div>

    <div class="mt-12 pt-8 border-t border-cowboy-700">
        <a href="/blog" class="font-ui text-sm">&larr; All posts</a>
    </div>
</article>
@endsection
