@extends('layout')

@section('content')
@php
    $term = $page;
    $posts = $term->queryEntries()
        ->where('published', true)
        ->orderBy('date', 'desc')
        ->paginate(10);
@endphp

<div class="max-w-3xl mx-auto px-6 pt-28 pb-20">
    <h1 class="page-heading mb-14">Blog — {{ $term->title }}</h1>

    @include('partials.posts.category-nav', ['activeTermSlug' => $term->slug()])

    <div class="space-y-10">
        @foreach ($posts as $post)
            @include('partials.posts.card', ['post' => $post])
        @endforeach
    </div>

    @include('partials.posts.pagination', ['posts' => $posts])
</div>
@endsection
