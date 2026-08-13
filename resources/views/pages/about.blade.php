@extends('layout')

@section('content')
{{-- Page header - Surface A --}}
<div class="bg-ink">
    <div class="max-w-3xl mx-auto px-6 pt-28 pb-6">
        <h1 class="page-heading">About</h1>
    </div>
</div>

@include('partials.surface-fade')

{{-- Page content - Surface B --}}
<div class="bg-slate">
<div class="max-w-3xl mx-auto px-6 pt-6 pb-20">
    <section class="mb-16">
        <h2 class="section-heading text-3xl">Rural Roots</h2>
        <div class="body-copy space-y-4">
            <p>
                I was born in rural Texas and spent my childhood on the move. My family lived in  
                half a dozen states before heading back to Texas for my high school years. I was
                busy raising horses, goats, and chickens when dad brought home our first computer, a 486dx33. 
                In my family home, which we built from scratch ourselves, I learned to build software and 
                discovered the online world. These were formative years.
            </p>

            <figure>
                <img
                    src="/assets/photos/me-on-horse-02.jpg"
                    alt="Scott on horseback in rural Texas"
                    class="inline-photo"
                    width="768"
                    height="470"
                >
            </figure>

            <p>
                My passion in high school was driven by a desire to make indie games, and I did. I created Splat, Bob and His 
                Amazing Journey Home, and The Legend of Talibah. Some of my games were featured on 
                Softdisk's CD-ROMs and even on a data CD included in an Information Society album. I released 
                shareware version on BBSes and, eventually, this new thing called the Internet. I called 
                my little indie game label Zaskoda Soft. 
            </p>

            <p>
                Although it seemed that building software was a passion for me, it took a lifetime of exploring
                to realize it wasn't. It was merely what I had to learn in order to express my creativity at 
                the time. It turns out, I enjoy crafting experiences for others to enjoy - in any medium. Early on this was 
                games and online communities. But in time, I followed this passion to create music, build art 
                installations, perform as a fire dancer, and craft unique organizational cultures.
            </p>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="section-heading text-3xl">Becoming &ldquo;Koda&rdquo;</h2>
        <div class="body-copy space-y-4">
            <p>
                The name Zaskoda came from my great-grandmother's maiden name. After discovering
                part of our family history and feeling a kinship to the name, I borrowed it as 
                online handle for all things ever since - from publishing DOS games and
                composing tracker music to my identity across social media and just 
                about anything else online. 
            </p>

            <figure>
                <img
                    src="/assets/photos/koda-at-burn.jpg"
                    alt="Koda petting a dragon art car at Burning Man"
                    class="inline-photo"
                    width="768"
                    height="421"
                >
            </figure>

            <p>
                Years later, a patner and I went to Burning Man where she referred to 
                me - for the first time - as a shortened version of the name: &ldquo;Koda&rdquo;. People overheard her, picked up on it, and decades 
                later, Koda is what most of my friends call me, and &ldquo;zaskoda&rdquo; is still my handle nearly everywhere online.
            </p>

            <figure>
                <img
                    src="/assets/photos/koda-firespinning.jpg"
                    alt="Koda fire spinning"
                    class="inline-photo"
                    width="768"
                    height="510"
                >
            </figure>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="section-heading text-3xl">Academic Detour</h2>
        <div class="body-copy space-y-4">
            <p>
                I have a BS in Computer Science from the University of North Texas, with a minor in
                English and Technical Writing. Highlights from undergrad include: President's List, 
                Dean's List, ACM member, and game development coursework in Ian Parberry's LARC lab.
            </p>
            <p>
                Years later I went back for an MS in Information and Communication Technology for
                Development from CU Boulder's ATLAS Institute (3.97 GPA). ICTD is an
                interdisciplinary program about how technology actually serves communities - not in
                the abstract, but in the field. Mine was the practical kind: I built field-data
                tools for Re:Vision, a Denver nonprofit fighting urban food deserts, and trained
                local women on the platform in two languages. I did a social entrepreneurship case
                study with Prospera, a Guadalajara nonprofit helping women become micro-entrepreneurs.
                I built and benchmarked a mesh network for last-mile connectivity in an
                under-resourced community and wrote it up as a measurement study. I studied distributed
                systems architecture and big data techniques where we mined large-scale Twitter data 
                about the Colorado floods that were happening around us at the time. I topped it all off 
                with a semester working directly with the Burning Man Project's technology team during their transition 
                from an LLC to a nonprofit. 
            </p>

            <figure>
                <img
                    src="/assets/photos/koda-and-odin-cropped.jpg"
                    alt="Koda and Odin"
                    class="inline-photo"
                    width="768"
                    height="479"
                >
            </figure>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="section-heading text-3xl">What I Believe</h2>
        <div class="body-copy space-y-4">
            <p>
                I'm a decentralist, philosophically and technically. I believe ownership of the
                Internet should be distributed, and I've been advocating for federated identity and
                open social systems most of my life. Centralized power eventually becomes corrupted by
                bad actors. It's a pattern that plays out time and time again. I believe that, ultimately,
                the best systems are decentralized and open.
            </p>
            <p>
                I'm egalitarian and politically independent. I care about authenticity more than 
                polish - in writing, in design, in behavior. I try
                to stay self-aware about my own contradictions and I'm always doing my best to
                grow into a better man. 
            </p>

            <figure>
                <img
                    src="/assets/photos/me-with-mohawk.jpg"
                    alt="Scott with a mohawk in front of system diagrams"
                    class="inline-photo"
                    width="768"
                    height="467"
                >
            </figure>

            <p>The picture above isn't a costume. It's just me embracing my cyberpunk vibes on a Wednesday 
                at Realtime Worlds. </p>
        </div>
    </section>

    <section class="mb-16">
        <h2 class="section-heading text-3xl">Beyond the Screen</h2>
        <div class="body-copy space-y-4">
            <p>
                I'm a fire poi performer. A granted festival artist. An occasional amateur actor. A
                HAM radio technician - callsign KI7APH. I served as president of Apogaea, Colorado's
                officially sanctioned regional Burning Man event, during its most successful year,
                and built the Temple of Moon there in 2011 - a memorial art installation in the
                shape of an infinity symbol, climbable, eventually burned at a ceremony, built with
                no prior large-scale art experience.
            </p>

            <figure>
                <img
                    src="/assets/photos/me-snowboarding.jpg"
                    alt="Scott snowboarding"
                    class="inline-photo"
                    width="768"
                    height="387"
                >
            </figure>

            <p>
                I've snowboarded an active volcano and managed to ride 56 days in a single season. 
                I've surfed a hurricane and flew a small plane over a glacier. I took my first diving trip at 17
                and am still an active diver. I also enjoy downhill mountain biking, backpacking, and overlanding.
            </p>

            <figure>
                <img
                    src="/assets/photos/me-in-italy.jpg"
                    alt="Scott in Italy"
                    class="inline-photo"
                    width="768"
                    height="427"
                >
            </figure>

            <p>
                I love to travel. These days I split my time between Seattle, Washington and Merida, Yucatan. You'll 
                often find me visiting friends and community in Texas, Colorado, California, and beyond. Or, I might 
                just be off on an adventure somewhere else.
            </p>
        </div>
    </section>

</div>
</div>
@endsection
