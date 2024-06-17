<?php 
    defined('C5_EXECUTE') or die('Access Denied.'); 
    $poster = \Concrete\Core\File\File::getByID($posterFileID)->getApprovedVersion()->getURL();
?>

<div id="model_viewer_scrapbook_<?= $bID; ?>">
    <img src="<?= $poster; ?>" alt="<?= t('3D Model Viewer preview image'); ?>" style="display: block; width: 100%; height: auto; max-height: 300px;" />
</div>