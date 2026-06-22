<section class="bg-slate py-16 md:py-20 px-6 md:px-12 lg:px-16">
    <div class="max-w-3xl mx-auto text-center">

        <a
            href="/about"
            aria-label="About Scott Dudley"
            class="group relative block w-44 h-44 mx-auto mb-10 rounded-full border-2 border-cowboy-600 overflow-hidden hover:border-copper-400 transition-colors"
        >
            <img
                src="/assets/photos/me-08.png"
                alt="Scott Dudley"
                class="w-full h-full object-cover scale-110 transition-transform duration-300 ease-out group-hover:scale-100"
            >
        </a>

        <h2 class="font-display font-extrabold text-5xl md:text-7xl text-cowboy-100 tracking-wide mb-8">
            Cyberpunk Cowboy
        </h2>

        <p class="font-body text-lg md:text-xl text-cowboy-100 leading-relaxed max-w-xl mx-auto mb-4">
            I'm Scott — but my friends call me Koda.   
        </p>          
        <p class="font-body text-lg md:text-xl text-cowboy-100 leading-relaxed max-w-xl mx-auto mb-10">
            <a href="/about">Find out more about me...</a>            
        </p>

        <div class="flex justify-center">
            @include('partials.social-icons', ['class' => 'gap-5', 'iconClass' => 'w-6 h-6', 'iconSize' => 24])
        </div>
    </div>
</section>
