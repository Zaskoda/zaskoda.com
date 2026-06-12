{{--
    Effect 1 — Hero parallax + reflection.
    The parallax layer holds the photo and its mirrored reflection so they move as one.
    The vignette and bottom fade are STATIC siblings anchored to the section, so the
    fade always meets the hero/identity seam regardless of scroll position — the moving
    layer simply slides underneath them. The photo translates down at 0.6x scroll, which
    makes the image appear to scroll at ~40% of page speed.
--}}
@php $reflectionH = '14rem'; @endphp

<section class="relative overflow-hidden bg-ink" data-hero>

    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div
            class="absolute inset-x-0 top-0 will-change-transform"
            style="height: calc(100vh + {{ $reflectionH }});"
            data-hero-parallax
        >
            {{-- Main hero photo: overscanned ~1.15 so edges never show during parallax --}}
            <div
                class="absolute inset-x-0 top-0 bg-cover bg-center"
                style="height: 100vh; background-image: url('/assets/photos/hero-alaska-boat.jpg'); transform: scale(1.15); transform-origin: bottom center;"
            ></div>

            {{-- Mirrored reflection directly beneath the photo's bottom edge --}}
            <div
                class="absolute inset-x-0 bg-cover opacity-40"
                style="top: 100vh; height: {{ $reflectionH }}; background-image: url('/assets/photos/hero-alaska-boat.jpg'); background-position: center bottom; transform: scale(1.15) scaleY(-1); transform-origin: top center;"
            ></div>
        </div>

        {{-- Static top vignette --}}
        <div class="absolute inset-x-0 top-0 h-[30vh] bg-gradient-to-b from-ink/25 to-transparent"></div>

        {{-- Static bottom fade into Surface A: anchored to the section bottom so the
             transition into the identity block is seamless at every scroll position --}}
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-b from-transparent via-ink/50 to-ink"
             style="height: calc({{ $reflectionH }} + 6rem);"></div>
    </div>

    {{-- Text block: near top on mobile; md+ inset 10% from top, 5–10% from right --}}
    <div class="relative z-10 h-screen px-6 md:px-12 lg:px-16">
        <div class="flex h-full flex-col items-center pt-[11vh] md:items-end md:pt-[10%] md:pr-[5%] lg:pr-[10%]">
            <div class="flex flex-col items-center text-center md:w-fit md:items-start md:text-left">
                <h1 class="font-display font-extrabold text-6xl md:text-7xl lg:text-8xl text-cowboy-50 mb-3 drop-shadow-[0_2px_12px_rgba(0,0,0,0.8)]">
                    Hello Internet,
                </h1>
                <p class="font-ui text-xl md:text-2xl text-cowboy-100 mb-10 drop-shadow-[0_2px_8px_rgba(0,0,0,0.7)]">
                    This is my website.
                </p>

                <a
                    href="https://www.flickr.com/photos/zaskoda/2623298793/"
                    class="group relative w-20 h-20 will-change-transform"
                    title="I made this boat on a trip to Alaska. Watch it sail."
                    target="_blank"
                    rel="noopener"
                    data-hero-play
                >
                    <svg viewBox="0 0 80 80" class="w-full h-full text-cowboy-50 group-hover:text-copper-400 transition-colors drop-shadow-lg" aria-hidden="true">
                        <circle cx="40" cy="40" r="38" stroke="currentColor" stroke-width="2" fill="rgba(0,0,0,0.35)" opacity="0.9"/>
                        <polygon points="32,24 60,40 32,56" fill="currentColor" opacity="0.95"/>
                    </svg>
                    <span class="sr-only">I made this boat on a trip to Alaska. Watch it sail.</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Layout spacer: the reflection renders in the parallax layer behind this area --}}
    <div class="relative pointer-events-none -mb-px" style="height: {{ $reflectionH }};" aria-hidden="true"></div>

</section>
