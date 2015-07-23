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

class RoleController extends Controller{

    /**
     * @Route("/roles", name="get_roles")
     * @Method("GET")
     */
    public function getAction(Request $request){

        try {

            $key = "SEBAJAMES";
            $token = $request->headers->get("tokenAuth");
            JWT::decode($token, $key, array("HS256"));

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->executeQuery("CALL getRoles()");
            $data = $query->fetchAll();

        }catch(\Exception $e){
            return new JsonResponse(array("message" => $e->getMessage()));
        }

        return new JsonResponse($data);

    }
    
    /**
     * @Route("/roles", name="create_role")
     * @Method("POST")
     */
    public function createAction(Request $request){

        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);

        try {

            $key = "SEBAJAMES";
            $token = $request->headers->get("tokenAuth");
            JWT::decode($token, $key, array("HS256"));

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->prepare(
                "CALL createRole(:name)"
            );

            $query->bindValue("name", $data['name']);
            
            $query->execute();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()));
        };

        return new JsonResponse(array("message"=>"Role agregado correctamente."));

    }
    
    /**
     * @Route("/roles", name="update_role")
     * @Method("PUT")
     */
    public function updatepAction(Request $request){

        $jsonData = $request->getContent();

        $data = json_decode($jsonData, true);

        try {

            $key = "SEBAJAMES";
            $token = $request->headers->get("tokenAuth");
            JWT::decode($token, $key, array("HS256"));

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->prepare(
                "CALL editRole(:name,:id)"
            );

            $query->bindValue("name", $data['name']);
            $query->bindValue("id", $data['id']);
            
            $query->execute();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()));
        };

        return new JsonResponse(array("message"=>"Role actualizado correctamente."));

    }

} 