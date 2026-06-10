{{--
    Effect 2 — Seattle footer reveal.
    min-h-screen + bg-fixed: content scrolls up to reveal the full skyline.
    site.js shifts background-position as the footer enters view; touch gets scroll fallback.
--}}
<footer
    class="relative min-h-screen bg-cover bg-no-repeat bg-fixed"
    style="background-image: url('/assets/photos/seattle-skyline.jpg'); background-position: center 25%;"
    data-footer
>
    {{-- Top fade only: blend scrolling page content into the skyline --}}
    <div class="absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-cowboy-950 via-cowboy-950/30 to-transparent pointer-events-none" aria-hidden="true"></div>

    {{-- Content anchored at the ¼ mark from the top of the reveal --}}
    <div class="relative z-10 w-full max-w-4xl mx-auto px-6 pt-[25vh] pb-16 text-center">

        <div class="footer-scrim mx-auto max-w-lg">
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
