<div id="model_container_3d_<?= $bID; ?>" class="model-container">
    <model-viewer id="model_viewer_3d_<?= $bID; ?>" <?= $attrString; ?>>
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
        <div class="error-overlay">
            <div class="error-text">
                <i class="abwd-mv-icon-attention" aria-hidden="true"></i>
                <p><?= t('Error'); ?><br/><small class="error-msg"><?= t('An error has occurred.'); ?></small></p>
            </div>
        </div>
    </model-viewer>
</div>
<script>
    window.onload = (function(){
        'use strict';
        // Translatable error messages for the model viewer
        document.querySelector('#model_viewer_3d_<?= $bID; ?>').addEventListener('error', (e) => {
            switch(e.detail.type){
                case 'loadfailure':
                    viewer.querySelector('.error-msg').textContent = `<?= t('The 3D model failed to load.'); ?>`;
                    break;
                case 'webglcontextlost':
                    viewer.querySelector('.error-msg').textContent = `<?= t('The webGL context was lost.'); ?>`;
                    break;
                default:
                    viewer.querySelector('.error-msg').textContent = `<?= t('An unknown or unexpected error occurred.'); ?>`;
                    break;
            }
            viewer.classList.add('has-error');
        });
    });
</script>