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

    public function verify($userEmail = null, $userPassword = null)
    {
        $oldEmail = $this->config->user_email;
        $oldPassword = $this->config->user_password;
        $oldCheckCache = $this->check_cache;

        if ($userEmail && $userPassword) {
            $this->config->user_email = $userEmail;
            $this->config->user_password = $userPassword;
            $this->check_cache = 0;
        }

        try {
            return $this->authenticate();
        } finally {
            $this->config->user_email = $oldEmail;
            $this->config->user_password = $oldPassword;
            $this->check_cache = $oldCheckCache;
        }
    }
}
