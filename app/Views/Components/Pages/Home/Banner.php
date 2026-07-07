<section class="section">
    <div class="container-app">
        <div class="relative overflow-hidden rounded-3xl bg-premium-panel p-2.5 sm:p-3.5">
            <div class="premium-glow premium-glow-primary -left-16 -top-16 h-64 w-64"></div>
            <div class="premium-glow premium-glow-secondary -bottom-20 -right-10 h-56 w-56"></div>
            <div class="premium-dot-pattern"></div>

            <div class="banner-swiper swiper relative z-10 overflow-hidden rounded-2xl shadow-card">
                <div class="swiper-wrapper">
                    <?php foreach ($banners as $banner) : ?>
                        <?php
                            $image = !empty($banner['image'])
                                ? base_url('assets/images/banner/' . $banner['image'])
                                : 'https://placehold.co/1600x600';
                        ?>
                        <div class="swiper-slide banner-slide">
                            <?php if (!empty($banner['link'])) : ?>
                                <a
                                    href="<?= esc($banner['link']) ?>"
                                    class="block h-full w-full"
                                >
                                    <img
                                        src="<?= esc($image) ?>"
                                        alt="Banner Promo"
                                        class="banner-image"
                                        loading="lazy"
                                    >
                                </a>
                            <?php else : ?>
                                <img
                                    src="<?= esc($image) ?>"
                                    alt="Banner Promo"
                                    class="banner-image"
                                    loading="lazy"
                                >
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>