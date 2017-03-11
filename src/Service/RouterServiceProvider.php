<?php
namespace ApiMaster\Service;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class RouterServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $middleware = function(Request $request, Application $app){
            $token =  $request->headers->get('Authorization');
            $token = str_replace('Bearer ', '', $token);

            try {
                $app['jwt']->validateToken($token);
            } catch (\Exception $e) {
                return $app->json(['msg'=> 'Token Inválido!'], 401);
            }
        };

        $app->get($app['api_version'] .'/beers', 'beers:get')->before($middleware);

        $app->get($app['api_version'] .'/beers/{id}', 'beers:get');

        $app->post($app['api_version'] . '/beers', 'beers:create');

        $app->put($app['api_version'] . '/beers', 'beers:update');

        $app->delete($app['api_version'] . '/beers/{id}', 'beers:delete');


        $app->get($app['api_version'] . '/users', 'users:get');

        $app->get($app['api_version'] . '/users/{id}', 'users:get');

        $app->post($app['api_version'] . '/users', 'users:create');

        $app->put($app['api_version'] . '/users', 'users:update');

        $app->delete($app['api_version'] . '/users/{id}', 'users:delete');

        /*
         * Auth Routes
         */
        $app->post($app['api_version'] . '/auth/login', 'auth:login');
    }


}