<?php defined('C5_EXECUTE') or die('Access Denied.'); 

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Form\Service\Widget\Color;
use Concrete\Core\Entity\File\File;
use Concrete\Core\Support\Facade\Application;
/**
 * Already Defined:
 * $controller - Block Controller instance
 * $form - Form Helper (Instance of class at concrete/src/Form/Service/Form.php)
 */
$app = Application::getFacadeApplication();
$color = $app->make(Color::class);
//Usage: $color->make($inputName, $value, $options = []);
$fileManager = $app->make(FileManager::class);
//Usage: $fileManager->file($inputID, $inputName, $chooseText, $preselectedFile = null, $args = [])
?>

<nav aria-label="Block Editor Form Sections">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-model-tab" data-bs-toggle="tab" data-bs-target="#nav-model" type="button" role="tab" aria-controls="nav-model" aria-selected="true">Model</button>
    <button class="nav-link" id="nav-controls-tab" data-bs-toggle="tab" data-bs-target="#nav-controls" type="button" role="tab" aria-controls="nav-controls" aria-selected="false">Controls</button>
    <button class="nav-link" id="nav-style-tab" data-bs-toggle="tab" data-bs-target="#nav-style" type="button" role="tab" aria-controls="nav-style" aria-selected="false">Style</button>
    <button class="nav-link" id="nav-accessibility-tab" data-bs-toggle="tab" data-bs-target="#nav-accessibility" type="button" role="tab" aria-controls="nav-accessibility" aria-selected="false">Accessibility</button>
    <button class="nav-link" id="nav-ar-tab" data-bs-toggle="tab" data-bs-target="#nav-ar" type="button" role="tab" aria-controls="nav-ar" aria-selected="false">AR</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active pt-3" id="nav-model" role="tabpanel" aria-labelledby="nav-model-tab" tabindex="0">
    <fieldset class="container-fluid">
        <legend><?= t('Model Information'); ?></legend>
        <div class="row mb-3">
            <div class="col-6">
                <?= $form->label('type', t('Model Type')); ?>
                <?= $form->select('type', array('glb'=>'GL Binary (.glb) Package','gltf'=>'glTF (.gltf) File'), ''); ?>
            </div>
            <div class="col-6">
                <?= $form->label('posterFileID', t('Poster Image')); ?>
                <?= $fileManager->image('ccm-b-poster-file', 'posterfID', t('Choose Poster Image'), null); ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <?= $form->label('fileID', t('Model File')); ?>
                <?= $fileManager->file('ccm-b-file', 'fileID', t('Choose Model File'), null); ?>
            </div>
            <div class="col-6">
                <?= $form->label('binaryFileID', t('Model Binary (.bin)')); ?>
                <?= $fileManager->file('ccm-b-binary-file', 'binaryFileID', t('Choose Model Binary'), null); ?>
            </div>
        </div>
        <div class="form-group">
            <?= $form->label('alt', t('Alternative Text')); ?>
            <?= $form->text('alt', ''); ?>
        </div>
        <div class="form-group">
            <?= $form->label('activationType', t('Viewer Initialization')); ?>
            <?= $form->select('activationType', array('auto'=>'Automatically when model loads','manual'=>'Manual (when a button is clicked)'), ''); ?>
        </div>
        <div class="form-group">
            <?= $form->label('loadingType', t('Loading Style')); ?>
            <?= $form->select('loadingType', array('auto'=>'Auto (Load whenever viewer initializes)','eager'=>'Eager (Load as soon as possible)','lazy'=>'Lazy (Load when scrolling into view)'), ''); ?>
        </div>
    </fieldset>
  </div>
  <div class="tab-pane fade pt-3" id="nav-controls" role="tabpanel" aria-labelledby="nav-controls-tab" tabindex="0">
    <fieldset class="container-fluid">
        <legend><?= t('User Controls'); ?></legend>
        <div class="row mb-3">
            <div class="col-6">
                <div class="form-check form-switch">
                    <?= $form->checkbox('enableOrbit', 'yes', false, array('role'=>'switch')); ?>
                    <?= $form->label('enableOrbit', t('Enable Orbit (rotation)'), array('class'=>'form-check-label')); ?>
                </div>
            </div>
            <div class="col-6">
                <div class="form-check form-switch">
                    <?= $form->checkbox('enablePan', 'yes', false, array('role'=>'switch')); ?>
                    <?= $form->label('enablePan', t('Enable Panning (slide)'), array('class'=>'form-check-label')); ?>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <div class="form-check form-switch">
                    <?= $form->checkbox('enableZoom', 'yes', false, array('role'=>'switch')); ?>
                    <?= $form->label('enableZoom', t('Enable Zoom (in/out)'), array('class'=>'form-check-label')); ?>
                </div>
            </div>
            <div class="col-6">
                <div class="form-check form-switch">
                    <?= $form->checkbox('enableButtons', 'yes', false, array('role'=>'switch')); ?>
                    <?= $form->label('enableButtons', t('Enable UI Buttons'), array('class'=>'form-check-label')); ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= $form->label('orbitSensitivity', t('Orbit Sensitivity')); ?>
            <input type="range" class="form-range" name="orbitSensitivity" id="orbitSensitivity" min="0" max="5" step="0.1" value="1">
            <div class="d-flex justify-content-between">
                <div>0</div>
                <div id="orbitSensitivity_display" class="fw-bold fs-4">1</div>
                <div>5</div>
            </div>
        </div>
        <div class="form-group">
            <?= $form->label('zoomSensitivity', t('Zoom Sensitivity')); ?>
            <input type="range" class="form-range" name="zoomSensitivity" id="zoomSensitivity" min="0" max="5" step="0.1" value="1">
            <div class="d-flex justify-content-between">
                <div>0</div>
                <div id="zoomSensitivity_display" class="fw-bold fs-4">1</div>
                <div>5</div>
            </div>
        </div>
        <div class="form-group">
            <?= $form->label('panSensitivity', t('Panning Sensitivity')); ?>
            <input type="range" class="form-range" name="panSensitivity" id="panSensitivity" min="0" max="5" step="0.1" value="1">
            <div class="d-flex justify-content-between">
                <div>0</div>
                <div id="panSensitivity_display" class="fw-bold fs-4">1</div>
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
            <?= $color->output('backgroundColor', '#ddd', array('preferredFormat'=>'hex')); ?>
        </div>
        <p class="fs-5">Dimensions:</p>
        <div class="form-group form-check form-switch">
            <?= $form->checkbox('isResponsive', 'yes', false, array('role'=>'switch')); ?>
            <?= $form->label('isResponsive', t('Responsive Sizing'), array('class'=>'form-check-label')); ?>
        </div>
        <div class="row">
            <div class="col-6"><?= $form->label('dimensionWidthValue', t('Width:')); ?></div>
            <div class="col-6"><?= $form->label('dimensionHeightValue', t('Height:')); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-3"><?= $form->number('dimensionWidthValue','800', array('min'=>'0', 'class'=>'dimensions-input')); ?></div>
            <div class="col-3"><?= $form->select('dimensionWidthUnits', array('px'=>'px', 'pc'=>'%'),'', array('class'=>'dimensions-input')); ?></div>
            <div class="col-3"><?= $form->number('dimensionHeightValue','600', array('min'=>'0', 'class'=>'dimensions-input')); ?></div>
            <div class="col-3"><?= $form->select('dimensionHeightUnits', array('px'=>'px', 'pc'=>'%'),'', array('class'=>'dimensions-input')); ?></div>
        </div>
    </fieldset>
  </div>
  <div class="tab-pane fade pt-3" id="nav-accessibility" role="tabpanel" aria-labelledby="nav-accessibility-tab" tabindex="0">
    <fieldset class="container-fluid">
        <legend><?= t('Accessibility'); ?></legend>
        <div class="form-group form-check form-switch">
            <?= $form->checkbox('enableA11y', 'yes', false, array('role'=>'switch')); ?>
            <?= $form->label('enableA11y', t('Enable Accessible Descriptions'), array('class'=>'form-check-label')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yInteractionPrompt', t('Interaction Prompt')); ?>
            <?= $form->text('a11yInteractionPrompt', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yFront', t('Front')); ?>
            <?= $form->text('a11yFront', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yBack', t('Back')); ?>
            <?= $form->text('a11yBack', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yLeft', t('Left')); ?>
            <?= $form->text('a11yLeft', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yRight', t('Right')); ?>
            <?= $form->text('a11yRight', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yUpperFront', t('Upper Front')); ?>
            <?= $form->text('a11yUpperFront', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yUpperBack', t('Upper Back')); ?>
            <?= $form->text('a11yUpperBack', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yUpperLeft', t('Upper Left')); ?>
            <?= $form->text('a11yUpperLeft', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yUpperRight', t('Upper Right')); ?>
            <?= $form->text('a11yUpperRight', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yLowerFront', t('Lower Front')); ?>
            <?= $form->text('a11yLowerFront', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yLowerBack', t('Lower Back')); ?>
            <?= $form->text('a11yLowerBack', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yLowerLeft', t('Lower Left')); ?>
            <?= $form->text('a11yLowerLeft', '', array('class'=>'a11y-input')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('a11yLowerRight', t('Lower Right')); ?>
            <?= $form->text('a11yLowerRight', '', array('class'=>'a11y-input')); ?>
        </div>
    </fieldset>
  </div>
  <div class="tab-pane fade pt-3" id="nav-ar" role="tabpanel" aria-labelledby="nav-ar-tab" tabindex="0">
    <fieldset class="container-fluid">
        <legend><?= t('Augmented Reality (AR)'); ?></legend>
        <div class="form-group form-check form-switch">
            <?= $form->checkbox('enableAR', 'yes', false, array('role'=>'switch')); ?>
            <?= $form->label('enableAR', t('Enable AR Where Available'), array('class'=>'form-check-label')); ?>
        </div>
        <div class="form-group form-check form-switch">
            <?= $form->checkbox('enableResizingAR', 'yes', false, array('role'=>'switch', 'class'=>'ar-input')); ?>
            <?= $form->label('enableResizingAR', t('Enable Resizing of Models in AR'), array('class'=>'form-check-label')); ?>
        </div>
        <div class="form-group form-check form-switch">
            <?= $form->checkbox('enableEstimationAR', 'yes', false, array('role'=>'switch', 'class'=>'ar-input')); ?>
            <?= $form->label('enableEstimationAR', t('Enable AR Lighting Estimation in WebXR Mode (slow)'), array('class'=>'form-check-label')); ?>
        </div>
        <div class="form-group">
            <?= $form->label('placementAR', t('Model Placement')); ?>
            <?= $form->select('placementAR', array('floor'=>'Floor', 'wall'=>'Wall'),'', array('class'=>'ar-input')); ?>
        </div>
    </fieldset>
  </div>
</div>
<script>
    'use strict';
    $('#enableOrbit').change(function(){
        //if(this.checked) $('#orbit_sensitivity_dd').slideDown(500);
        //else $('#orbit_sensitivity_dd').slideUp(500);
    });
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

    $('#enableA11y, #isResponsive, #enableAR').trigger('change');
</script>