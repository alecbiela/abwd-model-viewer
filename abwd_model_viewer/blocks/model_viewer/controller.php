<?php
namespace Concrete\Package\AbwdModelViewer\Block\ModelViewer;
use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController {
    protected $btTable = "btModelViewer";
    protected $btDefaultSet = "multimedia";
    protected $btSupportsInlineAdd = false;
    protected $btSupportsInlineEdit = false;
    protected $btInterfaceWidth = "700";
    protected $btInterfaceHeight = "600";
    protected $btExportFileColumns = ['fileID', 'posterFileID'];

    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputLifetime = 0;

    protected $helpers = ['form'];

    public function getBlockTypeName() { return t('3D Model Viewer'); }
    public function getBlockTypeDescription() { return t('Load, view, and control 3D models on your page.'); }
    public function getRequiredFeatures(): array { return [Features::FILES, Features::FORMS];}

    private function getFileObject($id): ?\Concrete\Core\Entity\File\Version {
        return File::getByID($id)->getApprovedVersion();
    }

    /**
     * Builds the attribute string to go inside <model-viewer>
     * This is done at save-time so it doesn't need to be remade for view() calls
     */
    private function buildAttrString($data, $model, $poster, &$err){
        $attrArr = [];
        $classArr = [];
        $styleArr = [];
        $classArr[] = 'model-viewer';
        
        /* Parse the args into the attribute array */
        // Model data
        $modelFile = $this->getFileObject($model);
        if(strtolower($modelFile->getExtension()) !== 'glb' && strtolower($modelFile->getExtension()) !== 'gltf'){
            $err->add(t('Invalid model file - you must use a model with a .glb or a .gltf file extension.'));
        }else {
            $attrArr[] = 'src="'.$modelFile->getURL().'"';
        }

        if(max(0, (int) $poster) > 0){
            $attrArr[] = 'poster="'.$this->getFileObject($poster)->getURL().'"';
        } else {
            $err->add(t('You must specify a poster image.'));
        }

        if($data["model"]["alt"] !== '') {
            $attrArr[] = 'alt="'.$data["model"]["alt"].'"';
        } else {
            $err->add(t('You must specify alternative text.'));
        }
        $attrArr[] = 'loading="'.$data["model"]["loadingType"].'"';
        $attrArr[] = 'reveal="'.$data["model"]["activationType"].'"';
      
        // Controls
        if($data["controls"]["enableOrbit"] || 
        $data["controls"]["enableZoom"] || 
        $data["controls"]["enablePan"] || 
        $data["controls"]["enableButtons"]) $attrArr[] = 'camera-controls';

        if(!$data["controls"]["enableOrbit"]) $attrArr[] = 'disable-orbit';
        else $attrArr[] = 'orbit-sensitivity="'.$data["controls"]["orbitSensitivity"].'"';
        if(!$data["controls"]["enableZoom"]) $attrArr[] = 'disable-zoom';
        else $attrArr[] = 'zoom-sensitivity="'.$data["controls"]["zoomSensitivity"].'"';
        if(!$data["controls"]["enablePan"]) $attrArr[] = 'disable-pan';
        else $attrArr[] = 'pan-sensitivity="'.$data["controls"]["panSensitivity"].'"';

        if($data["controls"]["enableButtons"] === true) $attrArr[] = 'ui-buttons';
      
        // Style
        if($data["style"]["backgroundColor"] !== '') $styleArr[] = 'background: '.$data["style"]['backgroundColor'].';';
        if($data["style"]["isResponsive"]) $classArr[] = 'responsive';
        else{
            if($data["style"]["dimensionWidthValue"] === '' || $data["style"]["dimensionHeightValue"] === ''){
                $err->add(t('You must specify a width and height when responsive styling is disabled.'));
            }
            $units = array('px'=>'px', 'pc'=>'%');
            $styleArr[] = 'width: '.$data["style"]["dimensionWidthValue"].$units[$data["style"]["dimensionWidthUnits"]].';';
            $styleArr[] = 'height: '.$data["style"]["dimensionHeightValue"].$units[$data["style"]["dimensionHeightUnits"]].';';
        }
        $classArr[] = 'styling-'.$data["style"]["styling"];
      
        // A11y
        if($data["accessibility"]["enableA11y"]){
            $attrArr[] = 'a11y="'.json_encode($data["accessibility"]["a11yRules"]).'"';
        }

        // AR
        if($data["ar"]["enableAR"]){
            $attrArr[] = 'ar';
            if($data["ar"]["enableResizingAR"]) $attrArr[] = 'ar-scale="auto"';
            else $attrArr[] = 'ar-scale="fixed"';
            if($data["ar"]["enableEstimationAR"]) $attrArr[] = 'xr-environment';
            $attrArr[] = 'ar-placement="'.$data["ar"]["placementAR"].'"';
        }

        // Combine
        if(!empty($classArr)) $attrArr[] = 'class="'.implode(' ', $classArr).'"';
        if(!empty($styleArr)) $attrArr[] = 'style="'.implode(' ', $styleArr).'"';

        $attrStr = implode(' ', $attrArr);
        return gzdeflate($attrStr);
    }

    /**
     * Format args for storage in a single database column
     * Note that the file IDs (poster and model) are stored in their own columns
     * to ensure they are dynamic when exported (such as when exporting/importing site content)
     */
    private function formatArgs($data){
        $bSettings = [
            "model" => [
                "alt" => $data["alt"],
                "activationType" => $data["activationType"],
                "loadingType" => $data["loadingType"]
            ],
            "controls" => [
                "enableOrbit" => (array_key_exists('enableOrbit', $data) && $data["enableOrbit"] === 'yes'),
                "orbitSensitivity" => $data["orbitSensitivity"],
                "enableZoom" => (array_key_exists('enableZoom', $data) && $data["enableZoom"] === 'yes'),
                "zoomSensitivity" => $data["zoomSensitivity"],
                "enablePan" => (array_key_exists('enablePan', $data) && $data["enablePan"] === 'yes'),
                "panSensitivity" => $data["panSensitivity"],
                "enableButtons" => (array_key_exists('enableButtons', $data) && $data["enableButtons"] === 'yes')
            ],
            "style" => [
                "backgroundColor" => $data["backgroundColor"],
                "isResponsive" => (isset($data['isResponsive']) && $data['isResponsive'] === 'yes'),
                "dimensionWidthValue" => (array_key_exists('dimensionWidthValue', $data)) ? $data["dimensionWidthValue"] : '100',
                "dimensionWidthUnits" => (array_key_exists('dimensionWidthUnits', $data)) ? $data["dimensionWidthUnits"] : 'px',
                "dimensionHeightValue" => (array_key_exists('dimensionHeightValue', $data)) ? $data["dimensionWidthValue"] : '100',
                "dimensionHeightUnits" => (array_key_exists('dimensionHeightUnits', $data)) ? $data["dimensionHeightUnits"] : 'px',
                "styling" => 'minimal'
            ],
            "accessibility" => [
                "enableA11y" => (array_key_exists('enableA11y', $data) && $data["enableA11y"] === 'yes'),
            ],
            "ar" => [
                "enableAR" => (array_key_exists('enableAR', $data) && $data["enableAR"] === 'yes'),
                "enableResizingAR" => (array_key_exists('enableResizingAR', $data) && $data["enableResizingAR"] === 'yes'),
                "enableEstimationAR" => (array_key_exists('enableEstimationAR', $data) && $data["enableEstimationAR"] === 'yes'),
                "placementAR" => (array_key_exists('placementAR', $data)) ? $data["placementAR"] : ''
            ]    
        ];
        if($bSettings["accessibility"]["enableA11y"] === true){
            $a11yRules = array('a11yRules' => [
                'interaction-prompt' => $data["a11yInteractionPrompt"],
                'front' => $data["a11yFront"],
                'back' => $data["a11yBack"],
                'left' => $data["a11yLeft"],
                'right' => $data["a11yRight"],
                'upper-front' => $data["a11yUpperFront"],
                'upper-back' => $data["a11yUpperBack"],
                'upper-left' => $data["a11yUpperLeft"],
                'upper-right' => $data["a11yUpperRight"],
                'lower-front' => $data["a11yLowerFront"],
                'lower-back' => $data["a11yLowerBack"],
                'lower-left' => $data["a11yLowerLeft"],
                'lower-right' => $data["a11yLowerRight"]
            ]);
            $bSettings = array_merge($bSettings["accessibility"], $a11yRules);
        }
        
        return $bSettings;
    }

    /**
     * Builds attribute string and saves data to the database
     */
    public function save($data){
        // $err is passed by reference to the functions below
        $err = $this->app->make('helper/validation/error');

        // Model file is required at a bare minimum
        if(max(0, (int) $data['fileID']) > 0){
            $bSettings = $this->formatArgs($data, $err);
            $aString = $this->buildAttrString($bSettings, $data["fileID"], $data["posterFileID"], $err);
            $args = [
                'fileID' => max(0, (int) $data['fileID']),
                'posterFileID' => max(0, (int) $data['posterFileID']),
                'bSettings' => json_encode($bSettings),
                'aString' => $aString
            ];
        } else $err->add(t('You must specify a 3D model file.'));

        // If any errors were found during the saving process, do not save the block
        // and output the errors to the user instead
        if($err->has()){
            print t('There were errors with saving the 3D Model Block:');
            $err->output();
            die();
        }

        parent::save($args);
    }

    /**
     * Runs when adding a new instance of this block
     */
    public function add(){
        $defaults = array(
            'ar' => [
                'enableAR' => false,
                'placementAR' => 'floor',
                'enableResizingAR' => false,
                'enableEstimationAR' => false
            ],
            'model' => [
                'alt' => '',
                'loadingType' => 'auto',
                'activationType' => 'auto'
            ],
            'style' => [
                'styling' => 'minimal',
                'isResponsive' => true,
                'backgroundColor' => '',
                'dimensionWidthUnits' => 'px',
                'dimensionHeightUnits' => 'px',
                'dimensionWidthValue' => '',
                'dimensionHeightValue' => ''
            ],
            'controls' => [
                'enablePan' => true,
                'enableZoom' => true,
                'enableOrbit' => true,
                'enableButtons' => false,
                'panSensitivity' => '1.0',
                'zoomSensitivity' => '1.0',
                'orbitSensitivity' => '1.0'
            ],
            'accessibility' => [
                'enableA11y' => false,
                'a11yRules' => [
                    'interaction-prompt' => '',
                    'front' => '',
                    'back' => '',
                    'left' => '',
                    'right' => '',
                    'upper-front' => '',
                    'upper-back' => '',
                    'upper-left' => '',
                    'upper-right' => '',
                    'lower-front' => '',
                    'lower-back' => '',
                    'lower-left' => '',
                    'lower-right' => ''
                ]
            ]
        );
        $this->set('blockData', $defaults);
        $this->set('fileID', null);
        $this->set('posterFileID', null);
    }

    /**
     * Runs when an existing instance of a block is edited
     */
    public function edit(){
        $blockData = json_decode($this->bSettings, true);
        if(!$blockData["accessibility"]["enableA11y"]){
            $blockData["accessibility"]["a11yRules"] = [
                'interaction-prompt' => '',
                'front' => '',
                'back' => '',
                'left' => '',
                'right' => '',
                'upper-front' => '',
                'upper-back' => '',
                'upper-left' => '',
                'upper-right' => '',
                'lower-front' => '',
                'lower-back' => '',
                'lower-left' => '',
                'lower-right' => ''
            ];
        }
        $this->set('blockData', $blockData);
        $this->set('fileID', $this->fileID);
        $this->set('posterFileID', $this->posterFileID);
    }

    /**
     * Runs when the block is loaded on the frontend for display
     */
    public function view(){
        $attrString = gzinflate($this->aString);
        $this->set('attrString', $attrString);
    }

    /**
     * Registers necessary css and javascript for the block
     */
    public function registerViewAssets($outputContent = ''){
        $this->requireAsset('abwd-model-viewer');
    }
}