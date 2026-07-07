<?php if (!empty($flashsale)) : ?>
    <section class="section">
        <div class="container-app">
            <div class="flashsale-banner relative mb-6 overflow-hidden rounded-3xl bg-premium-panel p-5 sm:p-7">
                <div class="premium-glow premium-glow-secondary -right-10 -top-16 h-56 w-56"></div>
                <div class="premium-glow premium-glow-primary -bottom-16 -left-10 h-48 w-48"></div>
                <div class="premium-dot-pattern"></div>

                <div class="section-title relative z-10 mb-0">
                    <div>
                        <h2 class="flex items-center gap-2 text-white">
                            <i class="bi bi-lightning-charge-fill text-secondary"></i>
                            <?= esc($flashsale['title']) ?>
                        </h2>

                        <p>
                            <?= esc($flashsale['description']) ?>
                        </p>
                    </div>

                    <div
                        class="flashsale-countdown"
                        x-data="flashsaleCountdown(<?= $flashsale['remaining_seconds'] ?>)">
                        <div class="countdown-box">
                            <span x-text="days"></span>
                            <small>Hari</small>
                        </div>

                        <div class="countdown-box">
                            <span x-text="hours"></span>
                            <small>Jam</small>
                        </div>

                        <div class="countdown-box">
                            <span x-text="minutes"></span>
                            <small>Menit</small>
                        </div>

                        <div class="countdown-box">
                            <span x-text="seconds"></span>
                            <small>Detik</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flashsale-swiper swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($flashsale['products'] as $product) : ?>
                        <?php
                        $salePrice = $product['discount_type'] === 'fixed'
                            ? $product['price'] - $product['discount_value']
                            : $product['price'] - (($product['price'] * $product['discount_value']) / 100);

                        $progress = $product['stock'] > 0
                            ? min(100, ($product['sold'] / $product['stock']) * 100)
                            : 0;
                        ?>
                        <div class="swiper-slide">
                            <a
                                href="<?= base_url('games/' . $product['slug']) ?>"
                                class="flashsale-card card card-hover">
                                <div class="flashsale-image">
                                    <img
                                        src="<?= base_url('assets/images/games/icons/' . $product['game_image']) ?>"
                                        alt="<?= esc($product['game_name']) ?>">
                                    <?php if ($product['discount_type'] === 'fixed') : ?>
                                        <span class="discount-badge">
                                            -Rp <?= number_format($product['discount_value'], 0, ',', '.') ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="discount-badge">
                                            -<?= $product['discount_value'] ?>%
                                        </span>
                                    <?php endif ?>
                                </div>
                                <div class="flashsale-body">
                                    <h3>
                                        <?= esc($product['game_name']) ?>
                                    </h3>
                                    <p>
                                        <?= esc($product['product']) ?>
                                    </p>
                                    <div class="flashsale-price">
                                        <strong>
                                            Rp <?= number_format($salePrice, 0, ',', '.') ?>
                                        </strong>
                                        <del>
                                            Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                        </del>
                                    </div>
                                    <div class="flashsale-progress">
                                        <div
                                            class="flashsale-progress-bar"
                                            style="width: <?= $progress ?>%"></div>
                                    </div>
                                    <span class="flashsale-sold">
                                        <?= number_format($product['sold']) ?>
                                        Terjual
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="swiper-pagination flashsale-pagination"></div>
            </div>
        </div>
    </section>
<?php endif ?>