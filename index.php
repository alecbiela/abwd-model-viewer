<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Model Viewer Test Functionality</title>
        <script async defer type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
        <style>
            .model-wrapper{
                width: 1299px;
                margin: auto;
            }
            model-viewer{
                width: 100vw;
                max-width: 100%;
                height: 56.25vw;
                max-height: 80vh;
            }
        </style>
    </head>
    <body>
        <?php include('./view.php'); ?>
    </body>
</html>