<?php
namespace Lownto\RemoteConfig\Di\Code\Reader;

use Magento\Framework\Exception\FileSystemException;

class ClassesScanner extends \Magento\Setup\Module\Di\Code\Reader\ClassesScanner
{
    private const HTTP_SUPPORTED_CLASSES = [];
    /**
     * Retrieves list of classes for given path
     *
     * @param string $path
     * @return array
     * @throws FileSystemException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getList($path)
    {
        if (!str_starts_with($path, 'http')) {
            return parent::getList($path);
        } else {
            return self::HTTP_SUPPORTED_CLASSES;
        }
    }
}
