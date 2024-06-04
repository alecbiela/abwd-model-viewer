<?php
  $bID = 44;    //arbitrary example block ID
  $args = [
    "model" => [
        "type" => 'glb',
        "fileID" => 999,
        "binaries" => 'included',
        "binaryFileID" => -1,
        "extras" => 'none',
        "alt" => 'A test model alt text',
        "posterFileID" => 998,
        "loadingType" => 'auto',
        "activationType" => 'auto'
    ],
    "controls" => [
        "enable" => true,
        "orbit" => true,
        "orbitSensitivity" => 1,
        "zoom" => true,
        "zoomSensitivity" => 2,
        "pan" => true,
        "panSensitivity" => 1,
        "uiControls" => true
    ],
    "style" => [
        "bgColor" => '#333',
        "dimensionType" => 'responsive',
        "dimensionWidthValue" => '800',
        "dimensionWidthUnits" => 'px',
        "dimensionHeightValue" => '600',
        "dimensionHeightUnits" => 'px',
        "styling" => 'minimal'
    ],
    "accessibility" => [
        "enable" => false,
        "rules" => [
            "front" => "",
            "back" => "",
            "left" => "",
            "right" => "",
            "upper-front" => "",
            "upper-back" => "",
            "upper-left" => "",
            "upper-right" => "",
            "lower-front" => "",
            "lower-back" => "",
            "lower-left" => "",
            "lower-right" => "",
            "interaction-prompt" => ""
        ]
    ],
    "ar" => [
        "enable" => false,
        "arScale" => 'auto',
        "placement" => 'floor',
        "xrEstimation" => false
    ]    
  ];

  $attrArr = [];
  $attrArr[] = 'id="model_viewport_'.$bID.'"';
  $attrArr[] = 'class="model-viewer"';

  
  /* Parse the args into the attribute array */
  // Model data
  $modelURL = './model/ToyCar.glb'; //TODO: replace file urls with C5 file getters
  $posterURL = './model/ToyCar.png';
  $attrArr[] = 'src="'.$modelURL.'"';
  $attrArr[] = 'alt="'.$args["model"]["alt"].'"';
  $attrArr[] = 'poster="'.$posterURL.'"';
  $attrArr[] = 'loading="'.$args["model"]["loadingType"].'"';
  $attrArr[] = 'reveal="'.$args["model"]["activationType"].'"';

  // Controls
  if($args["controls"]["enable"] === true) $attrArr[] = 'camera-controls';
  if($args["controls"]["orbit"] === false) $attrArr[] = 'disable-orbit';
  else $attrArr[] = 'orbit-sensitivity="'.$args["controls"]["orbitSensitivity"].'"';
  if($args["controls"]["zoom"] === false) $attrArr[] = 'disable-zoom';
  else $attrArr[] = 'zoom-sensitivity="'.$args["controls"]["zoomSensitivity"].'"'; 
  if($args["controls"]["pan"] === false) $attrArr[] = 'disable-pan';
  else $attrArr[] = 'pan-sensitivity="'.$args["controls"]["panSensitivity"].'"'; 

  // Style
  //TODO: Add logic for handling responsive vs fixed styles

  // A11y
  if($args["accessibility"]["enable"] === true) $attrArr[] = 'a11y="'.json_encode($args["accessibility"]["rules"]).'"';

  // AR
  if($args["ar"]["enable"] === true){
    $attrArr[] = 'ar';
    $attrArr[] = 'ar-scale="'.$args["ar"]["arScale"].'"';
    $attrArr[] = 'ar-placement='.$args["ar"]["placement"].'"';
    if($args["ar"]["xrEstimation"] === true) $attrArr[] = 'xr-environment';
  }
?>
<div id="model_wrapper_<?= $bID; ?>" class="model-wrapper">
    <model-viewer <?= implode(' ', $attrArr); ?>>
        <?php if($args["controls"]["uiControls"] === true) { ?>
            <div class="controls">
                <button id="zoomin" type="button">Zoom In</button>
                <button id="zoomout" type="button">Zoom Out</button>
            </div>
        <?php } ?>
    </model-viewer>
</div>
<script>
    'use strict';
    const model = document.querySelector('#test_model');
    document.querySelector('#zoomin').addEventListener('click',function(e){
        model.zoom(2);
    });
    document.querySelector('#zoomout').addEventListener('click',function(e){
        model.zoom(-2);
    });
</script>