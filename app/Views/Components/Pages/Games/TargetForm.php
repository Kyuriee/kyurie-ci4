<?php
$targetInputs = $target_form['inputs'] ?? [];
$targetGridClass = count($targetInputs) > 1 ? 'target-input-grid' : '';
?>
<div>
    <div class="section-title mb-4">
        <div>
            <h2 class="text-lg">1. Masukkan ID Akun</h2>
            <p>Cek ulang ID kamu biar item masuk ke akun yang benar.</p>
        </div>
    </div>

    <div class="<?= esc($targetGridClass, 'attr') ?>">
        <?php foreach ($targetInputs as $input) : ?>
            <div>
                <label for="target_<?= esc($input['key'], 'attr') ?>" class="mb-1.5 block text-sm font-semibold text-heading">
                    <?= esc($input['label']) ?>
                </label>
                <?php $inputType = $input['type'] ?? 'text'; ?>
                <?php if ($inputType === 'select') : ?>
                    <select
                        x-model="targetValues['<?= esc($input['key'], 'js') ?>']"
                        @change="schedulePreview()"
                        id="target_<?= esc($input['key'], 'attr') ?>"
                        name="<?= esc($input['name'], 'attr') ?>"
                        class="input"
                        <?= ! empty($input['required']) ? 'required' : '' ?>
                    >
                        <option value="" disabled>
                            <?= esc($input['placeholder'] ?? 'Pilih salah satu') ?>
                        </option>
                        <?php foreach (($input['options'] ?? []) as $option) : ?>
                            <option value="<?= esc($option['value'], 'attr') ?>">
                                <?= esc($option['label']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                <?php else : ?>
                    <input
                        x-model="targetValues['<?= esc($input['key'], 'js') ?>']"
                        @input="schedulePreview()"
                        type="<?= esc($inputType === 'number' ? 'number' : 'text', 'attr') ?>"
                        id="target_<?= esc($input['key'], 'attr') ?>"
                        name="<?= esc($input['name'], 'attr') ?>"
                        placeholder="<?= esc($input['placeholder'], 'attr') ?>"
                        class="input"
                        <?= ! empty($input['required']) ? 'required' : '' ?>
                    >
                <?php endif ?>
            </div>
        <?php endforeach ?>
    </div>
</div>
