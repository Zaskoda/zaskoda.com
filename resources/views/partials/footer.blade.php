{{--
    Effect 2 — Seattle footer reveal.
    min-h-screen + bg-fixed: content scrolls up to reveal the full skyline.
    site.js shifts background-position as the footer enters view; touch gets scroll fallback.
    The photo stays clearly visible — only a top gradient blends in the page content,
    and the footer content sits in a contained card near the bottom.
--}}
<footer
    class="relative min-h-screen bg-cover bg-no-repeat bg-fixed"
    style="background-image: url('/assets/photos/seattle-skyline.jpg'); background-position: center 25%;"
    data-footer
>
    {{-- Top fade only: blend scrolling page content into the skyline --}}
    <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-ink to-transparent pointer-events-none" aria-hidden="true"></div>

    {{-- Content card anchored near the bottom of the reveal --}}
    <div class="relative z-10 min-h-screen flex items-end justify-center px-6 pt-[40vh] pb-12 md:pb-16">
        <div class="w-full max-w-2xl bg-ink/85 backdrop-blur-sm border border-card-border rounded-lg px-8 py-10 md:px-12 text-center">
            <p class="font-body italic text-cowboy-100 text-lg leading-relaxed whitespace-pre-line">
                {{ $site_settings['tagline'] ?? '' }}
            </p>

            <nav class="flex flex-wrap justify-center gap-6 mt-10 mb-8">
                <a href="/work"     class="font-ui text-sm text-cowboy-100 hover:text-copper-400 transition-colors">Work</a>
                <a href="/projects" class="font-ui text-sm text-cowboy-100 hover:text-copper-400 transition-colors">Projects</a>
                <a href="/blog"     class="font-ui text-sm text-cowboy-100 hover:text-copper-400 transition-colors">Blog</a>
                <a href="/about"    class="font-ui text-sm text-cowboy-100 hover:text-copper-400 transition-colors">About</a>
                <a href="/contact"  class="font-ui text-sm text-cowboy-100 hover:text-copper-400 transition-colors">Contact</a>
            </nav>

            <div class="flex justify-center mb-6">
                @include('partials.social-icons', ['class' => 'gap-4', 'iconClass' => 'w-5 h-5', 'iconSize' => 20, 'linkClass' => 'text-cowboy-100 hover:text-copper-400'])
            </div>

            <p class="font-ui text-xs text-cowboy-500">&copy; {{ date('Y') }} zasKoda</p>
        </div>
    </div>
</footer>
