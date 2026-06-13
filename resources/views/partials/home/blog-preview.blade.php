@php
    $recentPosts = \Statamic\Facades\Entry::query()
        ->where('collection', 'posts')
        ->where('published', true)
        ->orderBy('date', 'desc')
        ->limit(3)
        ->get();
@endphp

<section class="bg-slate py-20 px-6 md:px-12 lg:px-16">
    <div class="max-w-7xl mx-auto">
        @include('partials.section-header', [
            'title' => 'From the Blog',
            'action_label' => 'Read the blog',
            'action_url' => '/blog',
        ])

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            @foreach ($recentPosts as $post)
                <article class="surface-card p-8 flex flex-col">
                    <p class="meta-text text-xs mb-2">{{ $post->date()->format('F j, Y') }}</p>
                    <h3 class="font-ui font-semibold text-lg text-cowboy-100 mb-3">
                        <a href="{{ $post->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $post->title }}</a>
                    </h3>
                    <p class="font-body text-sm text-cowboy-100 leading-relaxed mb-4">{{ $post->value('excerpt') }}</p>
                    <a href="{{ $post->url() }}" class="font-ui text-sm mt-auto">Read &rarr;</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
