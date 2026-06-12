@php
    $featuredWork = \Statamic\Facades\Entry::query()
        ->where('collection', 'work')
        ->where('published', true)
        ->where('featured', true)
        ->get()
        ->sortByDesc(function ($entry) {
            if ($entry->value('is_current')) {
                return PHP_INT_MAX;
            }

            $sortDate = $entry->value('end_date') ?? $entry->value('start_date');

            return $sortDate ? \Carbon\Carbon::parse($sortDate)->timestamp : 0;
        })
        ->take(3)
        ->values();
@endphp

<section class="bg-ink py-20 px-6 md:px-12 lg:px-16">
    <div class="max-w-7xl mx-auto">
        @include('partials.section-header', [
            'title' => 'Selected Work',
            'action_label' => 'View all work',
            'action_url' => '/work',
        ])

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            @foreach ($featuredWork as $entry)
                @php
                    $start = $entry->value('start_date') ? \Carbon\Carbon::parse($entry->value('start_date'))->format('Y') : null;
                    $end = $entry->value('is_current')
                        ? 'Present'
                        : ($entry->value('end_date') ? \Carbon\Carbon::parse($entry->value('end_date'))->format('Y') : null);
                    $achievements = collect($entry->value('achievements') ?? []);
                    $standout = $achievements->first()['text'] ?? null;
                @endphp
                <article class="surface-card p-8 flex flex-col">
                    <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-1">{{ $entry->title }}</h3>
                    <p class="font-ui text-sm text-cowboy-100 mb-1">{{ $entry->value('role') }}</p>
                    <p class="meta-text text-xs mb-4">{{ $start }}{{ $end ? ' - ' . $end : '' }}</p>
                    <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-4">{{ $entry->value('summary') }}</p>
                    @if ($standout)
                        <p class="font-ui text-sm metric-highlight mb-6">{{ $standout }}</p>
                    @endif
                    <a href="{{ $entry->url() }}" class="font-ui text-sm mt-auto">See full history &rarr;</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
