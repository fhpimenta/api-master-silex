<?php

namespace ApiMaster\Controller;

use JMS\Serializer\SerializerBuilder;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Response;

class BeersController
{
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function index()
    {
        $beers = $this->app['orm.em']
                        ->getRepository('ApiMaster\Model\Beer')
                        ->findAll();

        $serializer = SerializerBuilder::create()->build();
        $beers = $serializer->serialize($beers, 'json');

        return new Response($beers, 200);

    }
}