<?php
namespace app\components;

use yii\base\BootstrapInterface;

/**
 * Class ModuleBootstrap
 *
 * @package app\extensions
 */
class ModuleBootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $oApplication
     */
    public function bootstrap($oApplication)
    {
        $aModuleList = $oApplication->getModules();

        foreach ($aModuleList as $sKey => $aModule) {
            if (is_array($aModule) && strpos($aModule['class'], 'app\module') === 0) {
                $sFilePathConfig = __DIR__ . '/../module/api/' . $sKey . '/config/_routes.php';

                if (file_exists($sFilePathConfig)) {
                    $oApplication->getUrlManager()->addRules(require($sFilePathConfig));
                }
            }
        }
//        print_r($oApplication->getUrlManager());die;
    }
}