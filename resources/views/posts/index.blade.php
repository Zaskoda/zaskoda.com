@extends('layout')

@section('content')
@php
    $posts = \Statamic\Facades\Entry::query()
        ->where('collection', 'posts')
        ->where('published', true)
        ->orderBy('date', 'desc')
        ->paginate(10);
@endphp

<div class="max-w-3xl mx-auto px-6 pt-28 pb-20">
    <h1 class="page-heading mb-14">Blog</h1>

    <div class="space-y-10">
        @foreach ($posts as $post)
            <article class="border-b border-cowboy-700 pb-10">
                <p class="meta-text text-xs mb-2">{{ $post->date()->format('F j, Y') }}</p>
                <h2 class="font-ui font-semibold text-2xl mb-3">
                    <a href="{{ $post->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $post->title }}</a>
                </h2>
                @if ($post->value('excerpt'))
                    <p class="body-copy mb-3">{{ $post->value('excerpt') }}</p>
                @endif
                <a href="{{ $post->url() }}" class="font-ui text-sm">Read &rarr;</a>
            </article>
        @endforeach
    </div>

    @if ($posts->hasPages())
        <nav class="flex justify-between items-center mt-12" aria-label="Pagination">
            <div>
                @if ($posts->previousPageUrl())
                    <a href="{{ $posts->previousPageUrl() }}" class="font-ui text-sm">&larr; Newer posts</a>
                @endif
            </div>
            <p class="meta-text text-xs">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</p>
            <div>
                @if ($posts->nextPageUrl())
                    <a href="{{ $posts->nextPageUrl() }}" class="font-ui text-sm">Older posts &rarr;</a>
                @endif
            </div>
        </nav>
    @endif
</div>
@endsection
