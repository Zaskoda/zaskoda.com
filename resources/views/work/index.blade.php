@extends('layout')

@section('content')
@php
    $workEntries = \Statamic\Facades\Entry::query()
        ->where('collection', 'work')
        ->where('published', true)
        ->get()
        ->sortByDesc(function ($entry) {
            if ($entry->value('is_current')) {
                return PHP_INT_MAX;
            }

            $sortDate = $entry->value('end_date') ?? $entry->value('start_date');

            return $sortDate ? \Carbon\Carbon::parse($sortDate)->timestamp : 0;
        })
        ->values();

    $typeLabels = [
        'full_time' => 'Full-time',
        'contract' => 'Contract',
        'founder' => 'Founder',
        'volunteer' => 'Volunteer',
        'academic' => 'Academic',
    ];

    $employmentTypeOrder = ['full_time', 'contract', 'founder', 'volunteer', 'academic'];

    $typeCounts = [];
    $focusCounts = [];
    $industryCounts = [];

    foreach ($workEntries as $entry) {
        $empType = $entry->value('employment_type');
        if ($empType) {
            $typeCounts[$empType] = ($typeCounts[$empType] ?? 0) + 1;
        }

        $focusValue = $entry->augmentedValue('role_focus')->value();
        foreach (($focusValue ? collect($focusValue->get()) : collect()) as $term) {
            $focusCounts[$term->slug()] = ($focusCounts[$term->slug()] ?? 0) + 1;
        }

        $industryValue = $entry->augmentedValue('industry')->value();
        foreach (($industryValue ? collect($industryValue->get()) : collect()) as $term) {
            $industryCounts[$term->slug()] = ($industryCounts[$term->slug()] ?? 0) + 1;
        }
    }

    $focusOrder = ['engineering', 'community', 'leadership', 'writing', 'support'];
    $focusTerms = collect($focusOrder)->map(fn ($slug) => \Statamic\Facades\Term::find("role_focus::{$slug}"))->filter();

    $industryOrder = ['gaming', 'blockchain', 'e-commerce', 'legal', 'media', 'nonprofit-events', 'internet-services', 'education'];
    $industryTerms = collect($industryOrder)->map(fn ($slug) => \Statamic\Facades\Term::find("industry::{$slug}"))->filter();

    $cardTypeLabels = [
        'full_time' => 'Full-time',
        'contract' => 'Contract / Consulting',
        'founder' => 'Founder',
        'volunteer' => 'Volunteer',
        'academic' => 'Academic / Practicum',
    ];
@endphp

