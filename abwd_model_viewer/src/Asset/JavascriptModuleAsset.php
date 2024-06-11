<?php
namespace Concrete\Package\AbwdModelViewer\Asset;

defined('C5_EXECUTE') or die('Access Denied.');

use \Concrete\Core\Asset\JavascriptAsset;
use HtmlObject\Element;

class JavascriptModuleAsset extends JavascriptAsset
{

    /**
     * @var bool
     */
    protected $assetSupportsMinification = false;

    /**
     * @var bool
     */
    protected $assetSupportsCombination = false;

    /**
     * @return bool
     */
    public function isAssetLocal()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getAssetType()
    {
        return 'javascript-module';
    }

    public function getOutputAssetType()
    {
        return 'javascript';
    }

    /**
     * @return string
     */
    public function getAssetHashKey()
    {
        return md5($this->assetURL);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $e = new Element('script');
        $e->type('module')->src($this->getAssetURL());

        return (string) $e;
    }

    /**
     * @return string|null
     */
    public function getAssetContents()
    {
        return null;
    }
}
