<?php
namespace Application\Block\ModelViewer;
use Concrete\Core\Block\BlockController;

class Controller extends BlockController {
    protected $btTable = "btModelViewer";
    protected $btDefaultSet = "multimedia";
    protected $btSupportsInlineAdd = false;
    protected $btSupportsInlineEdit = false;
    protected $btInterfaceWidth = "700";
    protected $btInterfaceHeight = "600";
    protected $btExportFileColumns = ['fileID', 'binaryFileID', 'posterFileID'];

    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputLifetime = 0;

    protected $helpers = ['form'];

    public function getBlockTypeName() { return t('3D Model Viewer'); }
    public function getBlockTypeDescription() { return t('Load, view, and control 3D models on your page.'); }
    public function getRequiredFeatures(): array { return [Features::FILES, Features::FORMS];}

    private function getFileObject($id): ?\Concrete\Core\Entity\File\File {
        return File::getByID($id)->getApprovedVersion();
    }

    private function hydrateArgs(){
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
    }

    private function buildAttrString(){
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
    }

    public function save($data){
        $args = [
            'fileID' => max(0, (int) $data['fileID']),
            'binaryFileID' => max(0, (int) $data['binaryFileID']),
            'posterFileID' => max(0, (int) $data['posterFileID']),
        ];
        parent::save($args);
    }

    public function view(){

    }
}