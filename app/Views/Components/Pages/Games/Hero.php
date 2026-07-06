<div class="game-hero">
    <img
        src="<?= !empty($game['banner']) ? base_url('assets/images/games/banners/' . $game['banner']) : 'https://placehold.co/1200x514' ?>"
        alt="<?= esc($game['games']) ?>"
        loading="lazy"
    >
    <div class="game-hero-scrim"></div>

    <div class="game-hero-content">
        <div class="game-hero-icon">
            <img
                src="<?= !empty($game['image']) ? base_url('assets/images/games/icons/' . $game['image']) : 'https://placehold.co/200x200' ?>"
                alt=""
                loading="lazy"
            >
        </div>

        <div class="game-hero-title">
            <h1><?= esc($game['games']) ?></h1>
            <p>
                <?= esc($game['publisher'] ?? '') ?>
                <?php if (!empty($game['category'])) : ?>
                    &middot; <?= esc($game['category']) ?>
                <?php endif ?>
            </p>
        </div>
    </div>
</div>
