<?php

namespace Lownto\RemoteConfig\Di\Operation;

class RepositoryGenerator extends \Magento\Setup\Module\Di\App\Task\Operation\RepositoryGenerator
{
    private \Magento\Setup\Module\Di\Code\Reader\ClassesScanner $classesScanner;
    private \Magento\Setup\Module\Di\Code\Scanner\RepositoryScanner|Scanner\RepositoryScanner $repositoryScanner;
    private \Magento\Setup\Module\Di\Code\Scanner\ConfigurationScanner|Scanner\ConfigurationScanner $configurationScanner;
    private array $data;

    /**
     * @param \Magento\Setup\Module\Di\Code\Reader\ClassesScanner $classesScanner
     * @param \Magento\Setup\Module\Di\Code\Scanner\RepositoryScanner $repositoryScanner
     * @param \Magento\Setup\Module\Di\Code\Scanner\ConfigurationScanner $configurationScanner
     * @param array $data
     */
    public function __construct(
        \Magento\Setup\Module\Di\Code\Reader\ClassesScanner $classesScanner,
        \Magento\Setup\Module\Di\Code\Scanner\RepositoryScanner $repositoryScanner,
        \Magento\Setup\Module\Di\Code\Scanner\ConfigurationScanner $configurationScanner,
        $data = []
    ) {
        $this->repositoryScanner = $repositoryScanner;
        $this->data = $data;
        $this->classesScanner = $classesScanner;
        $this->configurationScanner = $configurationScanner;
        parent::__construct($classesScanner, $repositoryScanner, $configurationScanner);
    }

    /**
     * @inheri
     */
    public function doOperation()
    {
        $httpPaths = [];
        foreach ($this->data['paths'] as $path) {
            if (str_starts_with($path, 'http')) {
                $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                /* @todo: Make this dynamic for all files */
                $httpPaths[] = $path . 'etc/di.xml';
                $httpPaths[] = $path . 'etc/frontend/di.xml';
                $httpPaths[] = $path . 'etc/adminhtml/di.xml';
            } else {
                $this->classesScanner->getList($path);
            }
        }
        $this->repositoryScanner->setUseAutoload(false);
        $files = $this->configurationScanner->scan('di.xml');
        $files = array_merge($files, $httpPaths);
        $repositories = $this->repositoryScanner->collectEntities($files);
        foreach ($repositories as $entityName) {
            class_exists($entityName);
        }
    }
}
