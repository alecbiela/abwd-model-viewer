<?php
    $srcStr = isset($src) ? sprintf(' src="%s"', $src) : '';
    $posterStr = isset($poster) ? sprintf(' poster="%s"', $poster) : '';
    if($srcStr !== '' || $posterStr !== ''){ // Model OR Poster must be defined for block to display
?>
<div id="model_container_3d_<?= $mvid; ?>" class="model-container">
    <model-viewer id="model_viewer_3d_<?= $mvid; ?>" <?= $attrString; ?><?= $srcStr; ?><?= $posterStr; ?>>
        <?php if(strpos($attrString, 'reveal="manual"') !== false) { ?>
            <div slot="poster" class="load-3d-model-poster" style="background-image: url(<?= $poster; ?>);" aria-hidden="true"></div>
            <button slot="poster" class="load-3d-model" type="button"><i class="abwd-mv-icon-arrows-cw" aria-hidden="true"></i>&nbsp;<?= t('Load 3D Model'); ?></button>
        <?php } ?>
        <div class="controls">
            <div class="zooms">
                <button class="zoom-out-button" type="button">
                    <i class="abwd-mv-icon-minus" aria-hidden="true"></i>
                </button>
                <button class="zoom-in-button" type="button">
                    <i class="abwd-mv-icon-plus" aria-hidden="true"></i>
                </button>
            </div>
            <div class="orbits">
                <button class="orbit-right-button" type="button">
                    <i class="abwd-mv-icon-right-open" aria-hidden="true"></i>
                </button>
                <button class="orbit-left-button" type="button">                    
                    <i class="abwd-mv-icon-left-open" aria-hidden="true"></i>
                </button>
                <button class="orbit-up-button" type="button">                    
                    <i class="abwd-mv-icon-up-open" aria-hidden="true"></i>
                </button>
                <button class="orbit-down-button" type="button">                    
                    <i class="abwd-mv-icon-down-open" aria-hidden="true"></i>
                </button>
            </div>
            <div class="reset">
                <button class="reset-model-button" type="button">
                    <i class="abwd-mv-icon-cw" aria-hidden="true"></i> <?= t('Reset'); ?>
                </button>
            </div>
            <?php 
            /*UI Panning is not currently implemented, but may be added in a future update
            <div class="pans">
                <button class="pan-right-button" type="button">Pan Right</button>
                <button class="pan-left-button" type="button">Pan Left</button>
                <button class="pan-up-button" type="button">Pan Up</button>
                <button class="pan-down-button" type="button">Pan Down</button>
            </div> */ 
            ?>
        </div>
    </model-viewer>
</div>
<script>
    (function(){
        'use strict';
        // Translatable error messages for the model viewer
        document.querySelector('#model_viewer_3d_<?= $mvid; ?>').addEventListener('error', (e) => {
            switch(e.detail.type){
                case 'loadfailure':
                    console.error(`<?= t('The 3D model failed to load. Additional information may be available in errors from the viewer above.'); ?>`);
                    break;
                case 'webglcontextlost':
                    console.error(`<?= t('The WebGL context has been lost. Additional information may be available in errors from the viewer above.'); ?>`);
                    break;
                default:
                console.error(`<?= t('An unknown or unexpected error occurred. Additional information may be available in errors from the viewer above.'); ?>`);
                    break;
            }
            e.target.classList.add('has-error');
        });
    })();
</script>
<?php } else if($c->isEditMode()){ ?>
    <div class="ccm-edit-mode-disabled-item">
        <?php echo t('Empty 3D Model Viewer Block.'); ?>
    </div>
<?php } ?>