# ConcreteCMS 3D Model Viewer Add-on

This is the main Bitbucket repository for the "ABWD Model Viewer" add-on for ConcreteCMS. The add-on itself is an unofficial port of
Google's `<model-viewer>` web component and is in no way affiliated with Google.

Specifically, this package adds a new block, "3D Model Viewer", to the "Multimedia" section of the block editor which allows the user 
to display a 3D model in GL Transmission Format (glTF) and adjust various parameters of its control and display.

## Installation
Before attempting to install this add-on, ensure the following requirements are met:
* Concrete5/ConcreteCMS: Version `8.5.17` or later (Note: Additional versions will be tested for backwards compatibility).
* PHP: Version `7.4.0` or later.

To install the package, perform the following steps:
1. Copy the `abwd_model_viewer` folder into your website's `packages` folder.
2. Visit your website and log in.
3. In the Dashboard, navigate to the **Extend Concrete** or **Extend Concrete5** page (depending on your CMS version).
4. Press **Install** next to the `3D Model Viewer` package.
5. Wait for the page to refresh - You should receive a confirmation that the package was installed.

### A Note About File Types
Only glTF-formatted models may be used in this model viewer; any type other than `.glb` and `.gltf` is not supported, and existing models will need to be converted to glTF before they may be used. Free programs such as Blender are capable of exporting your models in this format - see [The Blender Documentation](https://docs.blender.org/manual/en/latest/addons/import_export/scene_gltf2.html) for more information on glTF formatting.

This package will add `.glb` and `.gltf` to the allowed upload file types in the CMS (see Dashboard > System & Settings > Files > Allowed File Types).  **It will also remove these types from the allowed types list when the package is uninstalled**. If you still need to upload files with these types after the package is uninstalled, you will need to manually re-add these extensions to that list inside the dashboard.

#### What about binary files?
Currently, the model viewer does **not** support models with detached binary files (for example, a `.gltf` file with the model information and separate `.bin` and texture files). This would require allowing `.bin` files to be uploaded to the File Manager, which can present a security risk especially on Linux servers. 
Therefore, any uploaded models will need to be bundled into a single `.glb` or *embedded*-`.gltf` file.

#### Where to Find Models
The Khronos Group maintains a repository of sample glTF models [on their GitHub Page](https://github.com/KhronosGroup/glTF-Sample-Assets/blob/main/Models/Models.md). For most of these models, a "Download GLB" button can be found beneath the image on the left side of the table - this can be used to download `.glb` files for testing or use on your own website (the licenses for each model are listed to the right of the image on that page).

## Configuration Options
A brief overview of the 3D Model Viewer Block's settings can be found in this section.

### Model
* Poster Image (**required**): An image that will show before/during the loading of the model viewer. Also serves as a fallback when the model viewer is not supported on the end user's device.
* Model File (**required**): The model file. Currently, only `.glb` and `.gltf` files are supported.
* Alternative Text (**required**): Descriptive text about the model for users who cannot access/operate the model viewer. Similar to an image's alternative text.
* Viewer Initialization: Controls when the viewer starts up. If set to automatic, the viewer will typically start up when the page loads (as soon as your model file is loaded on the page). If manual, the poster image will display until the viewer is clicked, at which point it will begin the loading process. (Default: Automatic)
* Loading Style: Controls when the model file is loaded. (Default: `Auto`)
  * `Auto`, which will load the model file into the viewer whenever it starts up
  * `Eager` which will load the model file as soon as possible (usually on page load) 
  * `Lazy` which will load the model when the viewer is about to be scrolled into view on the page

### Controls
* Enable Orbit: Enable/Disable the user's ability to rotate the camera around the model (Left Click + Drag)
* Enable Panning: Enable/Disable the user's ability to pan the camera across the model (Right Click + Drag)
* Enable Zoom: Enable/Disable the user's ability to zoom the camera in and out (Scroll Wheel)
* Enable UI Buttons: Enable/Disable a UI inside the viewer which will allow for Orbit and Zoom control of the model (if enabled), as well as a "reset" button that returns the camera to its original position. On extra-small screen sizes (&lt; 576px) these buttons will hide automatically.
* Orbit, Zoom, Panning Sensitivity: Individually control the sensitivity for the inputs. Higher numbers make the model move more in the desired direction from the user input, while lower numbers make it move less.

### Style
* Scene Background Color: Flat color to be displayed behind the model. The default value is transparent, but the model viewer's skybox essentially makes it white by default.
* Responsive Sizing: Enable responsive scaling of the model viewer based on screen size. The viewer will take up 100% of the width of its parent container (if possible) but will not extend past 80% of the screen's height (to allow room for touch-based scrolling).
* Width and Height: If responsive sizing is disabled, you may explicitly set a width and height of the model viewer, using pixels (`px`) or percent (`%`) values. Note that using percentages without explicit width/height settings on the block's container may produce unexpected results. Width and Height are **required** if responsive sizing is turned off.

### Accessibility
* Enable Accessible Descriptions: Allows for fine-tuned accessible descriptions of the model; if enabled, you may summarize or describe individual parts of the model for users of assistive technologies, as well as set customized interaction prompt text. 

### AR (Advanced)
* Enable AR Where Available: If the user's device supports Augmented Reality, enabling AR mode will show a UI button in the model viewer allowing for the model to be placed in an Augmented Reality scene.
* Enable Resizing of Models in AR: If enabled, models will be user-scalable within the AR scene.
* Enable AR Lighting Estimation in WebXR Mode: If the AR scene is rendered using WebXR, enabling this will project estimated lighting onto the model from the scene. Since this is done in real-time, it often carries a big performance hit.
* Model Placement: Where the model should be placed within the AR scene (Floor or Wall).

## Reporting Issues and Suggesting Improvements
In order to report a bug or suggest features/enhancements for future versions, head over to the [Bitbucket Issue Tracker](https://bitbucket.org/alecbiela/ccms-model-viewer/issues) for the project (Note: You will need to create a free Bitbucket account if you do not already have one). You may also send me a private message on the ConcreteCMS marketplace (Username: alecbiela).

### Issues
For bug reporting, please include the following information in your ticket:
* What you were trying to do when the issue occurred (what you expected to happen versus what actually happened).
* Any error text or message(s) displayed at the time of the issue.
* The steps to reproduce the error, if possible.

### Features
Apart from a few tweaks and additions, this is essentially a port of Google's `<model-viewer>` web component. As such, the ability to add new features to the viewer itself is somewhat limited. You may review the documentation and examples of the model viewer [on the project's website](https://modelviewer.dev/). If the idea looks like it's possible within the confines of the project, or has something to do with the CMS implementation, feel free to suggest it as an "enhancement" or "proposal".

## License
This add-on and the asset build scripts are Copyright &copy; 2024 Alec Bielanos. They are both distributed under the **Apache License, version 2.0**. Additional details are located in the `LICENSE.txt` file.

### Third-Party Licenses
The file `icon.png` is are derived from Bootstrap Icons, which is distributed under the MIT license.
See `LICENSE.icon.txt` for further information.

This project contains code derived from the ConcreteCMS project, which is distributed under the MIT license.
See `LICENSE.ccms.txt` for further information.