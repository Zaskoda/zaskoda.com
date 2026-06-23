@php
    $postsCollection = \Statamic\Facades\Collection::findByHandle('posts');
    $publishedPosts = \Statamic\Facades\Entry::query()
        ->where('collection', 'posts')
        ->where('published', true)
        ->get();

    $categoryCounts = [];
    foreach ($publishedPosts as $post) {
        $cats = $post->augmentedValue('categories')->value();
        foreach (($cats ? collect($cats->get()) : collect()) as $term) {
            $categoryCounts[$term->slug()] = ($categoryCounts[$term->slug()] ?? 0) + 1;
        }
    }

    $categoryTerms = \Statamic\Facades\Term::query()
        ->where('taxonomy', 'categories')
        ->get()
        ->filter(fn ($term) => ($categoryCounts[$term->slug()] ?? 0) > 0)
        ->sort(function ($a, $b) use ($categoryCounts) {
            $countA = $categoryCounts[$a->slug()] ?? 0;
            $countB = $categoryCounts[$b->slug()] ?? 0;
            if ($countA !== $countB) {
                return $countB <=> $countA;
            }

            return strcasecmp($a->title(), $b->title());
        })
        ->values();

    $activeTermSlug = $activeTermSlug ?? null;
    $allActive = $activeTermSlug === null;
@endphp

<div class="flex flex-wrap gap-2 mb-2">
    <a
        href="/blog"
        class="font-ui text-xs px-3 py-1.5 rounded border transition-colors {{ $allActive ? 'border-copper-600 text-copper-400 bg-cowboy-700' : 'border-copper-600 text-copper-400' }}"
        @if ($allActive) aria-current="page" @endif
    >All</a>
    @foreach ($categoryTerms as $term)
        @php
            $isActive = $activeTermSlug === $term->slug();
            $termUrl = $term->collection($postsCollection)->url();
        @endphp
        <a
            href="{{ $termUrl }}"
            class="font-ui text-xs px-3 py-1.5 rounded border transition-colors {{ $isActive ? 'border-copper-600 text-copper-400 bg-cowboy-700' : 'border-cowboy-600 text-cowboy-100 hover:border-copper-600' }}"
            @if ($isActive) aria-current="page" @endif
        >{{ $term->title }} ({{ $categoryCounts[$term->slug()] }})</a>
    @endforeach
</div>
