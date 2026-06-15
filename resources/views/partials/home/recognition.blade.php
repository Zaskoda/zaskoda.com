<section class="bg-slate py-20 px-6 md:px-12 lg:px-16">
    <div class="max-w-7xl mx-auto">
        @include('partials.section-header', [
            'title' => 'Recognition',
            'action_label' => 'View all recognition',
            'action_url' => '/recognition',
        ])

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <article class="surface-card p-8 flex flex-col">
                <p class="font-ui text-xs uppercase text-copper-400 mb-3">Game Credit</p>
                <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-3">Dead Man's Hand</h3>
                <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-6">
                    A AAA western-themed FPS published by Atari on the Unreal Engine. Programmer credit
                    for enemy AI, UI, mini-games, animation scripting, and environmental effects.
                </p>
                <a href="/recognition#credits" class="font-ui text-sm mt-auto">&rarr; Credits</a>
            </article>

            <article class="surface-card p-8 flex flex-col">
                <p class="font-ui text-xs uppercase text-copper-400 mb-3">Grant</p>
                <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-3">Polygon Studios Grant</h3>
                <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-6">
                    $5,000 USD to bring Orbiter 8 to the Polygon network. Awarded March 2022.
                </p>
                <a href="/recognition#awards" class="font-ui text-sm mt-auto">&rarr; Awards &amp; Grants</a>
            </article>

            <article class="surface-card p-8 flex flex-col">
                <p class="font-ui text-xs uppercase text-copper-400 mb-3">Talk</p>
                <h3 class="font-ui font-semibold text-xl text-cowboy-100 mb-3">SXSW Interactive 2001</h3>
                <p class="font-ui text-sm text-cowboy-100 leading-relaxed mb-6">
                    Presented alongside Microsoft on applying game design principles to web development.
                    Part of the SXSW Interactive track, representing Gathering of Developers.
                </p>
                <a href="/recognition#talks" class="font-ui text-sm mt-auto">&rarr; Talks</a>
            </article>
        </div>
    </div>
</section>
