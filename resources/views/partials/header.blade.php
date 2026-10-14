<header class=" sticky top-0 z-50 border-b bg-white/80 backdrop-blur">
    <div class="container h-14 flex items-center justify-between">
        <a href="" class="font-extrabold text-[var(--accent)]">Aptbelu.eu</a>
        <button
            id="nav-toggle"
            class="inline-flex items-center justify-center rounded-md p-2 md:hidden"
            aria-label="Otevřít menu"
        >
            <div class="hamburger space-y-1.5">
                <span class="hamburger-line block h-0.5 w-6 bg-slate-900"></span>
                <span class="hamburger-line block h-0.5 w-6 bg-slate-900"></span>
                <span class="hamburger-line block h-0.5 w-6 bg-slate-900"></span>
            </div>
        </button>

        <div id="nav-menu"
            class="absolute left-0 right-0 top-14 flex flex-col items-center justify-center gap-7 bg-white px-6 text-lg font-medium
             opacity-0 pointer-events-none md:hidden">
            <a href="#about-us" class="items-center h-16 leading-none">O nás</a>
            <a href="#gallery"  class="items-center h-16 leading-none">Galerie</a>
            <a href="#price-list"  class="items-center h-16 leading-none">Ceník</a>
{{--            <a href="#calendar" class="items-center h-16 leading-none">Kalendář</a>--}}
            <a href="#location" class="items-center h-16 leading-none">Lokalita</a>
            <a href="#contact"  class="items-center h-16 leading-none">Kontakt</a>
        </div>

        <div class="flex items-center gap-4 text-sm hidden md:block">
            <a href="#about-us" class="nav-link font-semibold">O nás</a>
            <a href="#gallery" class="nav-link font-semibold md:ml-3">Galerie</a>
            <a href="#price-list" class="nav-link font-semibold md:ml-3">Ceník</a>
{{--            <a href="#calendar" class="nav-link font-semibold">Kalendář</a>--}}
            <a href="#location" class="nav-link font-semibold md:ml-3">Lokalita</a>
            <a href="#contact" class="nav-link font-semibold md:ml-3">Kontakt</a>
        </div>
    </div>
</header>

<script>
    const btn = document.getElementById('nav-toggle');
    const menu = document.getElementById('nav-menu');
    const links = menu.querySelectorAll('a');

    btn.addEventListener('click', () => {
        menu.classList.toggle('open');
        btn.classList.toggle('open');
    });

    links.forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.remove('open');
            btn.classList.remove('open');
        });
    });
</script>
