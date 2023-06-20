<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Framework\Filesystem\Directory;

use Magento\Framework\Filesystem\DriverPool;

/**
 * The factory of the filesystem directory instances for read operations.
 *
 * @api
 */
class ReadFactory
{
    /**
     * Pool of filesystem drivers
     *
     * @var DriverPool
     */
    private $driverPool;

    /**
     * Constructor
     *
     * @param DriverPool $driverPool
     */
    public function __construct(DriverPool $driverPool)
    {
        $this->driverPool = $driverPool;
    }

    /**
     * Create a readable directory
     *
     * @param string $path
     * @param string $driverCode
     * @return ReadInterface
     */
    public function create($path, $driverCode = DriverPool::FILE)
    {
        /* Change to allow reading dynamic configuration */
        if (str_starts_with($path, 'http://')) {
            $driverCode = DriverPool::HTTP;
        }
        /* End of change */

        $driver = $this->driverPool->getDriver($driverCode);
        $factory = new \Magento\Framework\Filesystem\File\ReadFactory(
            $this->driverPool
        );

        return new Read(
            $factory,
            $driver,
            $path,
            new PathValidator($driver)
        );
    }
}
