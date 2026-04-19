<?php
// expects:
// $modalId
// $title
// $content
?>

<div id="<?= $modalId ?>"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg w-96 p-6">

        <h2 class="text-lg font-semibold mb-4">
            <?= $title ?>
        </h2>

        <?= $content ?>

    </div>
</div>