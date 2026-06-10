@php
    $featuredWork = \Statamic\Facades\Entry::query()
        ->where('collection', 'work')
        ->where('published', true)
        ->where('featured', true)
        ->orderBy('start_date', 'desc')
        ->limit(3)
        ->get();
@endphp

<section class="bg-cowboy-950 py-20 px-6 md:px-12 lg:px-16">
    <div class="max-w-7xl mx-auto">
        <h2 class="font-display font-bold text-4xl md:text-5xl text-cowboy-100 mb-3 border-b border-circuit-500/40 pb-3">Selected Work</h2>

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

        <div class="mt-10">
            <a href="/work" class="font-ui text-sm">See all work &rarr;</a>
        </div>
    </div>
</section>
