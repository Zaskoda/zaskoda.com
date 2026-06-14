@extends('layout')

@section('content')
@php
    $entry = $page;
    $hero = $entry->augmentedValue('hero_image')->value();
    $galleryValue = $entry->augmentedValue('gallery')->value();
    $gallery = $galleryValue ? collect($galleryValue->get()) : collect();
    $typesValue = $entry->augmentedValue('project_type')->value();
    $types = $typesValue ? collect($typesValue->get()) : collect();
    $techValue = $entry->augmentedValue('tech_stack')->value();
    $tech = $techValue ? collect($techValue->get()) : collect();
    $relatedWorkValue = $entry->augmentedValue('related_work')->value();
    $relatedWork = $relatedWorkValue ? collect($relatedWorkValue->get()) : collect();
    $relatedProjectsValue = $entry->augmentedValue('related_projects')->value();
    $relatedProjects = $relatedProjectsValue ? collect($relatedProjectsValue->get()) : collect();
    $links = collect($entry->value('links') ?? []);
    $awards = collect($entry->value('awards') ?? []);
    $startYear = $entry->value('date') ? \Carbon\Carbon::parse($entry->value('date'))->format('Y') : null;
    $endYear = $entry->value('date_end') ? \Carbon\Carbon::parse($entry->value('date_end'))->format('Y') : null;
    $statusLabels = ['active' => 'Active', 'completed' => 'Completed', 'ongoing' => 'Ongoing', 'archived' => 'Archived'];
    $status = $entry->value('status');
@endphp

<article data-lightbox-region>
{{-- Project header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-3xl mx-auto px-6 pt-28 pb-10">

    @if ($hero)
        <img src="{{ $hero->url() }}" alt="{{ $entry->title }}" class="w-full aspect-video object-cover rounded-lg border border-cowboy-600 mb-10">
    @endif

    @if ($types->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach ($types as $type)
                @include('partials.project-type-badge', ['term' => $type])
            @endforeach
        </div>
    @endif

    <h1 class="page-heading mb-3">{{ $entry->title }}</h1>

    @if ($entry->value('tagline'))
        <p class="font-body text-xl text-cowboy-100 italic mb-4">{{ $entry->value('tagline') }}</p>
    @endif

    <p class="meta-text text-sm mb-10">
        {{ $startYear }}{{ $endYear ? ' - ' . $endYear : '' }}
        @if ($status) · <span class="{{ $status === 'active' ? 'text-circuit-400' : '' }}">{{ $statusLabels[$status] ?? $status }}</span> @endif
    </p>
    </div>
</div>

@include('partials.surface-fade')

{{-- Project body — Surface B --}}
<div class="bg-slate">
    <div class="max-w-3xl mx-auto px-6 pt-12 pb-20">

    <div class="prose prose-invert font-body max-w-none mb-10">
        {!! $entry->augmentedValue('content') !!}
    </div>

    @if ($gallery->isNotEmpty())
        <div class="grid grid-cols-2 gap-4 mb-10">
            @foreach ($gallery as $image)
                <img src="{{ $image->url() }}" alt="{{ $entry->title }} gallery image" class="rounded-lg object-cover w-full border border-cowboy-600">
            @endforeach
        </div>
    @endif

    @if ($links->isNotEmpty())
        <h2 class="section-heading text-xl font-ui font-semibold">Links</h2>
        <ul class="space-y-2 mb-10">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="font-ui text-sm">
                        {{ $link['label'] }} &nearr;
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    @if ($awards->isNotEmpty())
        <h2 class="section-heading text-xl font-ui font-semibold">Awards &amp; Recognition</h2>
        <ul class="space-y-2 mb-10">
            @foreach ($awards as $award)
                @php
                    $subItems = collect($award['sub_items'] ?? [])->filter(fn ($item) => ! empty($item['title']));
                @endphp
                <li class="font-ui text-sm text-cowboy-100 pl-4 border-l-2 border-copper-600">
                    {{ $award['title'] }}@if ($award['year'] ?? null) ({{ $award['year'] }})@endif
                    @if ($award['amount'] ?? null) · <span class="metric-highlight">{{ $award['amount'] }}</span> @endif
                    @if ($subItems->isNotEmpty())
                        <ul class="mt-2 ml-4 space-y-1">
                            @foreach ($subItems as $subItem)
                                <li>
                                    {{ $subItem['title'] }}@if ($subItem['amount'] ?? null) — <span class="metric-highlight">{{ $subItem['amount'] }}</span>@endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @if ($tech->isNotEmpty())
        <h2 class="section-heading text-xl font-ui font-semibold">Tech Stack</h2>
        <div class="flex flex-wrap gap-2 mb-10">
            @foreach ($tech as $term)
                <span class="badge-tech">{{ $term->title }}</span>
            @endforeach
        </div>
    @endif

    @if ($relatedWork->isNotEmpty() || $relatedProjects->isNotEmpty())
        <h2 class="section-heading text-xl font-ui font-semibold">Related</h2>
        <ul class="space-y-2 mb-10">
            @foreach ($relatedWork as $work)
                <li><a href="{{ $work->url() }}" class="font-ui text-sm">{{ $work->title }} ({{ $work->value('role') }}) &rarr;</a></li>
            @endforeach
            @foreach ($relatedProjects as $project)
                <li><a href="{{ $project->url() }}" class="font-ui text-sm">{{ $project->title }} &rarr;</a></li>
            @endforeach
        </ul>
    @endif

    <a href="/projects" class="font-ui text-sm">&larr; All projects</a>
    </div>
</div>
</article>
@endsection
