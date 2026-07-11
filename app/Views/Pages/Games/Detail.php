<?= $this->extend('Layouts/Main'); ?>

<?= $this->section('content'); ?>
<div
    x-data="gameDetail('<?= esc($game['slug'], 'js') ?>', <?= esc(json_encode($products), 'attr') ?>, <?= esc(json_encode($target_form), 'attr') ?>, <?= esc(json_encode($payment_methods), 'attr') ?>, <?= esc(json_encode($auth_contact), 'attr') ?>)"
    class="section"
>
    <div class="container-app">
        <?= view('Components/Pages/Games/Hero', ['game' => $game]) ?>

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                <?= view('Components/Pages/Games/TargetForm', ['target_form' => $target_form]) ?>
                <?= view('Components/Pages/Games/ProductSelector', ['products' => $products]) ?>
                <?= view('Components/Pages/Games/PaymentMethods', ['payment_methods' => $payment_methods]) ?>
            </div>

            <div class="lg:col-span-1">
                <?= view('Components/Pages/Games/OrderSummary', ['game' => $game]) ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
