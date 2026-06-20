@extends('layout')

@section('content')
{{-- Page header — Surface A --}}
<div class="bg-ink">
    <div class="max-w-3xl mx-auto px-6 pt-28 pb-12">
        <h1 class="page-heading">About</h1>
    </div>
</div>

@include('partials.surface-fade')

{{-- Page content — Surface B --}}
<div class="bg-slate">
<div class="max-w-3xl mx-auto px-6 pt-12 pb-20">
    <section class="mb-16">
        <h2 class="section-heading text-3xl">The Cyberpunk Cowboy</h2>
        <div class="body-copy space-y-4">
            <p>
                I grew up in rural Texas with horses, goats, and chickens. My family built the home
                we lived in during my high school years, from scratch, ourselves. Somewhere in the
                middle of that I got my first computer: a 486dx33 that could be overclocked to 66mhz
                with the press of a button. I immediately got online — posting shareware projects to
                BBSes, browsing Usenet, and building websites when Mosaic was mainstream.
            </p>

            <figure>
                <img
                    src="/assets/photos/koda-and-odin-cropped.jpeg"
                    alt="Scott Dudley with Odin, his dog"
                    class="inline-photo"
                    width="2039"
                    height="1272"
                >
            </figure>

            <p>
                I started publishing DOS games and composing tracker music in high school under the
                name Zaskoda Soft, a name borrowed from my great-grandmother's maiden name. My first
                weekly Web updates went online in August 1996, before anyone called that blogging —
                I was writing about indie game development long before the term existed, and eventually
                worked on AAA titles too. I've launched three companies along the way: a pre-dot-com-bubble
                web development firm, a social media marketing studio, and a Web3 game studio. I've been
                building on the Internet ever since: game studio web platforms, communities with hundreds
                of thousands of members, goofy robots, blockchain games, a climbable art installation
                we burned at a ceremony, and a 12-year van build.
            </p>
            <p>
                High-tech and natural. Digital and handmade. I've lived both ends of it: decades
                writing software in Seattle, and these days splitting my time between Seattle and
                Yucatan, Mexico.
            </p>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="section-heading text-3xl">Academic Background</h2>
        <div class="body-copy space-y-4">
            <p>
                BS in Computer Science from the University of North Texas, with a minor in English
                and Technical Writing. President's List, Dean's List, ACM member, and game
                development coursework in Ian Parberry's LARC lab.
            </p>

            <figure>
                <img
                    src="/assets/photos/home-hosting.jpg"
                    alt="Scott hosting a community workshop"
                    class="inline-photo"
                    width="800"
                    height="533"
                >
                <figcaption class="inline-photo-caption">Community work in the field — training, hosting, building together.</figcaption>
            </figure>

            <p>
                MS in Information and Communication Technology for Development from the University
                of Colorado Boulder's ATLAS Institute, 3.97 GPA. ICTD is an interdisciplinary
                program about how technology serves communities. Mine was the practical kind: field
                tools for Re:Vision, a Denver nonprofit addressing the urban food desert problem,
                where I deployed offline data collection and trained local women on the platform in
                two languages. A social entrepreneurship case study with Prospera, a Guadalajara
                nonprofit helping women become micro-entrepreneurs. And a practicum with the Burning
                Man Project's technology team during its transition to a nonprofit. I also built and
                benchmarked a mesh network for last-mile connectivity and mined large-scale Twitter
                data from the Colorado floods.
            </p>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="section-heading text-3xl">Values</h2>
        <div class="body-copy space-y-4">
            <p>
                Decentralist, philosophically and technically. I believe ownership of the Internet
                should be distributed, and I've been advocating for federated identity and open
                social systems since 2005. I'm skeptical of centralized tech power and wrote
                publicly about leaving Facebook in 2019, then built my own federated address book
                because the software I wanted didn't exist.
            </p>
            <p>
                Egalitarian. Politically independent. I value authenticity over polish, in writing
                and in behavior, and I try to stay self-aware about my own contradictions.
            </p>

            <figure>
                <img
                    src="/assets/photos/home-hawk.jpg"
                    alt="A hawk perched outdoors in rural country"
                    class="inline-photo"
                    width="800"
                    height="533"
                >
                <figcaption class="inline-photo-caption">Rural roots — high-tech and natural, from the start.</figcaption>
            </figure>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="section-heading text-3xl">Beyond the Screen</h2>
        <div class="body-copy space-y-4">
            <p>
                Fire poi performer. Granted festival artist. Occasional amateur actor. HAM radio
                technician, callsign KI7APH. I served as president of Apogaea, Colorado's regional
                Burning Man event, during its most successful year, and built the Temple of Moon
                there in 2011.
            </p>

            <figure>
                <img
                    src="/assets/photos/home-performance.jpg"
                    alt="Scott performing with fire poi at a festival"
                    class="inline-photo"
                    width="800"
                    height="533"
                >
                <figcaption class="inline-photo-caption">Festival life — fire, art, and community off the clock.</figcaption>
            </figure>

            <p>
                I've documented 24 snowboarding trips, surfed, dived, and ridden downhill mountain
                bikes. These days I'm living in Mexico, a long way from Silicon Valley and arguably
                a return to my rural roots in a different form.
            </p>
        </div>
    </section>

    <section>
        <h2 class="section-heading text-3xl">Find Me</h2>
        <p class="body-copy mb-6">
            Email is best: <a href="mailto:{{ $site_settings['contact_email'] }}">{{ $site_settings['contact_email'] }}</a>.
            Otherwise I'm zaskoda just about everywhere.
        </p>
        @include('partials.social-icons', ['class' => 'gap-5', 'iconClass' => 'w-6 h-6', 'iconSize' => 24])
    </section>
</div>
</div>
@endsection
