{{--
    Hero — structural composition with old-site parallax.
    site.js pans the photo's background-position down at 1/3 scroll speed (the
    exact mechanism the 2016 theme used), so the photo appears to scroll slower
    than the page. The gap this opens above the image grows slower than the hero
    leaves the viewport, so it can never become visible — no overscan needed.
    Solid translucent bars top (nav) and bottom (caption) frame the photo.
--}}
<section class="relative bg-ink" data-hero style="height: 85svh;">

    <div
        class="absolute inset-0 bg-cover bg-no-repeat"
        style="background-image: url('/assets/photos/hero-alaska-boat.jpg'); background-position: center 35%;"
        data-hero-bg
        role="img"
        aria-label="A hand-carved wooden boat with a feather sail, held up at the shore in Alaska"
    ></div>

    {{-- Text block: 10% from the top when centered (clear of the boat); vertically
         centered over the open water right of center at lg+ --}}
    <div class="relative z-10 flex h-full flex-col items-center justify-start pt-[8.5svh] lg:justify-center lg:pt-0 lg:pb-12 lg:ml-[38%] lg:w-1/2">
        <div class="flex flex-col items-center text-center px-6">
            <h1 class="font-display font-extrabold text-6xl md:text-7xl lg:text-8xl text-cowboy-50 mb-3 drop-shadow-[0_2px_12px_rgba(0,0,0,0.8)]">
                Hello Internet,
            </h1>
            <p class="font-ui text-xl md:text-2xl text-cowboy-100 mb-10 drop-shadow-[0_2px_8px_rgba(0,0,0,0.7)]">
                This is my website.
            </p>

            <a
                href="https://www.flickr.com/photos/zaskoda/2623298793/"
                class="group relative w-20 h-20"
                title="I made this boat on a trip to Alaska. Watch it sail."
                target="_blank"
                rel="noopener"
            >
                <svg viewBox="0 0 80 80" class="w-full h-full text-cowboy-50 group-hover:text-copper-400 transition-colors drop-shadow-lg" aria-hidden="true">
                    <circle cx="40" cy="40" r="38" stroke="currentColor" stroke-width="2" fill="rgba(0,0,0,0.35)" opacity="0.9"/>
                    <polygon points="32,24 60,40 32,56" fill="currentColor" opacity="0.95"/>
                </svg>
                <span class="sr-only">I made this boat on a trip to Alaska. Watch it sail.</span>
            </a>
        </div>
    </div>

    {{-- Solid translucent caption bar: structural bottom edge, mirrors the nav bar --}}
    <div class="absolute inset-x-0 bottom-0 z-10 bg-ink/90 backdrop-blur-sm border-t border-card-border">
        <p class="py-4 px-6 text-center font-ui text-base text-cowboy-300">
            &ldquo;Koda&rdquo; is <span class="font-semibold text-cowboy-100">Scott William Dudley</span>
        </p>
    </div>

</section>
