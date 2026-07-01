<?php if (!empty($category_sections)) : ?>
    <section
        class="section"
        x-data="categoryTabs()"
    >
        <div class="container-app">
            <div class="section-title">
                <div>
                    <h2 class="flex items-center gap-2">
                        <i class="bi bi-grid-fill text-primary"></i>
                        Kategori Game
                    </h2>

                    <p>
                        Pilih kategori buat nemuin game favorit kamu.
                    </p>
                </div>
            </div>

            <div class="category-tabs">
                <?php foreach ($category_sections as $index => $section) : ?>
                    <?php $category = $section['category']; ?>
                    <button
                        type="button"
                        class="category-tab"
                        :class="{ 'is-active': active === <?= $index ?> }"
                        @click="active = <?= $index ?>"
                    >
                        <?php if (!empty($category['image'])) : ?>
                            <img
                                src="<?= base_url('assets/images/games/categories/' . $category['image']) ?>"
                                alt=""
                                loading="lazy"
                            >
                        <?php endif ?>

                        <?= esc($category['category']) ?>
                    </button>
                <?php endforeach ?>
            </div>

            <?php foreach ($category_sections as $index => $section) : ?>
                <div
                    x-show="active === <?= $index ?>"
                    x-cloak
                >
                    <div class="games-grid">
                        <?php foreach ($section['games'] as $game) : ?>
                            <?php
                                $image = !empty($game['image'])
                                    ? base_url('assets/images/games/icons/' . $game['image'])
                                    : 'https://placehold.co/300x400';
                            ?>
                            <a
                                href="<?= base_url('games/' . $game['slug']) ?>"
                                class="game-card"
                            >
                                <div class="game-image">
                                    <img
                                        src="<?= esc($image) ?>"
                                        alt="<?= esc($game['games']) ?>"
                                        loading="lazy"
                                    >

                                    <div class="game-overlay">
                                        <h3>
                                            <?= esc($game['games']) ?>
                                        </h3>

                                        <?php if (!empty($game['publisher'])) : ?>
                                            <p>
                                                <?= esc($game['publisher']) ?>
                                            </p>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </section>
<?php endif ?>