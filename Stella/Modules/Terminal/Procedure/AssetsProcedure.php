<?php


namespace Stella\Modules\Terminal\Procedure;

use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;

/**
 * -----------------------------------------
 * Final Class AssetsProcedure
 * -----------------------------------------
 *
 * {@inheritDoc}
 * This procedure handles the assets files of the
 * application, creating symbolic links between public
 * and src, loading the stella assets to the public folder
 * and so on.
 *
 * @author Benjamin Gil Flores
 * @version NaN
 * @package Stella\Modules\Terminal\Procedure
 */
final class AssetsProcedure extends Procedure
{
    /**
     * {@inheritDoc}
     *
     * @return $this
     */
    public function setOptions(): self
    {
        return $this;
    }

    /**
     * Creates symbolic link between application src
     * assets and public directory app bundle,
     * no options fot this action
     *
     * <code>
     *     php vendor/bin/stella assets:install
     * </code>
     *
     * @return array
     * @throws ConfigurationFileNotFoundException
     * @throws ConfigurationFileNotYmlException
     */
    protected function install (): array
    {
        $serverConf = $this->configuration->getConfigurationOfFile(PROJECT_DIR_CLI . "/config/server.yml");
        $bundlesDirPath = PROJECT_DIR_CLI . "/public/bundles";

        // Creates bundles directory
        if (!file_exists($bundlesDirPath)) {
            mkdir($bundlesDirPath, 0777, true);
        }

        if (isset($serverConf['app_name']) && isset($serverConf['resources'])) {
            $appName = $serverConf['app_name'];
            $resourcesPath = PROJECT_DIR_CLI . $serverConf['resources'];

            // Creates resources directory
            if (!is_dir($resourcesPath)) {
                mkdir($resourcesPath, 0777, true);
            }

            // Do symbolic link if possible
            if (is_dir($bundlesDirPath . "/$appName")) {
                if (is_link("public/bundles/$appName")) {
                    return array(
                        'format' => 'warning',
                        'message' => 'Symlink already created. Delete the linked directory and run the command again to link again'
                    );
                }

                if (symlink($resourcesPath, "public/bundles/$appName")) {
                    return array(
                        'format' => 'success',
                        'message' => 'Symlinks created with success'
                    );
                }
            }

            if (symlink($resourcesPath, "public/bundles/$appName")) {
                return array(
                    'format' => 'success',
                    'message' => 'Symlinks created with success'
                );
            }

            return array(
                'format' => 'fatal',
                'message' => 'Could not create symbolic links between assets directories :('
            );
        }

        return array(
            'format' => 'danger',
            'message' => 'Impossible to crate symlink, ensure that resources and app_name are defined in server conf file'
        );
    }

    /**
     * Makes a copy of the stella assets to the application
     * public bundles directory
     *
     * <code>
     *     php vendor/bin/stella assets:copy
     * </code>
     *
     * @return array
     */
    protected function copy (): array
    {
        $stellaBundleDirPath = PROJECT_DIR_CLI . "/public/bundles/stella";
        $stellaAssetsDirPath = dirname(__DIR__, 3) . "/Resources/assets";

        if ($this->recursiveCopyFiles($stellaAssetsDirPath, $stellaBundleDirPath)) {
            return array(
                'format' => 'success',
                'message' => "Stella assets copied with success in $stellaBundleDirPath"
            );
        }

        return array(
            'format' => 'fatal',
            'message' => 'Stella assets could not be installed :('
        );
    }

    /**
     * Copies directory contents to another directory
     * recursively
     *
     * @param string $dirname
     * @param string $targetDirname
     * @return bool
     */
    private function recursiveCopyFiles (string $dirname, string $targetDirname): bool
    {
        $stellaAssets = opendir($dirname);
        mkdir($targetDirname, 0777, true);

        while (false !== ($file = readdir($stellaAssets))) {
            if (($file != ".") && ($file != "..")) {
                if (is_dir($dirname . "/" . $file)) {
                    $this->recursiveCopyFiles($dirname . "/" . $file, $targetDirname . "/" . $file);
                } else {
                    \copy($dirname . "/" . $file, $targetDirname . "/" . $file);
                }
            }
        }

        closedir($stellaAssets);
        return true;
    }
}