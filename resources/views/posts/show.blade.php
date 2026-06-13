@extends('layout')

@section('content')
<article>
{{-- Post header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-2xl mx-auto px-6 pt-28 pb-10">
    <p class="font-ui text-sm text-cowboy-500 mb-3">{{ $page->date()->format('F j, Y') }}</p>
    @php
        $cats = $page->augmentedValue('categories')->value();
        $catTerms = $cats ? collect($cats->get()) : collect();
        $postsCollection = \Statamic\Facades\Collection::findByHandle('posts');
    @endphp
    @if ($catTerms->isNotEmpty())
        <p class="meta-text text-xs mb-3">
            @foreach ($catTerms as $term)
                @if (!$loop->first) · @endif
                <a href="{{ $term->collection($postsCollection)->url() }}" class="hover:text-copper-400">{{ $term->title() }}</a>
            @endforeach
        </p>
    @endif
    <h1 class="font-display font-bold text-4xl md:text-5xl text-cowboy-100">{{ $title }}</h1>
    </div>
</div>

@include('partials.surface-fade')

{{-- Post body — Surface B --}}
<div class="bg-slate">
    <div class="max-w-2xl mx-auto px-6 pt-12 pb-20">
    <div class="post-body prose prose-invert font-body text-cowboy-100 leading-relaxed max-w-none">
        {!! $page->augmentedValue('content') !!}
    </div>

    <div class="mt-12 pt-8 border-t border-cowboy-700">
        <a href="/blog" class="font-ui text-sm">&larr; All posts</a>
    </div>
    </div>
</div>
</article>
@endsection
