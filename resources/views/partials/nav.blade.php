@php
    $activeSection = request()->segment(1);

    $navLinks = [
        ['label' => 'Work', 'url' => '/work', 'section' => 'work'],
        ['label' => 'Projects', 'url' => '/projects', 'section' => 'projects'],
        ['label' => 'Recognition', 'url' => '/recognition', 'section' => 'recognition'],
        ['label' => 'Blog', 'url' => '/blog', 'section' => 'blog'],
        ['label' => 'About', 'url' => '/about', 'section' => 'about'],
        ['label' => 'Contact', 'url' => '/contact', 'section' => 'contact'],
    ];
@endphp

<nav id="site-nav" class="fixed top-0 w-full z-50 bg-ink/90 backdrop-blur-sm border-b border-card-border">
    {{-- Full-bleed header: inner container is wider than the page content column --}}
    <div class="max-w-[96rem] mx-auto px-6 flex items-center justify-between h-14">

        {{-- Logo: zas light, Koda bold, .com light --}}
        <a href="/" class="flex items-baseline leading-none hover:no-underline">
            <span class="font-ui font-light text-cowboy-500 text-lg">zas</span>
            <span class="font-display font-bold text-cowboy-100 text-2xl tracking-tight">Koda</span>
            <span class="font-ui font-light text-cowboy-500 text-base">.com</span>
        </a>

        {{-- Desktop links --}}
        <div class="hidden md:flex items-center gap-1">
            @foreach ($navLinks as $link)
                @php $isActive = $activeSection === $link['section']; @endphp
                <a
                    href="{{ $link['url'] }}"
                    class="nav-link {{ $isActive ? 'nav-link--active' : '' }}"
                    @if ($isActive) aria-current="page" @endif
                >{{ $link['label'] }}</a>
            @endforeach
        </div>

        {{-- Desktop social icons --}}
        <div class="hidden lg:block">
            @include('partials.social-icons')
        </div>

        {{-- Mobile hamburger --}}
        <button class="md:hidden text-cowboy-300 hover:text-copper-400 transition-colors duration-200" data-nav-toggle aria-label="Menu" aria-expanded="false">
            <svg class="w-6 h-6" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    {{-- Mobile dropdown --}}
    <div class="hidden md:hidden bg-ink border-t border-card-border px-6 py-6 space-y-1" data-nav-menu>
        @foreach ($navLinks as $link)
            @php $isActive = $activeSection === $link['section']; @endphp
            <a
                href="{{ $link['url'] }}"
                class="nav-link-mobile {{ $isActive ? 'nav-link-mobile--active' : '' }}"
                @if ($isActive) aria-current="page" @endif
            >{{ $link['label'] }}</a>
        @endforeach
        <div class="pt-4">
            @include('partials.social-icons', ['class' => 'gap-4', 'iconClass' => 'w-5 h-5', 'iconSize' => 20])
        </div>
    </div>
</nav>
