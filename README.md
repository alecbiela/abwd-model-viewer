# ConcreteCMS 3D Model Viewer Add-on

This is the main Bitbucket repository for the "ABWD Model Viewer" add-on for ConcreteCMS. The add-on itself is an unofficial port of
Google's `<model-viewer>` web component and is in no way affiliated with Google.

Specifically, this package adds a new block, "3D Model Viewer", to the "Multimedia" section of the block editor which allows the user 
to display a 3D model in GL Transmission Format (GLTF) and adjust various parameters of its control and display.

## Installation
Before attempting to install this add-on, ensure the following requirements are met:
* **Concrete5/ConcreteCMS:** Version `9.3.2` or later (Note: This is the current CMS version; additional versions will be tested for backwards compatibility).
* **PHP:** Version `7.4.36` or later.

To install the package, perform the following steps:
1. Copy the `abwd_model_viewer` folder into your website's `packages` folder.
2. Visit your website and log in.
3. In the Dashboard, navigate to the **Extend Concrete** or **Extend Concrete5** page (depending on your CMS version).
4. Press **Install** next to the `3D Model Viewer` package.
5. Wait for the page to refresh - You should receive a confirmation that the package was installed.

### A Note About File Types
Only GLTF-formatted models may be used in this model viewer; any type other than `.glb` and `.gltf` is not supported, and existing models will need to be converted to GLTF before they may be used. See [The Blender Documentation](https://docs.blender.org/manual/en/latest/addons/import_export/scene_gltf2.html) for more information on GLTF formatting.

This package will add `.glb` and `.gltf` to the allowed upload file types in the CMS (see Dashboard > System & Settings > Files > Allowed File Types).  **It will also remove these types from the allowed types list when the package is uninstalled**. If you still need to upload files with these types 
after the package is uninstalled, you will need to manually re-add these extensions to that list inside the dashboard.

#### What about binary files?
Currently, the model viewer does **not** support models with detached binary files (for example, a `.gltf` file with the model information and a separate `.bin` file with the model data). This would require allowing `.bin` files to be uploaded to the File Manager, which can present a security risk especially on Linux servers. 
Therefore, any uploaded models will need to be bundled into a single `.glb` or `.gltf` file.

## Features
Coming Soon.

## Reporting Issues and Suggesting Improvements
In order to report a bug or suggest features/enhancements for future versions, head over to the [Bitbucket Issue Tracker](https://bitbucket.org/alecbiela/ccms-model-viewer/issues) for the project (Note: You will need to create a free Bitbucket account if you do not already have one).

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

