<section class="section">
    <div class="container-app">
        <div class="banner-swiper swiper card overflow-hidden rounded-3xl">
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
</section>