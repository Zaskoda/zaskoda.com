@php
    $featuredProjects = \Statamic\Facades\Entry::query()
        ->where('collection', 'projects')
        ->where('published', true)
        ->where('featured', true)
        ->orderBy('featured_order', 'asc')
        ->limit(6)
        ->get();
@endphp

<section class="bg-ink py-20 px-6 md:px-12 lg:px-16">
    <div class="max-w-7xl mx-auto">
        @include('partials.section-header', [
            'title' => "Things I've Built",
            'action_label' => 'View all projects',
            'action_url' => '/projects',
        ])

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
            @foreach ($featuredProjects as $entry)
                @php
                    $hero = $entry->augmentedValue('hero_image')->value();
                    $typesValue = $entry->augmentedValue('project_type')->value();
                    $types = $typesValue ? collect($typesValue->get()) : collect();
                @endphp
                <article class="surface-card overflow-hidden flex flex-col">

                    <a href="{{ $entry->url() }}" class="block">
                        @if ($hero)
                            <img src="{{ $hero->url() }}" alt="{{ $entry->title }}" class="aspect-video w-full object-cover">
                        @else
                            <div class="aspect-video w-full bg-cowboy-700 flex items-center justify-center border-b border-cowboy-600">
                                <span class="font-display font-bold text-6xl text-cowboy-500">{{ \Illuminate\Support\Str::substr($entry->title, 0, 1) }}</span>
                            </div>
                        @endif
                    </a>

                    <div class="p-5 flex flex-col grow">
                        @if ($types->isNotEmpty())
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach ($types as $type)
                                    @include('partials.project-type-badge', ['term' => $type])
                                @endforeach
                            </div>
                        @endif
                        <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-2">
                            <a href="{{ $entry->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $entry->title }}</a>
                        </h3>
                        <p class="font-ui text-sm text-cowboy-100 leading-relaxed">{{ $entry->value('tagline') ?? $entry->value('summary') }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
