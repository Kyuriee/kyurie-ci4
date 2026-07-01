<?php if (!empty($popular_games)) : ?>
    <section class="section">
        <div class="container-app">
            <div class="section-title">
                <div>
                    <h2 class="flex items-center gap-2">
                        <i class="bi bi-fire text-secondary"></i>
                        Game Populer
                    </h2>

                    <p>
                        Top up game favorit dengan proses cepat &amp; aman.
                    </p>
                </div>

                <a
                    href="<?= base_url('games') ?>"
                    class="popular-games-link"
                >
                    Lihat Semua
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="popular-games-grid">
                <?php foreach ($popular_games as $game) : ?>
                    <?php
                        $image = !empty($game['image'])
                            ? base_url('assets/images/games/icons/' . $game['image'])
                            : 'https://placehold.co/300x300';
                    ?>
                    <a
                        href="<?= base_url('games/' . $game['slug']) ?>"
                        class="popular-game-card card card-hover"
                    >
                        <div class="popular-game-image">
                            <img
                                src="<?= esc($image) ?>"
                                alt="<?= esc($game['games']) ?>"
                                loading="lazy"
                            >
                        </div>
                        <div class="popular-game-body">
                            <h3>
                                <?= esc($game['games']) ?>
                            </h3>
                            <?php if (!empty($game['publisher'])) : ?>
                                <p>
                                    <?= esc($game['publisher']) ?>
                                </p>
                            <?php endif ?>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </section>
<?php endif ?>