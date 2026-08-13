@extends('layout')

@section('content')
@php
    $linkUrl = function ($value) {
        if (empty($value)) {
            return null;
        }
        if (is_string($value)) {
            return $value;
        }
        if (is_array($value)) {
            return $value['url'] ?? $value['link'] ?? null;
        }

        return null;
    };

    $resolveEntry = function ($value) {
        if (empty($value)) {
            return null;
        }
        $id = is_array($value) ? ($value[0] ?? null) : $value;
        if (! $id) {
            return null;
        }

        return \Statamic\Facades\Entry::find($id);
    };

    $talks = \Statamic\Facades\Entry::query()
        ->where('collection', 'talks')
        ->where('published', true)
        ->get()
        ->sortByDesc(fn ($entry) => \Carbon\Carbon::parse($entry->value('date') ?? '1970-01-01')->timestamp)
        ->values();

    $credits = \Statamic\Facades\Entry::query()
        ->where('collection', 'credits')
        ->where('published', true)
        ->get()
        ->sortByDesc(fn ($entry) => \Carbon\Carbon::parse($entry->value('date') ?? '1970-01-01')->timestamp)
        ->values();

    $creditGroups = [
        'engineering' => 'Programming / Engineering',
        'technology' => 'Technology',
        'community' => 'Online Community / Business',
        'pr' => 'Public Relations / Marketing',
        'support' => 'Customer / Technical Support',
    ];

    $talkTypeLabels = [
        'talk' => 'Talk',
        'panel' => 'Panel',
        'workshop' => 'Workshop',
        'keynote' => 'Keynote',
        'podcast' => 'Podcast',
        'series' => 'Demo / Talk Series',
    ];

    $awards = collect();
    foreach (\Statamic\Facades\Entry::query()->whereIn('collection', ['projects', 'work'])->where('published', true)->get() as $parent) {
        foreach (collect($parent->value('awards') ?? []) as $award) {
            if (empty($award['title'])) {
                continue;
            }
            $awards->push([
                'year' => $award['year'] ?? '',
                'title' => $award['title'],
                'source' => $award['source'] ?? null,
                'source_url' => $linkUrl($award['source_url'] ?? null),
                'amount' => $award['amount'] ?? null,
                'recognition_type' => $award['recognition_type'] ?? 'award',
                'sub_items' => collect($award['sub_items'] ?? [])->values()->all(),
                'parent' => $parent,
            ]);
        }
    }
    $awards = $awards->sortByDesc(fn ($award) => (int) ($award['year'] ?: 0))->values();
    $standardAwards = $awards->filter(fn ($award) => ($award['recognition_type'] ?? 'award') !== 'archive')->values();
    $archiveAwards = $awards->filter(fn ($award) => ($award['recognition_type'] ?? 'award') === 'archive')->values();
@endphp

