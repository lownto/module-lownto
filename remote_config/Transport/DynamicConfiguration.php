<?php

namespace Lownto\RemoteConfig\Transport;

use \Magento\Framework\Module\Manager as ModuleManager;
use \Magento\Framework\Module\Dir as ModuleDir;

class DynamicConfiguration implements \Cmtickle\EventThing\Transport\TransportInterface
{
    protected ModuleManager $moduleManager;
    protected ModuleDir $moduleDir;
    protected \Magento\Framework\Serialize\Serializer\Json $serialize;

    public function __construct(
        ModuleManager $moduleManager,
        ModuleDir $moduleDir,
        \Magento\Framework\Serialize\Serializer\Json $serialize
    ) {
        $this->moduleManager = $moduleManager;
        $this->moduleDir = $moduleDir;
        $this->serialize = $serialize;
    }

    protected function getRestUrl()
    {
        if (!$this->moduleManager->isEnabled('Lownto_DynamicConfiguration')) {
            throw new \Exception('Dynamic Configuration module must be installed and enabled.');
        }

        return rtrim(
            $this->moduleDir->getDir('Lownto_DynamicConfiguration'),
            '/'
            ) . '/lownto';
    }

    public function process(array $data):array
    {
        $curl = curl_init($this->getRestUrl());
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->serialize->serialize($data));
        $response = curl_exec($curl);

        return $response ? $this->serialize->Unserialize($response) : $data;
    }
}
