window.onload = (() => {
    'use strict';
    const MODEL_VIEWERS = document.querySelectorAll('model-viewer');

    // Set up event listeners
    const init = () => {
        for(const viewer of MODEL_VIEWERS){
            // Only run button setup logic if block's UI-buttons are enabled
            if(viewer.getAttribute('ui-buttons') !== null) {
                const SENSITIVITY = {
                    pan: parseFloat(viewer.getAttribute('pan-sensitivity')),
                    zoom: parseFloat(viewer.getAttribute('zoom-sensitivity')),
                    orbit: parseFloat(viewer.getAttribute('orbit-sensitivity')),
                    pan_mod: 0.01,
                    zoom_mod: 3,
                    orbit_mod: 0.5
                };
                viewer.addEventListener('load', () => {
                    viewer.setAttribute('default-orbit', viewer.getCameraOrbit().toString());
                    viewer.setAttribute('default-pos', viewer.getCameraTarget().toString());
                    viewer.classList.add('loaded');
                });

                // If orbit enabled, register orbit buttons
                if(viewer.getAttribute('disable-orbit') === null){
                    viewer.querySelector('.orbit-down-button').addEventListener('click', () => {
                        let orbit = viewer.getCameraOrbit();
                        orbit.phi += (SENSITIVITY.orbit * SENSITIVITY.orbit_mod);
                        viewer.setAttribute('camera-orbit', orbit.toString());
                        viewer.setAttribute('interaction-prompt', 'none');
                    });
                    viewer.querySelector('.orbit-up-button').addEventListener('click', () => {
                        let orbit = viewer.getCameraOrbit();
                        orbit.phi -= (SENSITIVITY.orbit * SENSITIVITY.orbit_mod);
                        viewer.setAttribute('camera-orbit', orbit.toString());
                        viewer.setAttribute('interaction-prompt', 'none');
                    });
                    viewer.querySelector('.orbit-left-button').addEventListener('click', () => {
                        let orbit = viewer.getCameraOrbit();
                        orbit.theta -= (SENSITIVITY.orbit * SENSITIVITY.orbit_mod);
                        viewer.setAttribute('camera-orbit', orbit.toString());
                        viewer.setAttribute('interaction-prompt', 'none');
                    });
                    viewer.querySelector('.orbit-right-button').addEventListener('click', () => {
                        let orbit = viewer.getCameraOrbit();
                        orbit.theta += (SENSITIVITY.orbit * SENSITIVITY.orbit_mod);
                        viewer.setAttribute('camera-orbit', orbit.toString());
                        viewer.setAttribute('interaction-prompt', 'none');
                    });
                }
                // If zoom enabled, register zoom buttons
                if(viewer.getAttribute('disable-zoom') === null){
                    viewer.querySelector('.zoom-in-button').addEventListener('click', () => {
                        viewer.zoom(SENSITIVITY.zoom * SENSITIVITY.zoom_mod);
                        viewer.setAttribute('interaction-prompt', 'none');
                    });
                    viewer.querySelector('.zoom-out-button').addEventListener('click', () => {
                        viewer.zoom(SENSITIVITY.zoom * SENSITIVITY.zoom_mod * -1);
                        viewer.setAttribute('interaction-prompt', 'none');
                    });
                }
                // If pan enabled, register pan buttons
                // Pan buttons may be added in a future update
                /*if(viewer.getAttribute('disable-pan') === null){
                }*/

                // Resets camera to initial
                viewer.querySelector('.reset-model-button').addEventListener('click', () => {
                    viewer.setAttribute('camera-orbit', viewer.getAttribute('default-orbit'));
                    viewer.setAttribute('camera-target', viewer.getAttribute('default-pos'));
                    viewer.zoom(-999);
                    viewer.setAttribute('interaction-prompt', 'none');
                });
            }
                // Hook up viewers that load manually on button click (reveal=manual)
                const revealButton = viewer.querySelector('.load-3d-model');
                if(revealButton !== null){
                    revealButton.addEventListener('click', () => {
                        viewer.dismissPoster();
                    });
                }
        }
    };
    init();
})();