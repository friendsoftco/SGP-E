<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JWT;

class UserController extends Controller{

    /**
     * @Route("/users", name="create_user")
     * @Method("POST")
     */

    public function createAction(Request $request){

        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);

        try {

            $key = "SEBAJAMES";
            $token = $request->headers->get("token-aut");
            JWT::decode($token, $key, array("HS256"));

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->prepare(
                "CALL createUser(
                   :name, :lastname, :email,:password,
                   :create_date,:id_originator,:group_id,
                   :phone,:cellpone,:address,:roles_id,
                   :state
                   )"
            );

            $password = password_hash($data['password'],PASSWORD_BCRYPT,array("cost" => 12));

            $query->bindValue("name", $data['name']);
            $query->bindValue("lastname", $data['lastname']);
            $query->bindValue("email", $data['email']);
            $query->bindValue("password", $password);
            $query->bindValue("create_date", $data['create_date']);
            $query->bindValue("id_originator", $data['id_originator']);
            $query->bindValue("group_id", $data['group_id']);
            $query->bindValue("phone", $data['phone']);
            $query->bindValue("cellpone", $data['cellphone']);
            $query->bindValue("address", $data['address']);
            $query->bindValue("roles_id", $data['roles_id']);
            $query->bindValue("state", $data['state']);

            $query->execute();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()));
        };

        return new JsonResponse(array("message"=>"Usuario creado correctamente."));

    }

    /**
     * @Route("/users/{id}", name="get_user_id")
     * @Method("GET")
     */
    public function getByIdAction($id, Request $request){

        try {

            $key = "SEBAJAMES";
            $token = $request->headers->get("token-aut");
            JWT::decode($token, $key, array("HS256"));

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->executeQuery("CALL getUser(?)", array($id));
            $data = $query->fetch();

        }catch(\Exception $e){
            return new JsonResponse(array("message" => $e->getMessage()));
        }

        return new JsonResponse($data);

    }

    /**
     * @Route("/users/group/{groupId}", name="get_user_groupId")
     * @Method("GET")
     */
    public function getAction($groupId, Request $request){

        try {

            $key = "SEBAJAMES";
            $token = $request->headers->get("token-aut");
            JWT::decode($token, $key, array("HS256"));

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->executeQuery("CALL getUsers(?)", array($groupId));
            $data = $query->fetchAll();

        }catch(\Exception $e){
            return new JsonResponse(array("message" => $e->getMessage()));
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/users", name="update_user")
     * @Method("PUT")
     */
    public function updateAction(Request $request){

        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);

        try {

            $key = "SEBAJAMES";
            $token = $request->headers->get("token-aut");
            JWT::decode($token, $key, array("HS256"));

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->prepare(
                "CALL editUser(
                   :name, :lastname, :email,:password,
                   :phone,:cellpone,:address,:roles_id,
                   :state,:id
                   )"
            );
            $query->bindValue("id", $data['id']);
            $query->bindValue("name", $data['name']);
            $query->bindValue("lastname", $data['lastname']);
            $query->bindValue("email", $data['email']);
            $query->bindValue("password", $data['password']);
            $query->bindValue("phone", $data['phone']);
            $query->bindValue("cellpone", $data['cellphone']);
            $query->bindValue("address", $data['address']);
            $query->bindValue("roles_id", $data['roles_id']);
            $query->bindValue("state", $data['state']);

            $query->execute();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()));
        };

        return new JsonResponse(array("message"=>"Usuario actualizado correctamente."));
    }

    /**
     * @Route("/users/{id}", name="delete_user")
     * @Method("DELETE")
     */
    public function deleteAction($id){
        //
    }

    /**
     * @Route("/login", name="login_user")
     * @Method("POST")
     */
    public function loginAction(Request $request){

        $contentData = $request->getContent();
        $jsonData = json_decode($contentData, true);

        try {

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->executeQuery("CALL loginUser(?)", array($jsonData['user']));
            $data = $query->fetchAll();

            if(!$data){
                throw new \Exception("Usuario no valido!!");
            }

            if(!password_verify($jsonData['password'],$data[0]["password"])){
                throw new \Exception("Password no valido!!");
            }
            unset($data[0]["password"]);

            $key = "SEBAJAMES";
            $jwt = JWT::encode($data[0],$key);

        }catch(\Exception $e){
            return new JsonResponse(array("messaje" => $e->getMessage()));
        }

        return new JsonResponse(array("token-aut" => $jwt));
    }

} 