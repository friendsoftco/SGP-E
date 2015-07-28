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

            $query = $conn->prepare("CALL createVoter(
                                            :document,
                                            :name1,
                                            :name2,
                                            :last_name1,
                                            :last_name2,
                                            :birth_date,
                                            :email,
                                            :exp_date,
                                            :exp_place,
                                            :phone,
                                            :cellphone,
                                            :address,
                                            :longitude,
                                            :latitude,
                                            :create_date,
                                            :zone_id,
                                            :users_id,
                                            :voters_state_id,
                                            :vote_state_id,
                                            :group_id
            )");
            
            $query->bindValue("document", $data['document']);
            $query->bindValue("name1", $data['name1']);
            $query->bindValue("name2", (empty($data['name2'])) ? NULL : $data['name2']);
            $query->bindValue("last_name1", $data['last_name2']);
            $query->bindValue("last_name2", $data['last_name2']);
            $query->bindValue("birth_date", $data['birth_date']);
            $query->bindValue("email", (empty($data['email'])) ? NULL : $data['email']);
            $query->bindValue("exp_date", (empty($data['exp_date'])) ? NULL : $data['exp_date']);
            $query->bindValue("exp_place", (empty($data['exp_place'])) ? NULL : $data['exp_place']);
            $query->bindValue("phone", (empty($data['phone'])) ? NULL : $data['phone']);
            $query->bindValue("cellphone", (empty($data['cellphone'])) ? NULL : $data['cellphone']);
            $query->bindValue("address", (empty($data['address'])) ? NULL : $data['address']);
            $query->bindValue("longitude", (empty($data['longitude'])) ? NULL : $data['longitude']);
            $query->bindValue("latitude", (empty($data['latitude'])) ? NULL : $data['latitude']);
            $query->bindValue("create_date", $data['create_date']);
            $query->bindValue("zone_id", $data['zone_id']);
            $query->bindValue("users_id", $data['users_id']);
            $query->bindValue("voters_state_id", $data['voters_state_id']);
            $query->bindValue("vote_state_id", $data['vote_state_id']);
            $query->bindValue("group_id", $data['group_id']);
            $query->execute();

        }catch(\Exception $e){
            return new JsonResponse(array("message" => $e->getMessage()));
        }

        return new JsonResponse(array("message"=>"Votante creado correctamente."));

    }

} 