{{-- Page header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-5xl mx-auto px-6 md:px-12 pt-28 pb-8">
        <h1 class="page-heading mb-4">Recognition</h1>
        <p class="body-copy text-lg mb-10 max-w-2xl">
            Talks I've given, games I've shipped, and grants and awards I've been
            fortunate enough to receive. The outside world's footnotes on the work.
        </p>

        <nav class="flex flex-wrap gap-2" aria-label="Recognition sections">
            <a href="#credits" class="font-ui text-xs px-3 py-1.5 rounded border border-copper-600 text-copper-400 hover:bg-cowboy-800 transition-colors">Game Credits</a>
            <a href="#awards" class="font-ui text-xs px-3 py-1.5 rounded border border-copper-600 text-copper-400 hover:bg-cowboy-800 transition-colors">Awards &amp; Grants</a>
            <a href="#talks" class="font-ui text-xs px-3 py-1.5 rounded border border-copper-600 text-copper-400 hover:bg-cowboy-800 transition-colors">Talks &amp; Appearances</a>
        </nav>
    </div>
</div>

@include('partials.surface-fade')

{{-- Credits — Surface B --}}
<section id="credits" class="bg-slate py-8 scroll-mt-20">
    <div class="max-w-5xl mx-auto px-6 md:px-12">
        @include('partials.section-header', ['title' => 'Game Credits'])

        <div class="mt-10">
        @foreach ($creditGroups as $categoryKey => $categoryLabel)
            @php
                $groupCredits = $credits->filter(fn ($entry) => $entry->value('role_category') === $categoryKey);
            @endphp
            @if ($groupCredits->isNotEmpty())
                <h3 class="font-ui font-semibold text-lg text-cowboy-100 mb-4 mt-8 first:mt-0">{{ $categoryLabel }}</h3>
                <ul class="space-y-3 mb-6">
                    @foreach ($groupCredits as $credit)
                        @php
                            $gameUrl = $linkUrl($credit->value('game_url'));
                            $mobyUrl = $linkUrl($credit->value('mobygames_url'));
                            $creditYear = $credit->value('date') ? \Carbon\Carbon::parse($credit->value('date'))->format('Y') : null;
                        @endphp
                        <li class="font-ui text-sm text-cowboy-100 leading-relaxed">
                            @if ($gameUrl)
                                <a href="{{ $gameUrl }}" target="_blank" rel="noopener" class="font-semibold text-cowboy-100 hover:text-copper-400">{{ $credit->title }}</a>
                            @else
                                <span class="font-semibold">{{ $credit->title }}</span>
                            @endif
                            @if ($creditYear)
                                <span class="text-cowboy-500">({{ $creditYear }})</span>
                            @endif
                            @if ($credit->value('platforms'))
                                <span class="text-cowboy-500"> — {{ $credit->value('platforms') }}</span>
                            @endif
                            <span class="text-cowboy-300"> — {{ $credit->value('role') }}</span>
                            @if ($mobyUrl)
                                <a href="{{ $mobyUrl }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300 ml-2">MobyGames &rarr;</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        @endforeach

        <p class="font-ui text-sm text-cowboy-300 mt-8">
            Full MobyGames profile:
            <a href="https://www.mobygames.com/person/11638/scott-dudley/" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">26 credits across 15 shipped titles &rarr;</a>
        </p>
        </div>
    </div>
</section>

@include('partials.surface-fade', ['reverse' => true])

{{-- Awards — Surface A --}}
<section id="awards" class="bg-ink py-8 scroll-mt-20">
    <div class="max-w-5xl mx-auto px-6 md:px-12">
        @include('partials.section-header', ['title' => 'Awards & Grants'])

        <div class="space-y-6 mt-10">
            @foreach ($standardAwards as $award)
                <article class="flex flex-col sm:flex-row gap-4 sm:gap-8 surface-card p-6 md:p-8">
                    <div class="font-display font-bold text-4xl text-cowboy-300 shrink-0 w-full sm:w-24">{{ $award['year'] }}</div>
                    <div class="min-w-0">
                        <h3 class="font-ui font-semibold text-lg text-cowboy-100 mb-2">{{ $award['title'] }}</h3>
                        @if ($award['source'])
                            <p class="font-ui text-sm text-cowboy-300 mb-2">
                                from
                                @if ($award['source_url'])
                                    <a href="{{ $award['source_url'] }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">{{ $award['source'] }}</a>
                                @else
                                    {{ $award['source'] }}
                                @endif
                            </p>
                        @endif
                        @if ($award['amount'])
                            <p class="font-ui text-sm metric-highlight mb-2">{{ $award['amount'] }}</p>
                        @endif
                        <p class="font-ui text-sm text-cowboy-300">
                            For:
                            <a href="{{ $award['parent']->url() }}" class="text-copper-400 hover:text-copper-300">{{ $award['parent']->title }}</a>
                        </p>
                        @php
                            $subItems = collect($award['sub_items'] ?? [])->filter(fn ($item) => ! empty($item['title']));
                        @endphp
                        @if ($subItems->isNotEmpty())
                            <ul class="mt-4 pt-4 border-t border-cowboy-700 space-y-3">
                                @foreach ($subItems as $subItem)
                                    @php
                                        $subSourceUrl = $linkUrl($subItem['source_url'] ?? null);
                                    @endphp
                                    <li>
                                        <p class="font-ui font-semibold text-cowboy-100 mb-1">{{ $subItem['title'] }}</p>
                                        @if (! empty($subItem['source']))
                                            <p class="font-ui text-sm text-cowboy-300 mb-1">
                                                from
                                                @if ($subSourceUrl)
                                                    <a href="{{ $subSourceUrl }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">{{ $subItem['source'] }}</a>
                                                @else
                                                    {{ $subItem['source'] }}
                                                @endif
                                            </p>
                                        @endif
                                        @if (! empty($subItem['amount']))
                                            <p class="font-ui text-sm metric-highlight">{{ $subItem['amount'] }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        @if ($archiveAwards->isNotEmpty())
            <h3 class="font-ui font-semibold text-lg text-cowboy-100 mb-4 mt-12">Archives &amp; Preservation</h3>
            <p class="font-ui text-sm text-cowboy-300 mb-6 max-w-2xl">
                Work that's been preserved by independent archives — long after the platforms that hosted it moved on.
            </p>
            <div class="space-y-6">
                @foreach ($archiveAwards as $award)
                    <article class="flex flex-col sm:flex-row gap-4 sm:gap-8 surface-card p-6 md:p-8">
                        <div class="font-display font-bold text-4xl text-cowboy-300 shrink-0 w-full sm:w-24">{{ $award['year'] }}</div>
                        <div class="min-w-0">
                            <h3 class="font-ui font-semibold text-lg text-cowboy-100 mb-2">{{ $award['title'] }}</h3>
                            @if ($award['source'])
                                <p class="font-ui text-sm text-cowboy-300 mb-2">
                                    from
                                    @if ($award['source_url'])
                                        <a href="{{ $award['source_url'] }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">{{ $award['source'] }}</a>
                                    @else
                                        {{ $award['source'] }}
                                    @endif
                                </p>
                            @endif
                            @if ($award['amount'])
                                <p class="font-ui text-sm metric-highlight mb-2">{{ $award['amount'] }}</p>
                            @endif
                            <p class="font-ui text-sm text-cowboy-300">
                                For:
                                <a href="{{ $award['parent']->url() }}" class="text-copper-400 hover:text-copper-300">{{ $award['parent']->title }}</a>
                            </p>
                            @php
                                $subItems = collect($award['sub_items'] ?? [])->filter(fn ($item) => ! empty($item['title']));
                            @endphp
                            @if ($subItems->isNotEmpty())
                                <ul class="mt-4 pt-4 border-t border-cowboy-700 space-y-3">
                                    @foreach ($subItems as $subItem)
                                        @php
                                            $subSourceUrl = $linkUrl($subItem['source_url'] ?? null);
                                        @endphp
                                        <li>
                                            <p class="font-ui font-semibold text-cowboy-100 mb-1">{{ $subItem['title'] }}</p>
                                            @if (! empty($subItem['source']))
                                                <p class="font-ui text-sm text-cowboy-300 mb-1">
                                                    from
                                                    @if ($subSourceUrl)
                                                        <a href="{{ $subSourceUrl }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">{{ $subItem['source'] }}</a>
                                                    @else
                                                        {{ $subItem['source'] }}
                                                    @endif
                                                </p>
                                            @endif
                                            @if (! empty($subItem['amount']))
                                                <p class="font-ui text-sm metric-highlight">{{ $subItem['amount'] }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

@include('partials.surface-fade')

{{-- Talks — Surface B --}}
<section id="talks" class="bg-slate py-8 scroll-mt-20">
    <div class="max-w-5xl mx-auto px-6 md:px-12">
        @include('partials.section-header', ['title' => 'Talks & Appearances'])

        <div class="space-y-6 mt-10">
            @foreach ($talks as $talk)
                @php
                    $eventUrl = $linkUrl($talk->value('event_url'));
                    $videoUrl = $linkUrl($talk->value('video_url'));
                    $slidesUrl = $linkUrl($talk->value('slides_url'));
                    $relatedProject = $resolveEntry($talk->value('related_project'));
                    $relatedWork = $resolveEntry($talk->value('related_work'));
                    $talkType = $talk->value('talk_type');
                    $hasContent = ! empty($talk->value('content'));
                    $talkDate = $talk->value('date') ? \Carbon\Carbon::parse($talk->value('date'))->format('F Y') : null;
                    $appearances = collect($talk->value('appearances') ?? [])->filter(fn ($item) => ! empty($item['event']));
                @endphp
                <article class="surface-card p-6 md:p-8">
                    <div class="flex flex-wrap items-start gap-3 mb-3">
                        <h3 class="font-ui font-semibold text-xl text-cowboy-100">
                            @if ($hasContent)
                                <a href="{{ $talk->url() }}" class="text-cowboy-100 hover:text-copper-400">{{ $talk->title }}</a>
                            @else
                                {{ $talk->title }}
                            @endif
                        </h3>
                        @if ($talkType)
                            <span class="badge-warm">{{ $talkTypeLabels[$talkType] ?? $talkType }}</span>
                        @endif
                    </div>

                    <p class="meta-text text-sm mb-3">
                        @if ($eventUrl)
                            <a href="{{ $eventUrl }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">{{ $talk->value('event') }}</a>
                        @else
                            {{ $talk->value('event') }}
                        @endif
                        @if ($talkDate)
                            · {{ $talkDate }}
                        @endif
                        @if ($talk->value('location'))
                            · {{ $talk->value('location') }}
                        @endif
                    </p>

                    @if ($talk->value('representing'))
                        <p class="font-ui text-sm text-cowboy-300 mb-1">Representing: {{ $talk->value('representing') }}</p>
                    @endif
                    @if ($talk->value('co_presenter'))
                        <p class="font-ui text-sm text-cowboy-300 mb-3">With: {{ $talk->value('co_presenter') }}</p>
                    @endif

                    @if ($talk->value('summary'))
                        <p class="body-copy text-base mb-4">{{ $talk->value('summary') }}</p>
                    @endif

                    @if ($appearances->isNotEmpty())
                        <div class="mb-4">
                            <p class="font-ui text-sm text-cowboy-300 mb-2">Recordings:</p>
                            <ul class="space-y-2 font-ui text-sm">
                                @foreach ($appearances as $appearance)
                                    @php
                                        $appearanceVideoUrl = $linkUrl($appearance['video_url'] ?? null);
                                        $appearanceDate = ! empty($appearance['date'])
                                            ? \Carbon\Carbon::parse($appearance['date'])->format('F Y')
                                            : null;
                                    @endphp
                                    <li>
                                        <div class="flex flex-wrap items-baseline gap-x-2">
                                            <span class="text-cowboy-500">&rarr;</span>
                                            <span class="text-cowboy-300">
                                                {{ $appearance['event'] }}@if ($appearanceDate), {{ $appearanceDate }}@endif
                                            </span>
                                            @if ($appearanceVideoUrl)
                                                <span class="text-cowboy-500">&nbsp;&mdash;</span>
                                                <a href="{{ $appearanceVideoUrl }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">Watch &rarr;</a>
                                            @endif
                                        </div>
                                        @if (! empty($appearance['note']))
                                            <p class="text-sm text-cowboy-300 mt-1">{{ $appearance['note'] }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-x-4 gap-y-2 font-ui text-sm">
                        @if ($videoUrl && $appearances->isEmpty())
                            <a href="{{ $videoUrl }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">Watch recording &rarr;</a>
                        @endif
                        @if ($slidesUrl)
                            <a href="{{ $slidesUrl }}" target="_blank" rel="noopener" class="text-copper-400 hover:text-copper-300">View slides &rarr;</a>
                        @endif
                        @if ($relatedProject)
                            <a href="{{ $relatedProject->url() }}" class="text-copper-400 hover:text-copper-300">Related: {{ $relatedProject->title }} &rarr;</a>
                        @endif
                        @if ($relatedWork)
                            <a href="{{ $relatedWork->url() }}" class="text-copper-400 hover:text-copper-300">Related: {{ $relatedWork->title }} &rarr;</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

@include('partials.surface-fade', ['reverse' => true])
@endsection
