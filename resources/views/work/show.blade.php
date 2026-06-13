@extends('layout')

@section('content')
@php
    $entry = $page;
    $typeLabels = [
        'full_time' => 'Full-time',
        'contract' => 'Contract / Consulting',
        'founder' => 'Founder',
        'volunteer' => 'Volunteer',
        'academic' => 'Academic / Practicum',
    ];
    $start = $entry->value('start_date') ? \Carbon\Carbon::parse($entry->value('start_date'))->format('F Y') : null;
    $end = $entry->value('is_current')
        ? 'Present'
        : ($entry->value('end_date') ? \Carbon\Carbon::parse($entry->value('end_date'))->format('F Y') : null);
    $achievements = collect($entry->value('achievements') ?? []);
    $techValue = $entry->augmentedValue('tech_stack')->value();
    $tech = $techValue ? collect($techValue->get()) : collect();
    $logo = $entry->augmentedValue('logo')->value();
    $relatedValue = $entry->augmentedValue('related_projects')->value();
    $related = $relatedValue ? collect($relatedValue->get()) : collect();
    $type = $entry->value('employment_type');
    $isVolunteer = in_array($type, ['volunteer', 'academic']);
@endphp

<article>
{{-- Role header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-3xl mx-auto px-6 pt-28 pb-10">

    @if ($logo)
        <img src="{{ $logo->url() }}" alt="{{ $entry->title }} logo" class="h-16 mb-8 object-contain">
    @endif

    <h1 class="page-heading mb-2">{{ $entry->title }}</h1>
    <p class="font-ui text-xl text-cowboy-100 mb-2">{{ $entry->value('role') }}</p>
    <p class="meta-text text-sm mb-8">
        {{ $start }}{{ $end ? ' - ' . $end : '' }}
        @if ($entry->value('location')) · {{ $entry->value('location') }} @endif
        @if ($type) · {{ $typeLabels[$type] ?? $type }} @endif
    </p>
    </div>
</div>

@include('partials.surface-fade')

{{-- Role body — Surface B --}}
<div class="bg-slate">
    <div class="max-w-3xl mx-auto px-6 pt-12 pb-20">

    <div class="prose prose-invert font-body max-w-none mb-10">
        {!! $entry->augmentedValue('content') !!}
    </div>

    @if ($achievements->isNotEmpty())
        <h2 class="section-heading text-xl font-ui font-semibold">Key Achievements</h2>
        <ul class="space-y-2 mb-10">
            @foreach ($achievements as $achievement)
                <li class="font-ui text-sm metric-highlight pl-4 border-l-2 border-copper-600">{{ $achievement['text'] }}</li>
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

    @if ($related->isNotEmpty())
        <h2 class="section-heading text-xl font-ui font-semibold">Related Projects</h2>
        <ul class="space-y-2 mb-10">
            @foreach ($related as $project)
                <li><a href="{{ $project->url() }}" class="font-ui text-sm">{{ $project->title }} &rarr;</a></li>
            @endforeach
        </ul>
    @endif

    <a href="/work" class="font-ui text-sm">&larr; All work</a>
    </div>
</div>
</article>
@endsection
