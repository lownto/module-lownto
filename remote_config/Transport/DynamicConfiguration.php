<?php

namespace Lownto\RemoteConfig\Transport;

use \Magento\Support\Model\Report\Group\Modules\Modules;

class DynamicConfiguration implements \Cmtickle\EventThing\Transport\TransportInterface
{
    protected Modules $modules;
    protected \Magento\Framework\Serialize\Serializer\Json $serialize;

    public function __construct(
        Modules $modules,
        \Magento\Framework\Serialize\Serializer\Json $serialize
    ) {
        $this->modules = $modules;
        $this->serialize = $serialize;
    }

    protected function getRestUrl()
    {
        if (!$this->modules->isModuleEnabled('Lownto_DynamicConfiguration')) {
            throw new \Exception('Dynamic Configuration module must be installed and enabled.');
        }

        return rtrim(
            $this->modules->getModulePath('Lownto_DynamicConfiguration'),
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

        return $this->serialize->Unserialize($response ?: $data);
    }
}
