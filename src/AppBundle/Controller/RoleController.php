<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoleController extends Controller{

    /**
     * @Route("/roles", name="get_roles")
     * @Method("GET")
     */
    public function getAction(){

        try{
            
            $conn = $this->getDoctrine()->getConnection();
    
            $query = $conn->executeQuery("CALL getRoles()");
            $data = $query->fetchAll();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()),500);
        };

        return new JsonResponse($data);

    }
    
    /**
     * @Route("/roles", name="create_role")
     * @Method("POST")
     */
    public function createAction(Request $request){

        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        try{
            
            $conn = $this->getDoctrine()->getConnection();
    
            $query = $conn->prepare(
                "CALL createRole(:description)"
            );
    
            $query->bindValue("description", $data['description']);
            $query->execute();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()),500);
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

        try{
            
            $conn = $this->getDoctrine()->getConnection();
    
            $query = $conn->prepare(
                "CALL updateRole(:description,:id)"
            );
    
            $query->bindValue("description", $data['description']);
            $query->bindValue("id", $data['id']);
            $query->execute();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()),500);
        };    

        return new JsonResponse(array("message"=>"Role actualizado correctamente."));

    }
    
    /**
     * @Route("/roles", name="delete_role")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request){

        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        
        try{
            
            $conn = $this->getDoctrine()->getConnection();
    
            $query = $conn->prepare(
                "CALL deleteRole(:id)"
            );
    
            $query->bindValue("id", $data['id']);
            $query->execute();
        }catch(\Exception $e){
            return new JsonResponse(array("message"=>$e->getMessage()),500);
        };    

        return new JsonResponse(array("message"=>"Role eliminado."));

    }

} 