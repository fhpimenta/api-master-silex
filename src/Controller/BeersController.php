<?php

namespace ApiMaster\Controller;

use ApiMaster\Model\Beers;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BeersController extends BaseController
{
    public function index()
    {
        $beers = $this->getDoctrineService()
                        ->getRepository('ApiMaster\Model\Beers')
                        ->findAll();

        $serializer = SerializerBuilder::create()->build();
        $beers = $serializer->serialize($beers, 'json');

        return new Response($beers, 200);

    }

    public function get($id = null)
    {
        $beers = $this->getDoctrineService()
            ->getRepository('ApiMaster\Model\Beers');

        if (is_null($id)) {
            $beers = $beers->findAll();
        } else {
            $id = (int) $id;
            $beers = $beers->find($id);
        }

        if(!empty($beers)) {
            $build = SerializerBuilder::create()->build()->serialize($beers, 'json');

            return $build;
        }

        return new JsonResponse('Sem cervejas cadastradas', 404);
    }

    public function create(Request $request)
    {
        $data = $request->request->all();

        $beer = new Beers();

        try{

            $beer->setName($data['name']);
            $beer->setPrice($data['price']);
            $beer->setType($data['type']);
            $beer->setMark($data['mark']);
            $beer->setCreatedAt($this->getDateTimeNow());
            $beer->setUpdatedAt($this->getDateTimeNow());

            $orm = $this->getDoctrineService();
            $orm->persist($beer);
            $orm->flush();
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }

        return new JsonResponse('Cerveja criada com sucesso', 200);
    }

    public function update(Request $request)
    {
        $data = $request->request->all();

        if (!isset($data['id']) || is_null($data['id'])) {
            return json_encode(["msg" => "ID nao informado"]);
        }

        $orm = $this->getDoctrineService();

        $beer = $orm->getRepository('ApiMaster\Model\Beers')
            ->find($data['id']);

        foreach ($data as $key => $value) {
            $set = "set" . ucfirst($key);
            $beer->$set($value);
        }

        $beer->setUpdatedAt($this->getDateTimeNow());

        $orm->merge($beer);
        $orm->flush();

        return json_encode(["msg"=>"beer sucessfull updatad at"]);
    }

    public function delete($id = null)
    {
        if (!isset($id) || is_null($id)) {
            return json_encode(["msg" => "ID nao informado"]);
        }

        $orm = $this->getDoctrineService();

        $beer = $orm->getRepository('ApiMaster\Model\Beers')
            ->find($id);

        $orm->remove($beer);
        $orm->flush();

        return json_encode(["msg"=>"beer sucessfull deleted at"]);
    }
}