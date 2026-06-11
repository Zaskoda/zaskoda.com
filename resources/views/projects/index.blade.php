@extends('layout')

@section('content')
@php
    $projects = \Statamic\Facades\Entry::query()
        ->where('collection', 'projects')
        ->where('published', true)
        ->get()
        ->sortByDesc(function ($entry) {
            $sortDate = $entry->value('date_end') ?? $entry->value('date');

            return $sortDate ? \Carbon\Carbon::parse($sortDate)->timestamp : 0;
        })
        ->values();

    $projectTypes = \Statamic\Facades\Term::query()->where('taxonomy', 'project_type')->orderBy('title')->get();
    $contexts = \Statamic\Facades\Term::query()->where('taxonomy', 'context')->orderBy('title')->get();

    $typeCounts = [];
    $contextCounts = [];
    foreach ($projects as $entry) {
        $typesValue = $entry->augmentedValue('project_type')->value();
        foreach (($typesValue ? collect($typesValue->get()) : collect()) as $type) {
            $typeCounts[$type->slug()] = ($typeCounts[$type->slug()] ?? 0) + 1;
        }
        $contextsValue = $entry->augmentedValue('context')->value();
        foreach (($contextsValue ? collect($contextsValue->get()) : collect()) as $context) {
            $contextCounts[$context->slug()] = ($contextCounts[$context->slug()] ?? 0) + 1;
        }
    }
@endphp

<div class="max-w-7xl mx-auto px-6 md:px-12 pt-28 pb-20">
    <h1 class="page-heading mb-4">Projects</h1>
    <p class="body-copy text-lg mb-10 max-w-2xl">
        Software, art installations, robots, vehicles, and community platforms. Everything here
        was built, not bought.
    </p>

    <div class="space-y-3 mb-12">
        <div class="flex flex-wrap gap-2" data-filter-group="types">
            <button class="font-ui text-xs px-3 py-1.5 rounded border border-copper-600 text-copper-400 transition-colors" data-filter-all aria-pressed="true">All</button>
            @foreach ($projectTypes as $term)
                @if (($typeCounts[$term->slug()] ?? 0) > 0)
                    <button class="font-ui text-xs px-3 py-1.5 rounded border border-cowboy-600 text-cowboy-100 hover:border-copper-600 transition-colors" data-filter="{{ $term->slug() }}" aria-pressed="false">{{ $term->title }} ({{ $typeCounts[$term->slug()] }})</button>
                @endif
            @endforeach
        </div>
        <div class="flex flex-wrap gap-2" data-filter-group="contexts">
            <button class="font-ui text-xs px-3 py-1.5 rounded border border-circuit-500 text-circuit-400 transition-colors" data-filter-all aria-pressed="true">All</button>
            @foreach ($contexts as $term)
                @if (($contextCounts[$term->slug()] ?? 0) > 0)
                    <button class="font-ui text-xs px-3 py-1.5 rounded border border-cowboy-600 text-cowboy-100 hover:border-circuit-500 transition-colors" data-filter="{{ $term->slug() }}" aria-pressed="false">{{ $term->title }} ({{ $contextCounts[$term->slug()] }})</button>
                @endif
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($projects as $entry)
            @php
                $hero = $entry->augmentedValue('hero_image')->value();
                $typesValue = $entry->augmentedValue('project_type')->value();
                $types = $typesValue ? collect($typesValue->get()) : collect();
                $contextsValue = $entry->augmentedValue('context')->value();
                $entryContexts = $contextsValue ? collect($contextsValue->get()) : collect();
            @endphp
            <article
                class="surface-card overflow-hidden flex flex-col"
                data-project-card
                data-types="{{ $types->map->slug()->implode(' ') }}"
                data-contexts="{{ $entryContexts->map->slug()->implode(' ') }}"
            >
                <a href="{{ $entry->url() }}" class="block">
                    @if ($hero)
                        <img src="{{ $hero->url() }}" alt="{{ $entry->title }}" class="aspect-video w-full object-cover">
                    @else
                        <div class="aspect-video w-full bg-cowboy-700 flex items-center justify-center border-b border-cowboy-600">
                            <span class="font-display font-bold text-6xl text-cowboy-500">{{ \Illuminate\Support\Str::substr($entry->title, 0, 1) }}</span>
                        </div>
                    @endif
                </a>
                <div class="p-6 flex flex-col grow">
                    @if ($types->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach ($types as $type)
                                @include('partials.project-type-badge', ['term' => $type])
                            @endforeach
                        </div>
                    @endif
                    <h2 class="font-ui font-semibold text-lg text-cowboy-100 mb-2">
                        <a href="{{ $entry->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $entry->title }}</a>
                    </h2>
                    <p class="font-ui text-sm text-cowboy-100 leading-relaxed">{{ $entry->value('tagline') ?? $entry->value('summary') }}</p>
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection
