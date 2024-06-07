<?php
namespace Concrete\Package\AbwdModelViewer;

defined('C5_EXECUTE') or die('Access Denied.');

use \Concrete\Core\Package\Package;
use \Concrete\Core\Block\BlockType\BlockType;

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

    public function install()
    {
        if (version_compare(phpversion(), $phpVersionRequired, '<')) {
            throw new \Exception('This package requires a minimum PHP version of '.$phpVersionRequired.' to run correctly.');
        }
        $pkg = parent::install();
        BlockType::installBlockType('model_viewer', $pkg);
    }
}