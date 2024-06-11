<?php
namespace Concrete\Package\AbwdModelViewer;

defined('C5_EXECUTE') or die('Access Denied.');

use \Config;
use \Concrete\Core\Package\Package;
use \Concrete\Core\Block\BlockType\BlockType;
use \Concrete\Core\Asset\AssetList;
use \Concrete\Core\Asset\Asset;
use Concrete\Core\Support\Facade\Application;
use \Concrete\Package\AbwdModelViewer\Asset\JavascriptModuleAsset;

class Controller extends Package
{
    protected $pkgHandle = 'abwd_model_viewer';
    protected $appVersionRequired = '9.3.2';
    protected $phpVersionRequired = '7.4.36';
    protected $pkgVersion = '1.0.0';
    protected $pkgAutoloaderRegistries = array('src/Asset' => 'Concrete\Package\AbwdModelViewer\Asset');

    public function getPackageDescription()
    {
        return t('Adds a 3D model viewer block to the editor. Supports .glb and .gltf model files.');
    }

    public function getPackageName()
    {
        return t('3D Model Viewer');
    }

    private function installOrUpgrade($pkg = null){
        if(is_null($pkg)) $pkg = Package::getByHandle('abwd_model_viewer');

        // Add block type if it doesn't exist yet
        $bt = BlockType::getByHandle('model_viewer');
        if (!is_object($bt)) {
            $bt = BlockType::installBlockType('model_viewer', $pkg);
        }

        // Add glb and gltf to allowed file manager types if they aren't yet
        $app = Application::getFacadeApplication();
        $config = $app->make('config');
        $helper_file = $app->make('helper/concrete/file');
        
        $file_access_file_types = $helper_file->unserializeUploadFileExtensions($config->get('concrete.upload.extensions'));
        if(!in_array('glb', $file_access_file_types)) $file_access_file_types[] = 'glb';
        if(!in_array('gltf', $file_access_file_types)) $file_access_file_types[] = 'gltf';

        $types = $helper_file->serializeUploadFileExtensions($file_access_file_types);
        Config::save('concrete.upload.extensions', $types);
    }

    public function install()
    {
        if (version_compare(phpversion(), $this->phpVersionRequired, '<')) {
            throw new \Exception('This package requires a minimum PHP version of '.$this->phpVersionRequired.' to run correctly.');
        }
        $pkg = parent::install();
        $this->installOrUpgrade($pkg);
    }

    public function upgrade(){
        parent::upgrade();
        $this->installOrUpgrade();
    }

    public function uninstall(){
        parent::uninstall();

        // Remove glb and gltf from allowed file manager types
        $app = Application::getFacadeApplication();
        $config = $app->make('config');
        $helper_file = $app->make('helper/concrete/file');
        
        $file_access_file_types = $helper_file->unserializeUploadFileExtensions($config->get('concrete.upload.extensions'));

        if (($key = array_search('glb', $file_access_file_types)) !== false) {
            unset($file_access_file_types[$key]);
        }
        if (($key = array_search('gltf', $file_access_file_types)) !== false) {
            unset($file_access_file_types[$key]);
        }

        $types = $helper_file->serializeUploadFileExtensions($file_access_file_types);
        Config::save('concrete.upload.extensions', $types);
    }

    public function on_start(){
        $al = AssetList::getInstance();
        $al->register('javascript', 'abwd-model-viewer', 'js/viewer.min.js', array('version' => '1.0.0'), 'abwd_model_viewer');
        $al->register('css', 'abwd-model-viewer', 'css/viewer.min.css', array('version' => '1.0.0'), 'abwd_model_viewer');
        $al->register('javascript-inline', 'meshopt-support', 'self.ModelViewerElement = self.ModelViewerElement || {}; self.ModelViewerElement.meshoptDecoderLocation = "https://cdn.jsdelivr.net/npm/meshoptimizer/meshopt_decoder.js"', array('version' => '0.20.0', 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => false, 'combine' => false), 'abwd_model_viewer');

        $o = new JavascriptModuleAsset('google-model-viewer');
        $o->register('https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js', array('version' => '3.5.0'), 'abwd_model_viewer');
        $al->registerAsset($o);
    }
}