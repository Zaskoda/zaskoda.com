@extends('layout')

@section('content')
@php
    $workEntries = \Statamic\Facades\Entry::query()
        ->where('collection', 'work')
        ->where('published', true)
        ->orderBy('start_date', 'desc')
        ->get();

    $typeLabels = [
        'full_time' => 'Full-time',
        'contract' => 'Contract / Consulting',
        'founder' => 'Founder',
        'volunteer' => 'Volunteer',
        'academic' => 'Academic / Practicum',
    ];
@endphp

<div class="max-w-5xl mx-auto px-6 md:px-12 pt-28 pb-20">
    <h1 class="page-heading mb-4">Work</h1>
    <p class="body-copy text-lg mb-14 max-w-2xl">
        Thirty years of building: studios, startups, retailers, nonprofits, and a couple of
        companies of my own.
    </p>

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
            @endphp
            <article class="surface-card p-8 {{ $isVolunteer ? '!border-circuit-500/50' : '' }}">
                <div class="flex flex-wrap items-baseline gap-x-4 gap-y-1 mb-1">
                    <h2 class="font-ui font-semibold text-2xl text-cowboy-100">
                        <a href="{{ $entry->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $entry->title }}</a>
                    </h2>
                    @if ($type)
                        <span class="font-ui text-xs px-2 py-0.5 rounded border {{ $isVolunteer ? 'badge-tech' : 'badge-warm' }}">
                            {{ $typeLabels[$type] ?? $type }}
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
@endsection
