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
     * @Route("/voter", name="get_voters")
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

} 