<?php
/**
 * @var string $field       'image' | 'banner' — key di form & di state Alpine `media`
 * @var string $label       label yang keliatan
 * @var string $refName     nama x-ref buat <input type="file"> tersembunyi
 * @var string $aspectClass ukuran box preview (tailwind class, beda buat icon vs banner)
 * @var string $folderHint  path folder tujuan upload manual (buat teks bantuan)
 * @var string $placeholder placeholder input nama file
 */
?>
<div>
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"><?= esc($label) ?></label>
    <input type="file" x-ref="<?= $refName ?>" accept="image/*" @change="onMediaFileChange('<?= $field ?>', $event)" class="hidden">

    <div class="flex items-center gap-3">
        <div class="flex <?= $aspectClass ?> shrink-0 items-center justify-center overflow-hidden rounded-lg border border-dashed border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
            <img
                x-show="mediaPreviewUrl('<?= $field ?>') && !media.<?= $field ?>.broken"
                :src="mediaPreviewUrl('<?= $field ?>')"
                @error="media.<?= $field ?>.broken = true"
                class="h-full w-full object-cover"
                alt="">
            <svg x-show="!mediaPreviewUrl('<?= $field ?>') || media.<?= $field ?>.broken" width="20" height="20" viewBox="0 0 20 20" fill="none" class="text-gray-300 dark:text-gray-600">
                <path d="M3 6a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V6z" stroke="currentColor" stroke-width="1.5" />
                <path d="M3 13l4-4 3 3 4-4 3 3" stroke="currentColor" stroke-width="1.5" />
            </svg>
        </div>
        <div class="flex flex-1 flex-col gap-2">
            <input type="text" x-model="form.<?= $field ?>" placeholder="<?= esc($placeholder, 'attr') ?>" class="h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-theme-xs text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            <div class="flex gap-2">
                <button type="button" @click="$refs.<?= $refName ?>.click()" class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5">Pilih File</button>
                <button type="button" x-show="mediaPreviewUrl('<?= $field ?>')" @click="clearMedia('<?= $field ?>')" class="rounded-lg border border-error-300 px-3 py-1.5 text-theme-xs font-medium text-error-600 hover:bg-error-50 dark:border-error-500/30 dark:text-error-400 dark:hover:bg-error-500/10">Hapus</button>
            </div>
        </div>
    </div>
    <p class="mt-1 text-theme-xs text-gray-400">Preview + nama file otomatis doang. File fisiknya tetep manual ditaro ke <code><?= esc($folderHint) ?></code>.</p>
</div>
