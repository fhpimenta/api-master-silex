<?php

namespace ApiMaster\Controller;

use ApiMaster\Model\Users;
use Illuminate\Hashing\BcryptHasher;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    public function get($id = null)
    {
        $users = $this->getDoctrineService()
            ->getRepository('ApiMaster\Model\Users');

        if (is_null($id)){
            $users = $users->findAll();
        } else {
            $id = (int) $id;
            $users = $users->find($id);
        }

        if(!empty($users)) {
            $build = SerializerBuilder::create()->build()->serialize($users, 'json');

            return new Response($build, 200);
        }

        return new JsonResponse('Não há usuario cadastrados', 404);
    }

    public function create(Request $request)
    {
        $data = $request->request->all();

        $user = new Users();

        $name = $data['name'];
        $phone = $data['celphone'];
        $email = $data['email'];
        $website = $data['website'];

        $password = new BcryptHasher();
        $password = $password->make($data['password']);

        $user->setName($name);
        $user->setCelphone($phone);
        $user->setEmail($email);
        $user->setWebsite($website);
        $user->setPassword($password);
        $user->setCreatedAt($this->getDateTimeNow());
        $user->setUpdatedAt($this->getDateTimeNow());;

        try {
            $orm = $this->getDoctrineService();
            $orm->persist($user);
            $orm->flush();

            return new JsonResponse('Usuario salvo com sucesso!', 200);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 404);
        }

    }

    public function update(Request $request)
    {
        $data = $request->request->all();

        if (!isset($data['id']) || is_null($data['id'])){
            return $this->app->json(['msg' => 'Id não encontrado!'], 401);
        }

        $orm = $this->getDoctrineService();

        $user = $orm->getRepository('ApiMaster\Model\Users')
            ->find($data['id']);

        foreach ($data as $key=>$value) {
            $set = "set" . ucfirst($key);

            if ($set == "setPassword") {
                $password = new BcryptHasher();
                $password = $password->make($data['password']);
                $user->setPassword($password);
                continue;
            }

            $user->$set($value);
        }

        $user->setUpdatedAt($this->getDateTimeNow());

        try {
            $orm->merge($user);
            $orm->flush();

            return $this->app->json('Usuário atualizado com sucesso!', 200);
        } catch (\Exception $e) {
            return $this->app->json($e->getMessage(), 404);
        }

    }

    public function delete($id = null)
    {
        if (!isset($id) || is_null($id)){
            return $this->app->json('ID não informado!', 401);
        }

        $orm = $this->getDoctrineService();

        $user = $orm->getRepository('ApiMaster\Model\Users')
            ->find($id);

        $orm->remove($user);
        $orm->flush();

        return json_encode(["msg"=>"beer sucessfull deleted at"]);
    }
}
