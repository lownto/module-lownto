<?php

namespace Lownto\RemoteConfig\Di\Operation;

class ApplicationCodeGenerator extends \Magento\Setup\Module\Di\App\Task\Operation\ApplicationCodeGenerator
{
    private \Magento\Setup\Module\Di\Code\Reader\ClassesScanner $classesScanner;
    protected \Magento\Setup\Module\Di\Code\Scanner\PhpScanner $phpScanner;
    private \Magento\Setup\Module\Di\Code\Scanner\DirectoryScanner $directoryScanner;
    private array $data;

    /**
     * @param \Magento\Setup\Module\Di\Code\Reader\ClassesScanner $classesScanner
     * @param \Magento\Setup\Module\Di\Code\Scanner\PhpScanner $phpScanner
     * @param \Magento\Setup\Module\Di\Code\Scanner\DirectoryScanner $directoryScanner
     * @param array $data
     */
    public function __construct(
        \Magento\Setup\Module\Di\Code\Reader\ClassesScanner $classesScanner,
        \Magento\Setup\Module\Di\Code\Scanner\PhpScanner $phpScanner,
        \Magento\Setup\Module\Di\Code\Scanner\DirectoryScanner $directoryScanner,
        $data = []
    ) {
        $this->data = $data;
        $this->classesScanner = $classesScanner;
        $this->phpScanner = $phpScanner;
        $this->directoryScanner = $directoryScanner;
    }

    /**
     * @inheritDoc
     */
    private function getFiles(array $paths): array
    {
        $files = [];

        foreach ($paths as $path) {
            if(str_starts_with($path, 'http')) {
                continue;
            }
            $this->classesScanner->getList($path);

            $files[] = $this->directoryScanner->scan(
                $path,
                $this->data['filePatterns'],
                $this->data['excludePatterns']
            );
        }

        return array_merge_recursive([], ...$files);
    }

    /**
     * @inheritdoc
     */
    public function doOperation()
    {
        if (array_diff(array_keys($this->data), ['filePatterns', 'paths', 'excludePatterns'])
            !== array_diff(['filePatterns', 'paths', 'excludePatterns'], array_keys($this->data))) {
            return;
        }

        foreach ($this->data['paths'] as $paths) {
            if (!is_array($paths)) {
                $paths = (array)$paths;
            }

            $files = $this->getFiles($paths);

            $entities = $this->phpScanner->collectEntities($files['php'] ?? []);
            foreach ($entities as $entityName) {
                class_exists($entityName);
            }
        }
    }
}
