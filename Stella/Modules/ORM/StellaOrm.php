<?php


namespace Stella\Modules\ORM;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Stella\Core\Configuration;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotFoundException;
use Stella\Exceptions\Core\Configuration\ConfigurationFileNotYmlException;

/**
 * Class StellaOrm
 *
 * @author Benjamin Gil FLores
 * @version 0.3
 * @since 0.2
 * @package Stella\Modules\ORM
 */
class StellaOrm
{
    /**
     * @var Configuration
     */
    private Configuration $configuration;

    /**
     * StellaOrm constructor.
     */
    public function __construct ()
    {
        $this->configuration = new Configuration();
    }

    /**
     * @return EntityManager
     * @throws ConfigurationFileNotFoundException
     * @throws DBALException
     * @throws ORMException
     */
    public function getEntityManager (): EntityManager
    {
        $config = Setup::createAnnotationMetadataConfiguration(
            array(PROJECT_DIR . "/src"),
            true,
            null,
            null,
            false
        );

        return EntityManager::create(DriverManager::getConnection($this->getDatabaseConfiguration()), $config);
    }

    /**
     * @return array
     * @throws ConfigurationFileNotFoundException
     */
    private function getDatabaseConfiguration (): array
    {
        $configuration = $this->configuration->getConfigurationOfFile('orm');

        return array(
            'dbname' => $configuration['dbname'],
            'user' => $configuration['user'],
            'password' => $configuration['password'],
            'host' => $configuration['host'],
            'driver' => $configuration['driver']
        );
    }
}