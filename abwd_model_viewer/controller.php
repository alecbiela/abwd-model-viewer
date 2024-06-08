<?php
namespace Concrete\Package\AbwdModelViewer;

defined('C5_EXECUTE') or die('Access Denied.');

use \Concrete\Core\Package\Package;
use \Concrete\Core\Block\BlockType\BlockType;
use \Concrete\Core\Asset\AssetList;
use \Concrete\Core\Asset\Asset;


class Controller extends Package
{
    protected $pkgHandle = 'abwd_model_viewer';
    protected $appVersionRequired = '9.3.2';
    protected $phpVersionRequired = '7.4.36';
    protected $pkgVersion = '1.0';

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
        $bt = BlockType::getByHandle('model_viewer');
        if (!is_object($bt)) {
            $bt = BlockType::installBlockType('model_viewer', $pkg);
        }
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

    public function on_start(){
        //TODO: Test if js assets can be loaded in the footer
        $al = AssetList::getInstance();
        $al->register('javascript', 'abwd-model-viewer', 'js/viewer.min.js', array('version' => '1.0.0'), 'abwd_model_viewer');
        $al->register('css', 'abwd-model-viewer', 'css/viewer.min.css', array('version' => '1.0.0'), 'abwd_model_viewer');
    }
}