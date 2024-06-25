<?php 
    defined('C5_EXECUTE') or die('Access Denied.');
    $alt = json_decode($bSettings, true)["model"]["alt"];
?>

<div id="model_viewer_scrapbook_<?= $mvid; ?>">
    <?php if(isset($poster)) { ?>
        <img src="<?= $poster; ?>" alt="<?= t($alt); ?>">
    <?php } else { ?>
        <p style="font-size: 36px; padding: 0 10px;"><?= t($alt); ?></p>
    <?php } ?>
</div>