<section class="bg-slate py-20 px-6 md:px-12 lg:px-16">
    <div class="max-w-7xl mx-auto">
        @include('partials.section-header', [
            'title' => 'Recognition',
            'action_label' => 'View all recognition',
            'action_url' => '/recognition',
        ])

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <article class="surface-card p-8 flex flex-col">
                <svg class="w-8 h-8 text-copper-500 mb-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 11h4m4 0h4M6 11V7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4M6 11v4a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-4M7 15h1v4H7v-4m10 0h1v4h-1v-4"/>
                </svg>
                <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-3">Dead Man's Hand</h3>
                <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-6">
                    A AAA western FPS published by Atari on the Unreal Engine. Programmer credit
                    for enemy AI, UI, mini-games, animation scripting, and environmental effects.
                </p>
                <a href="/recognition#credits" class="font-ui text-sm mt-auto">View all credits &rarr;</a>
            </article>

            <article class="surface-card p-8 flex flex-col">
                <svg class="w-8 h-8 text-copper-500 mb-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.726 6.726 0 0 1-3.044 0"/>
                </svg>
                <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-3">Polygon Studios Grant</h3>
                <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-6">
                    $5,000 USD awarded to bring Orbiter 8 to the Polygon network. March 2022.
                </p>
                <a href="/recognition#awards" class="font-ui text-sm mt-auto">View all awards &rarr;</a>
            </article>

            <article class="surface-card p-8 flex flex-col">
                <svg class="w-8 h-8 text-copper-500 mb-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z"/>
                </svg>
                <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-3">SXSW Interactive 2001</h3>
                <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-6">
                    Presented alongside Microsoft on applying game design principles to web development.
                    Twenty-five years ago at SXSW Interactive, representing Gathering of Developers.
                </p>
                <a href="/recognition#talks" class="font-ui text-sm mt-auto">View all talks &rarr;</a>
            </article>
        </div>
    </div>
</section>
