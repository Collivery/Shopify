<?php

namespace App\Soap;

use Mds\Collivery;

class ColliverySoap extends Collivery
{
    public function __construct(array $config = [])
    {
        if (empty($config['cache'])) {
            $config['cache'] = storage_path().'/collivery';
        }
        parent::__construct($config);
    }

    public function verify($username, $password)
    {
        try {
            if (!$this->init()) {
                return false;
            }

            $this->client->authenticate($username, $password, null,
                [
                    'name' => $this->config->app_name.' mds/collivery/class',
                    'version' => $this->config->app_version,
                    'host' => $this->config->app_host,
                    'url' => $this->config->app_url,
                    'lang' => 'PHP '.phpversion(),
                ]);

            return true;
        } catch (\SoapFault $e) {
        }

        return false;
    }
}
