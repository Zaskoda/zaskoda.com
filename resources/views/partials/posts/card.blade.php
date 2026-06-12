@php
    $cats = $post->augmentedValue('categories')->value();
    $catTerms = $cats ? collect($cats->get()) : collect();
@endphp

<article class="border-b border-cowboy-700 pb-10">
    <p class="meta-text text-xs mb-2">{{ $post->date()->format('F j, Y') }}</p>
    <h2 class="font-ui font-semibold text-2xl mb-3">
        <a href="{{ $post->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $post->title }}</a>
    </h2>
    @if ($post->value('excerpt'))
        <p class="body-copy mb-3">{{ $post->value('excerpt') }}</p>
    @endif
    @if ($catTerms->isNotEmpty())
        <p class="meta-text text-xs mb-3">
            {{ $catTerms->map->title()->implode(' · ') }}
        </p>
    @endif
    <a href="{{ $post->url() }}" class="font-ui text-sm">Read &rarr;</a>
</article>
