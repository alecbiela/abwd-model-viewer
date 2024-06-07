<div id="3d_model_container_<?= $bID; ?>" class="model-container">
    <model-viewer id="3d_model_viewer_<?= $bID; ?>" <?= $attrString; ?>>
        <!-- TODO: Add the rest of the buttons and flesh out the display of these buttons in CSS -->
        <!-- with things like .controls[ui-buttons] -> display: block; -->
        <div class="controls">
            <button id="zoomin" type="button" data-sensitivity="2">Zoom In</button>
            <button id="zoomout" type="button" data-sensitivity="-2">Zoom Out</button>
        </div>
    </model-viewer>
</div>