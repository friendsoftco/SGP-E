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

class VoterController extends Controller{

    /**
     * @Route("/voters", name="get_voters")
     * @Method("GET")
     */
    public function getAction(){

        try {

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->executeQuery("CALL getVoters()");
            $data = $query->fetchAll();

        }catch(\Exception $e){
            return new JsonResponse(array("message" => $e->getMessage()));
        }

        return new JsonResponse($data);

    }
    
    /**
     * @Route("/voters", name="create_voters")
     * @Method("POST")
     */
    public function createAction(Request $request){
        
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        try {

            $conn = $this->getDoctrine()->getConnection();

            $query = $conn->prepare("CALL getVoters()");
            
            $query->bindValue("name", $data['name']);
            $query->execute();

        }catch(\Exception $e){
            return new JsonResponse(array("message" => $e->getMessage()));
        }

        return new JsonResponse($data);

    }

} 