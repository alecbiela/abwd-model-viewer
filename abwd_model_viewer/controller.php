<?php
namespace Concrete\Package\AbwdModelViewer;

defined('C5_EXECUTE') or die('Access Denied.');

use \Config;
use \Concrete\Core\Package\Package;
use \Concrete\Core\Block\BlockType\BlockType;
use \Concrete\Core\Asset\AssetList;
use \Concrete\Core\Asset\Asset;
use \Concrete\Package\AbwdModelViewer\Asset\JavascriptModuleAsset;

class Controller extends Package
{
    protected $pkgHandle = 'abwd_model_viewer';
    protected $appVersionRequired = '8.5.0';
    protected $phpVersionRequired = '7.4.0';
    protected $pkgVersion = '1.0.1';
    protected $pkgAutoloaderRegistries = array('src/Asset' => 'Concrete\Package\AbwdModelViewer\Asset');

    public function getPackageDescription()
    {
        return t('Adds a 3D model viewer block to the editor. Supports glTF Binary (.glb) model files.');
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

        // Add glb to allowed file manager types if they aren't yet
        $config = $this->app->make('config');
        $helper_file = $this->app->make('helper/concrete/file');
        
        $file_access_file_types = $helper_file->unserializeUploadFileExtensions($config->get('concrete.upload.extensions'));
        if(!in_array('glb', $file_access_file_types)) $file_access_file_types[] = 'glb';

        $types = $helper_file->serializeUploadFileExtensions($file_access_file_types);
        Config::save('concrete.upload.extensions', $types);
    }

    public function install()
    {
        if (version_compare(phpversion(), $this->phpVersionRequired, '<')) {
            throw new \Exception(t('This package requires a minimum PHP version of '.$this->phpVersionRequired.' to run correctly.'));
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

        // Remove glb from allowed file manager types
        $config = $this->app->make('config');
        $helper_file = $this->app->make('helper/concrete/file');
        
        $file_access_file_types = $helper_file->unserializeUploadFileExtensions($config->get('concrete.upload.extensions'));

        if (($key = array_search('glb', $file_access_file_types)) !== false) {
            unset($file_access_file_types[$key]);
        }

        $types = $helper_file->serializeUploadFileExtensions($file_access_file_types);
        Config::save('concrete.upload.extensions', $types);
    }

    public function on_start(){
        $al = AssetList::getInstance();
        $al->register('javascript', 'abwd-model-viewer', 'js/viewer.min.js', array('version' => $this->pkgVersion), 'abwd_model_viewer');
        $al->register('css', 'abwd-model-viewer', 'css/viewer.min.css', array('version' => $this->pkgVersion), 'abwd_model_viewer');
        $al->register('javascript-inline', 'meshopt-support', 'self.ModelViewerElement = self.ModelViewerElement || {}; self.ModelViewerElement.meshoptDecoderLocation = "https://cdn.jsdelivr.net/npm/meshoptimizer/meshopt_decoder.js"', array('version' => '0.20.0', 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => false, 'combine' => false), 'abwd_model_viewer');
        $al->register('javascript', 'version-8-backport', 'js/version8-backport.min.js', array('version' => $this->pkgVersion), 'abwd_model_viewer');
        $al->register('css', 'version-8-backport', 'css/version8-backport.min.css', array('version' => $this->pkgVersion), 'abwd_model_viewer');

        // Google Model Viewer - Version 4.0.0
        // License: https://github.com/google/model-viewer/blob/master/LICENSE (Apache 2.0)
        $o = new JavascriptModuleAsset('google-model-viewer');
        $o->register('https://cdn.jsdelivr.net/npm/@google/model-viewer@4.0.0/dist/model-viewer.min.js', array('version' => '4.0.0'), 'abwd_model_viewer');
        $al->registerAsset($o);

        // Two separate groups - only 1 will be required dynamically based on cms version
        $al->registerGroup('abwd-model-viewer-9', array(
            array('javascript','abwd-model-viewer'),
            array('css','abwd-model-viewer'),
            array('javascript-inline','meshopt-support'),
            array('javascript-module','google-model-viewer')
        ));
        $al->registerGroup('abwd-model-viewer-8', array(
            array('javascript','abwd-model-viewer'),
            array('css','abwd-model-viewer'),
            array('javascript-inline','meshopt-support')
        ));
    }
}