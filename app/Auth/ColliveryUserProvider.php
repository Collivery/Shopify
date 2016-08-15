<?php

    namespace App\Auth;

    use App\User;
    use Illuminate\Auth\EloquentUserProvider;
    use Illuminate\Contracts\Hashing\Hasher;
    use Mds\Auth\SoapService;
    use Mds\Client\SoapClient;

    class ColliveryUserProvider extends EloquentUserProvider
    {
        public function __construct(Hasher $contract, $model)
        {
            parent::__construct($contract, $model);
        }

        public function retrieveByCredentials(array $credentials)
        {
            $user = parent::retrieveByCredentials($credentials);
            if (!$user) {
                //try and find them by email
                $soapService = SoapService::getInstance(new SoapClient([]));

                if ($soapService->auth()) {
                    $authData = $soapService->getAuthData();
                    $user = new User([
                        'email'    => $credentials['email'],
                        'password' => $credentials['password'],
                    ]);

                    $user->save();
                }
            }

            return $user;
        }
    }
