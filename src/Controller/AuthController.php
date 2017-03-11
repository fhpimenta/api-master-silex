<?php

namespace ApiMaster\Controller;

use Illuminate\Hashing\BcryptHasher;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $data = $request->request->all();

        $user = $this->getDoctrineService()
                    ->getRepository('ApiMaster\Model\Users')
                    ->findByEmail($data['email'])[0];

        if (!$user || $data['email'] != $user->getEmail()) {
            return $this->app->json(['msg' => 'Usuário ou senha incorretos'], 401);
        }

        $hash = new BcryptHasher();

        if(!$hash->check($data['password'], $user->getPassword())) {
            return $this->app->json(['msg' => 'Senha incorreta'], 401);
        }

        $jwt = $this->app['jwt'];

        $jwt->setApplication($this->app);

        $jwt->setPayloadData([
            'username' => $user->getEmail()
        ]);

        return $this->app->json(['token' => $jwt->generateToken()->__toString()]);

    }
}