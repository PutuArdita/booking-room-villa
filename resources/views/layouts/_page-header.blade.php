<section class="bg-brand-900 py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 lg:px-6">
        <h1 data-reveal class="font-display text-3xl font-semibold text-white sm:text-4xl">{{ $title }}</h1>
        @isset($subtitle)
            <p data-reveal style="--reveal-delay: 100ms" class="mt-2 max-w-2xl text-brand-100">{{ $subtitle }}</p>
        @endisset
    </div>
</section>