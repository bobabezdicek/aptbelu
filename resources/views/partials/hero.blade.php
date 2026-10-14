<section class="relative w-full h-[85vh] overflow-hidden">
    <img src="{{ asset('images/hero-apartment.jpg') }}" alt="Luxusní apartmán" class="absolute inset-0 w-full h-full object-cover [object-position:50%_35%] md:[object-position:50%_45%]">

    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-transparent"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-40
              bg-gradient-to-b from-transparent via-white/70 to-white"></div>

    <div class="relative z-10 flex flex-col items-center justify-center text-center h-full text-white px-4">
        <h1 class="text-5xl md:text-7xl font-extrabold mb-4 drop-shadow-lg">
            Luxusní<br>Apartmán
        </h1>
        <p class="max-w-2xl text-base md:text-lg text-white/90 font-semibold">
            Objevte dokonalé spojení elegance a pohodlí v srdci města. <br>
            Plně vybavený apartmán s moderním designem pro nezapomenutelný pobyt.
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-4">
{{--            <a href="#gallery" class="btn btn-ghost">Podívat se dovnitř</a>--}}
{{--            <a href="#calendar" class="btn btn-primary">Zjistit dostupnost</a>--}}
            <a href="#gallery" class="btn btn-primary">Podívat se dovnitř</a>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-[110px] pointer-events-none hero-fade"></div>
</section>
