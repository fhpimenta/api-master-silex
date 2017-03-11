<?php
namespace ApiMaster\Service;

use ApiMaster\Controller\AuthController;
use ApiMaster\Controller\BeersController;
use ApiMaster\Controller\UserController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ControllerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        /**
         * Beers Controller
         * @param Container $app
         * @return BeersController
         */
        $app['beers'] = function (Container $app) {
            return new BeersController($app);
        };

        $app['users'] = function (Container $app) {
            return new UserController($app);
        };

        $app['auth'] = function (Container $app) {
            return new AuthController($app);
        };
    }
}