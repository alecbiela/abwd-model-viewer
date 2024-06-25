<?php
namespace Concrete\Package\AbwdModelViewer\Block\ModelViewer;
use Concrete\Core\Utility\Service\Identifier;
use Concrete\Core\Block\BlockController;
use Concrete\Core\File\File;
use \Config;
use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Form\Service\Widget\Color;
use Concrete\Core\Page\Page;

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
        $f = File::getByID($id);
        return (is_object($f) && $f->getFileID()) ? File::getByID($id)->getApprovedVersion() : null;
    }

    /**
     * If we are on version 8, include the necessary backporting assets for the block editor
     */
    private function backportIfNeeded(){
        $v = intval(mb_substr(Config::get('concrete.version'), 0, 1));
        if($v < 9){
            $this->requireAsset('javascript', 'version-8-backport');
            $this->requireAsset('css', 'version-8-backport');
        }
    }

    /**
     * Returns an array of default block settings
     * @param void
     * @return array
     */
    private function getDefaults(){
        return array(
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
    }

    /**
     * Builds the attribute string to go inside <model-viewer>
     * This is done at save-time so it doesn't need to be remade for view() calls
     */
    private function buildAttrString($data){
        $attrArr = [];
        $classArr = [];
        $styleArr = [];
        $classArr[] = 'model-viewer';
        
        /* Parse the args into the attribute array */
        $attrArr[] = 'alt="'.$data["model"]["alt"].'"';
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
        if($data["style"]["isResponsive"]) {
            $classArr[] = 'responsive';
        } else {
            $units = array('px'=>'px', 'pc'=>'%');
            $styleArr[] = 'width: '.$data["style"]["dimensionWidthValue"].$units[$data["style"]["dimensionWidthUnits"]].';';
            $styleArr[] = 'height: '.$data["style"]["dimensionHeightValue"].$units[$data["style"]["dimensionHeightUnits"]].';';
        }
        $classArr[] = 'styling-'.$data["style"]["styling"];
      
        // A11y
        if($data["accessibility"]["enableA11y"]){
            $attrArr[] = 'a11y="'.htmlspecialchars(json_encode($data["accessibility"]["a11yRules"]), ENT_QUOTES, 'UTF-8').'"';
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
                "dimensionWidthValue" => (array_key_exists('dimensionWidthValue', $data)) ? $data["dimensionWidthValue"] : '',
                "dimensionWidthUnits" => (array_key_exists('dimensionWidthUnits', $data)) ? $data["dimensionWidthUnits"] : 'px',
                "dimensionHeightValue" => (array_key_exists('dimensionHeightValue', $data)) ? $data["dimensionWidthValue"] : '',
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
            $bSettings["accessibility"] += $a11yRules;
        }
        
        return $bSettings;
    }

    /**
     * Validate block information before save
     * @param array $args - data from block form
     * @return object|void - error object if errors exist
     */
    public function validate($args){
        $err = $this->app->make('helper/validation/error');

        // Validate the model file
        if(isset($args['fileID']) && max(0, (int) $args['fileID']) > 0){
            $modelFile = $this->getFileObject($args['fileID']);
            if(!isset($modelFile)){
                $err->add(t('The model file could not be loaded from the file manager.'));
            } else if(isset($modelFile) && strtolower($modelFile->getExtension()) !== 'glb'){
                $err->add(t('Invalid file format - you must use a packaged GLB model with the (.glb) file extension. For more information on how to convert your model to GLB, please refer to the documentation.'));
            }
        } else $err->add(t('You must specify a 3D model file.'));

        // Validate the poster file
        if(isset($args['posterFileID']) && max(0, (int) $args['posterFileID']) > 0){
            $posterFile = $this->getFileObject($args['posterFileID']);
            if(!isset($posterFile)){
                $err->add(t('The poster file could not be loaded from the file manager.'));   
            }
        } else $err->add(t('You must specify a poster image.'));

        // Validate other required fields
        if($args['alt'] === '') $err->add(t('You must specify alternative text.'));
        $responsive = (isset($args['isResponsive']) && $args['isResponsive'] === 'yes');
        if(!$responsive){
            $w = (isset($args['dimensionWidthValue']) && $args['dimensionWidthValue'] !== '');
            $h = (isset($args['dimensionHeightValue']) && $args['dimensionHeightValue'] !== '');
            if(!$w || !$h){
                $err->add(t('You must specify a width and height when responsive styling is disabled.'));
            }
        }

        if($err->has()){
            return $err;
        }
    }

    /**
     * Saves data to the database
     */
    public function save($data){
        $bSettings = $this->formatArgs($data);
        $aString = $this->buildAttrString($bSettings);
        $args = [
            'fileID' => max(0, (int) $data['fileID']),
            'posterFileID' => max(0, (int) $data['posterFileID']),
            'bSettings' => json_encode($bSettings),
            'aString' => $aString
        ];

        parent::save($args);
    }

    /**
     * Runs when adding a new instance of this block
     */
    public function add(){
        $this->backportIfNeeded();
        $defaults = $this->getDefaults();
        $this->set('blockData', $defaults);
        $this->set('fileID', null);
        $this->set('posterFileID', null);

        $color = $this->app->make(Color::class);
        $this->set('color', $color); //Usage: $color->make($inputName, $value, $options = []);
        $fileManager = $this->app->make(FileManager::class);
        $this->set('fileManager', $fileManager); //Usage: $fileManager->file($inputID, $inputName, $chooseText, $preselectedFile = null, $args = [])
        
    }

    /**
     * Runs when an existing instance of a block is edited
     */
    public function edit(){
        $this->backportIfNeeded();
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
        $this->set('fileID', $this->getFileObject($this->fileID));
        $this->set('posterFileID', $this->getFileObject($this->posterFileID));
        $color = $this->app->make(Color::class);
        $this->set('color', $color); //Usage: $color->make($inputName, $value, $options = []);
        $fileManager = $this->app->make(FileManager::class);
        $this->set('fileManager', $fileManager); //Usage: $fileManager->file($inputID, $inputName, $chooseText, $preselectedFile = null, $args = [])
    }

    /**
     * Runs when the block is loaded on the frontend for display
     */
    public function view(){
        $c = Page::getCurrentPage();
        $this->set('c', $c);
        $mvid = $this->app->make(Identifier::class)->getString(8);
        $this->set('mvid', $mvid);
        $attrString = gzinflate($this->aString);

        // Load the files by their ID, and add their URLs to the attribute string
        // (checks to see if they exist, in case the files were deleted but the block is still around)
        $mdl = $this->getFileObject($this->fileID);
        if(is_object($mdl)){
            $this->set('src', $mdl->getURL());
        }
        $poster = $this->getFileObject($this->posterFileID);
        if(is_object($poster)){
            $this->set('poster', $poster->getURL());
        }

        $this->set('attrString', $attrString);
    }

    /**
     * Registers necessary css and javascript for the block
     */
    public function registerViewAssets($outputContent = ''){
        $v = intval(mb_substr(Config::get('concrete.version'), 0, 1));
        if($v === 9){
            $this->requireAsset('abwd-model-viewer-9');
        } else {
            $this->requireAsset('abwd-model-viewer-8');
            $this->addFooterItem('<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>');
        }
    }
}