{{-- Page header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-5xl mx-auto px-6 md:px-12 pt-28 pb-8">
    <h1 class="page-heading mb-4">Work</h1>
    <p class="body-copy text-lg mb-10 max-w-2xl">
        Thirty years of building: studios, startups, retailers, nonprofits, and a couple of
        companies of my own.
    </p>

    <div class="space-y-3">
        {{-- Row 1: employment type (select field, not taxonomy) --}}
        <div class="flex flex-wrap gap-2" data-filter-group="type">
            <button class="font-ui text-xs px-3 py-1.5 rounded border border-copper-600 text-copper-400 transition-colors" data-filter-all aria-pressed="true">All</button>
            @foreach ($employmentTypeOrder as $slug)
                @if (($typeCounts[$slug] ?? 0) > 0)
                    <button class="font-ui text-xs px-3 py-1.5 rounded border border-cowboy-600 text-cowboy-100 hover:border-copper-600 transition-colors" data-filter="{{ $slug }}" aria-pressed="false">{{ $typeLabels[$slug] }} ({{ $typeCounts[$slug] }})</button>
                @endif
            @endforeach
        </div>

        {{-- Row 2: role focus --}}
        <div class="flex flex-wrap gap-2" data-filter-group="focus">
            <button class="font-ui text-xs px-3 py-1.5 rounded border border-circuit-500 text-circuit-400 transition-colors" data-filter-all aria-pressed="true">All</button>
            @foreach ($focusTerms as $term)
                @if (($focusCounts[$term->slug()] ?? 0) > 0)
                    <button class="font-ui text-xs px-3 py-1.5 rounded border border-cowboy-600 text-cowboy-100 hover:border-circuit-500 transition-colors" data-filter="{{ $term->slug() }}" aria-pressed="false">{{ $term->title }} ({{ $focusCounts[$term->slug()] }})</button>
                @endif
            @endforeach
        </div>

        {{-- Row 3: industry --}}
        <div class="flex flex-wrap gap-2" data-filter-group="industry">
            <button class="font-ui text-xs px-3 py-1.5 rounded border border-copper-600 text-copper-400 transition-colors" data-filter-all aria-pressed="true">All</button>
            @foreach ($industryTerms as $term)
                @if (($industryCounts[$term->slug()] ?? 0) > 0)
                    <button class="font-ui text-xs px-3 py-1.5 rounded border border-cowboy-600 text-cowboy-100 hover:border-copper-600 transition-colors" data-filter="{{ $term->slug() }}" aria-pressed="false">{{ $term->title }} ({{ $industryCounts[$term->slug()] }})</button>
                @endif
            @endforeach
        </div>
    </div>
    </div>
</div>

@include('partials.surface-fade')

{{-- Work history — Surface B --}}
<section class="bg-slate">
    <div class="max-w-5xl mx-auto px-6 md:px-12 pt-6 pb-20">

    <p class="font-ui text-sm text-cowboy-500 hidden mb-6" data-filter-empty>Nothing matches that combination.</p>

    <div class="space-y-6">
        @foreach ($workEntries as $entry)
            @php
                $start = $entry->value('start_date') ? \Carbon\Carbon::parse($entry->value('start_date'))->format('Y') : null;
                $end = $entry->value('is_current')
                    ? 'Present'
                    : ($entry->value('end_date') ? \Carbon\Carbon::parse($entry->value('end_date'))->format('Y') : null);
                $type = $entry->value('employment_type');
                $isVolunteer = in_array($type, ['volunteer', 'academic']);
                $achievements = collect($entry->value('achievements') ?? [])->take(2);

                $focusValue = $entry->augmentedValue('role_focus')->value();
                $entryFocus = $focusValue ? collect($focusValue->get()) : collect();
                $industryValue = $entry->augmentedValue('industry')->value();
                $entryIndustries = $industryValue ? collect($industryValue->get()) : collect();
            @endphp
            <article
                class="surface-card p-8 {{ $isVolunteer ? '!border-circuit-500/50' : '' }}"
                data-work-card
                data-type="{{ $type }}"
                data-focus="{{ $entryFocus->map->slug()->implode(' ') }}"
                data-industry="{{ $entryIndustries->map->slug()->implode(' ') }}"
            >
                <div class="flex flex-wrap items-baseline gap-x-4 gap-y-1 mb-1">
                    <h2 class="font-ui font-semibold text-2xl text-cowboy-100">
                        <a href="{{ $entry->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $entry->title }}</a>
                    </h2>
                    @if ($type)
                        <span class="font-ui text-xs px-2 py-0.5 rounded border {{ $isVolunteer ? 'badge-tech' : 'badge-warm' }}">
                            {{ $cardTypeLabels[$type] ?? $type }}
                        </span>
                    @endif
                </div>
                <p class="font-ui text-sm text-cowboy-100 mb-1">{{ $entry->value('role') }}</p>
                <p class="meta-text text-xs mb-4">
                    {{ $start }}{{ $end ? ' - ' . $end : '' }}{{ $entry->value('location') ? ' · ' . $entry->value('location') : '' }}
                </p>
                <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-4">{{ $entry->value('summary') }}</p>
                @if ($achievements->isNotEmpty())
                    <ul class="space-y-1 mb-4">
                        @foreach ($achievements as $achievement)
                            <li class="font-ui text-sm metric-highlight">{{ $achievement['text'] }}</li>
                        @endforeach
                    </ul>
                @endif
                <a href="{{ $entry->url() }}" class="font-ui text-sm">Full details &rarr;</a>
            </article>
        @endforeach
    </div>
    </div>
</section>
@endsection
