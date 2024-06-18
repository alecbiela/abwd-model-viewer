<?php 

defined('C5_EXECUTE') or die('Access Denied.'); 

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Form\Service\Widget\Color;
use Concrete\Core\Entity\File\File;
use Concrete\Core\Support\Facade\Application;
/**
 * Already Defined:
 * $controller - Block Controller instance
 * $form - Form Helper (Instance of class at concrete/src/Form/Service/Form.php)
 * $fileID, $posterFileID - will be null if adding a new block
 * $blockData - Existing data (if editing block) or defaults
 */
$app = Application::getFacadeApplication();
$color = $app->make(Color::class);
//Usage: $color->make($inputName, $value, $options = []);
$fileManager = $app->make(FileManager::class);
//Usage: $fileManager->file($inputID, $inputName, $chooseText, $preselectedFile = null, $args = [])
?>
<div id="abwd_model_viewer_block_editor">
    <nav aria-label="Block Editor Form Sections">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-model-tab" data-toggle="tab" data-bs-toggle="tab" data-target="#nav-model" data-bs-target="#nav-model" type="button" role="tab" aria-controls="nav-model" aria-selected="true"><?= t('Model'); ?></button>
        <button class="nav-link" id="nav-controls-tab" data-toggle="tab" data-bs-toggle="tab" data-target="#nav-controls" data-bs-target="#nav-controls" type="button" role="tab" aria-controls="nav-controls" aria-selected="false"><?= t('Controls'); ?></button>
        <button class="nav-link" id="nav-style-tab" data-toggle="tab" data-bs-toggle="tab" data-target="#nav-style" data-bs-target="#nav-style" type="button" role="tab" aria-controls="nav-style" aria-selected="false"><?= t('Style'); ?></button>
        <button class="nav-link" id="nav-accessibility-tab" data-toggle="tab" data-bs-toggle="tab" data-target="#nav-accessibility" data-bs-target="#nav-accessibility" type="button" role="tab" aria-controls="nav-accessibility" aria-selected="false"><?= t('Accessibility'); ?></button>
        <button class="nav-link" id="nav-ar-tab" data-toggle="tab" data-bs-toggle="tab" data-target="#nav-ar" data-bs-target="#nav-ar" type="button" role="tab" aria-controls="nav-ar" aria-selected="false"><?= tc('Augmented Reality', 'AR'); ?></button>
    </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane active pt-3" id="nav-model" role="tabpanel" aria-labelledby="nav-model-tab" tabindex="0">
        <fieldset class="container-fluid">
            <legend><?= t('Model Information'); ?></legend>
            <div class="row mb-3">
                <div class="col-6">
                    <?= $form->label('fileID', t('Model File')); ?>
                    <?= $fileManager->file('ccm-b-file', 'fileID', t('Choose Model File'), $fileID); ?>
                </div>
                <div class="col-6">
                    <?= $form->label('posterFileID', t('Poster Image')); ?>
                    <?= $fileManager->image('ccm-b-poster-file', 'posterFileID', t('Choose Poster Image'), $posterFileID); ?>
                </div>
            </div>
            <div class="form-group">
                <?= $form->label('alt', t('Alternative Text')); ?>
                <?= $form->text('alt', $blockData["model"]["alt"]); ?>
            </div>
            <div class="form-group">
                <?= $form->label('activationType', t('Viewer Initialization')); ?>
                <?= $form->select('activationType', array('auto'=>t('Automatically when model loads'),'manual'=>t('Manual (when a button is clicked)')), $blockData["model"]["activationType"]); ?>
            </div>
            <div class="form-group">
                <?= $form->label('loadingType', t('Loading Style')); ?>
                <?= $form->select('loadingType', array('auto'=>t('Auto (Load whenever viewer initializes)'),'eager'=>t('Eager (Load as soon as possible)'),'lazy'=>t('Lazy (Load when scrolling into view)')), $blockData["model"]["loadingType"]); ?>
            </div>
        </fieldset>
    </div>
    <div class="tab-pane fade pt-3" id="nav-controls" role="tabpanel" aria-labelledby="nav-controls-tab" tabindex="0">
        <fieldset class="container-fluid">
            <legend><?= t('User Controls'); ?></legend>
            <div class="row mb-3">
                <div class="col-6">
                    <div class="form-check form-switch">
                        <?= $form->checkbox('enableOrbit', 'yes', $blockData["controls"]["enableOrbit"], array('role'=>'switch')); ?>
                        <?= $form->label('enableOrbit', t('Enable Orbit (rotation)'), array('class'=>'form-check-label')); ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-check form-switch">
                        <?= $form->checkbox('enablePan', 'yes', $blockData["controls"]["enablePan"], array('role'=>'switch')); ?>
                        <?= $form->label('enablePan', t('Enable Panning (slide)'), array('class'=>'form-check-label')); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <div class="form-check form-switch">
                        <?= $form->checkbox('enableZoom', 'yes', $blockData["controls"]["enableZoom"], array('role'=>'switch')); ?>
                        <?= $form->label('enableZoom', t('Enable Zoom (in/out)'), array('class'=>'form-check-label')); ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-check form-switch">
                        <?= $form->checkbox('enableButtons', 'yes', $blockData["controls"]["enableButtons"], array('role'=>'switch')); ?>
                        <?= $form->label('enableButtons', t('Enable UI Buttons'), array('class'=>'form-check-label')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?= $form->label('orbitSensitivity', t('Orbit Sensitivity')); ?>
                <input type="range" class="form-range" name="orbitSensitivity" id="orbitSensitivity" min="0" max="5" step="0.1" value="<?= $blockData["controls"]["orbitSensitivity"]; ?>">
                <div class="d-flex justify-content-between">
                    <div>0</div>
                    <div id="orbitSensitivity_display" class="fs-4" style="font-weight: bold;">1</div>
                    <div>5</div>
                </div>
            </div>
            <div class="form-group">
                <?= $form->label('zoomSensitivity', t('Zoom Sensitivity')); ?>
                <input type="range" class="form-range" name="zoomSensitivity" id="zoomSensitivity" min="0" max="5" step="0.1" value="<?= $blockData["controls"]["zoomSensitivity"]; ?>">
                <div class="d-flex justify-content-between">
                    <div>0</div>
                    <div id="zoomSensitivity_display" class="fs-4" style="font-weight: bold;">1</div>
                    <div>5</div>
                </div>
            </div>
            <div class="form-group">
                <?= $form->label('panSensitivity', t('Panning Sensitivity')); ?>
                <input type="range" class="form-range" name="panSensitivity" id="panSensitivity" min="0" max="5" step="0.1" value="<?= $blockData["controls"]["panSensitivity"]; ?>">
                <div class="d-flex justify-content-between">
                    <div>0</div>
                    <div id="panSensitivity_display" class="fs-4" style="font-weight: bold;">1</div>
                    <div>5</div>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="tab-pane fade pt-3" id="nav-style" role="tabpanel" aria-labelledby="nav-style-tab" tabindex="0">
        <fieldset class="container-fluid">
            <legend><?= t('Viewer Styles'); ?></legend>
            <div class="form-group">
                <?= $form->label('backgroundColor', t('Scene Background Color')); ?><br>
                <?= $color->output('backgroundColor', $blockData["style"]["backgroundColor"], array('preferredFormat'=>'hex')); ?>
            </div>
            <p class="fs-5">Dimensions:</p>
            <div class="form-group form-check form-switch">
                <?= $form->checkbox('isResponsive', 'yes', $blockData["style"]["isResponsive"], array('role'=>'switch')); ?>
                <?= $form->label('isResponsive', t('Responsive Sizing'), array('class'=>'form-check-label')); ?>
            </div>
            <div class="row">
                <div class="col-6"><?= $form->label('dimensionWidthValue', t('Width:')); ?></div>
                <div class="col-6"><?= $form->label('dimensionHeightValue', t('Height:')); ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-3"><?= $form->number('dimensionWidthValue', $blockData["style"]["dimensionWidthValue"], array('min'=>'0', 'class'=>'dimensions-input')); ?></div>
                <div class="col-3"><?= $form->select('dimensionWidthUnits', array('px'=>'px', 'pc'=>'%'), $blockData["style"]["dimensionWidthUnits"], array('class'=>'dimensions-input')); ?></div>
                <div class="col-3"><?= $form->number('dimensionHeightValue', $blockData["style"]["dimensionHeightValue"], array('min'=>'0', 'class'=>'dimensions-input')); ?></div>
                <div class="col-3"><?= $form->select('dimensionHeightUnits', array('px'=>'px', 'pc'=>'%'),$blockData["style"]["dimensionHeightUnits"], array('class'=>'dimensions-input')); ?></div>
            </div>
        </fieldset>
    </div>
    <div class="tab-pane fade pt-3" id="nav-accessibility" role="tabpanel" aria-labelledby="nav-accessibility-tab" tabindex="0">
        <fieldset class="container-fluid">
            <legend><?= t('Accessibility'); ?></legend>
            <div class="form-group form-check form-switch">
                <?= $form->checkbox('enableA11y', 'yes', $blockData["accessibility"]["enableA11y"], array('role'=>'switch')); ?>
                <?= $form->label('enableA11y', t('Enable Accessible Descriptions'), array('class'=>'form-check-label')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yInteractionPrompt', t('Interaction Prompt')); ?>
                <?= $form->text('a11yInteractionPrompt', $blockData["accessibility"]["a11yRules"]["interaction-prompt"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yFront', t('Front')); ?>
                <?= $form->text('a11yFront', $blockData["accessibility"]["a11yRules"]["front"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yBack', t('Back')); ?>
                <?= $form->text('a11yBack', $blockData["accessibility"]["a11yRules"]["back"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yLeft', t('Left')); ?>
                <?= $form->text('a11yLeft', $blockData["accessibility"]["a11yRules"]["left"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yRight', t('Right')); ?>
                <?= $form->text('a11yRight', $blockData["accessibility"]["a11yRules"]["right"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yUpperFront', t('Upper Front')); ?>
                <?= $form->text('a11yUpperFront', $blockData["accessibility"]["a11yRules"]["upper-front"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yUpperBack', t('Upper Back')); ?>
                <?= $form->text('a11yUpperBack', $blockData["accessibility"]["a11yRules"]["upper-back"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yUpperLeft', t('Upper Left')); ?>
                <?= $form->text('a11yUpperLeft', $blockData["accessibility"]["a11yRules"]["upper-left"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yUpperRight', t('Upper Right')); ?>
                <?= $form->text('a11yUpperRight', $blockData["accessibility"]["a11yRules"]["upper-right"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yLowerFront', t('Lower Front')); ?>
                <?= $form->text('a11yLowerFront', $blockData["accessibility"]["a11yRules"]["lower-front"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yLowerBack', t('Lower Back')); ?>
                <?= $form->text('a11yLowerBack', $blockData["accessibility"]["a11yRules"]["lower-back"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yLowerLeft', t('Lower Left')); ?>
                <?= $form->text('a11yLowerLeft', $blockData["accessibility"]["a11yRules"]["lower-left"], array('class'=>'a11y-input')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('a11yLowerRight', t('Lower Right')); ?>
                <?= $form->text('a11yLowerRight', $blockData["accessibility"]["a11yRules"]["lower-right"], array('class'=>'a11y-input')); ?>
            </div>
        </fieldset>
    </div>
    <div class="tab-pane fade pt-3" id="nav-ar" role="tabpanel" aria-labelledby="nav-ar-tab" tabindex="0">
        <fieldset class="container-fluid">
            <legend><?= t('Augmented Reality (AR)'); ?></legend>
            <div class="form-group form-check form-switch">
                <?= $form->checkbox('enableAR', 'yes', $blockData["ar"]["enableAR"], array('role'=>'switch')); ?>
                <?= $form->label('enableAR', t('Enable AR Where Available'), array('class'=>'form-check-label')); ?>
            </div>
            <div class="form-group form-check form-switch">
                <?= $form->checkbox('enableResizingAR', 'yes', $blockData["ar"]["enableResizingAR"], array('role'=>'switch', 'class'=>'ar-input')); ?>
                <?= $form->label('enableResizingAR', t('Enable Resizing of Models in AR'), array('class'=>'form-check-label')); ?>
            </div>
            <div class="form-group form-check form-switch">
                <?= $form->checkbox('enableEstimationAR', 'yes', $blockData["ar"]["enableEstimationAR"], array('role'=>'switch', 'class'=>'ar-input')); ?>
                <?= $form->label('enableEstimationAR', t('Enable AR Lighting Estimation in WebXR Mode (slow)'), array('class'=>'form-check-label')); ?>
            </div>
            <div class="form-group">
                <?= $form->label('placementAR', t('Model Placement')); ?>
                <?= $form->select('placementAR', array('floor'=>'Floor', 'wall'=>'Wall'),$blockData["ar"]["placementAR"], array('class'=>'ar-input')); ?>
            </div>
        </fieldset>
    </div>
    </div>
</div>
<script>
    $(function(){
        'use strict';

        // Event listeners
        $('#orbitSensitivity').on('input',function(){
            $('#orbitSensitivity_display').text(this.value);
        });
        $('#zoomSensitivity').on('input',function(){
            $('#zoomSensitivity_display').text(this.value);
        });
        $('#panSensitivity').on('input',function(){
            $('#panSensitivity_display').text(this.value);
        });
        $('#isResponsive').change(function(){
            if(this.checked) $('.dimensions-input').addClass('disabled').attr('disabled', 'disabled');
            else $('.dimensions-input').removeClass('disabled').removeAttr('disabled');
        });
        $('#enableA11y').change(function(){
            if(!this.checked) $('.a11y-input').addClass('disabled').attr('disabled', 'disabled');
            else $('.a11y-input').removeClass('disabled').removeAttr('disabled');
        });
        $('#enableAR').change(function(){
            if(!this.checked) $('.ar-input').addClass('disabled').attr('disabled', 'disabled');
            else $('.ar-input').removeClass('disabled').removeAttr('disabled');
        });

        // Sync display values with inputs
        $('#enableA11y, #isResponsive, #enableAR').trigger('change');
        $('#orbitSensitivity, #zoomSensitivity, #panSensitivity').trigger('input');
    });
</script>