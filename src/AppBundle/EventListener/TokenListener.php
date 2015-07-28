<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use JWT;

class TokenListener {
    
    public function onKernelRequest(GetResponseEvent $event){
        
        $request = $event->getRequest();
        
        if($request->getPathInfo() !== "/login"){
        
            $key = "SEBAJAMES";
            $token = $request->headers->get("X-Custom-Auth");
            
            $response = new JsonResponse();
            try{
                JWT::decode($token, $key, array("HS256"));    
            }catch(\UnexpectedValueException $e){
                $response->setData(array("message" => $e->getMessage()));
                $response->setStatusCode(500);
                $event->setResponse($response);
            }
        }
        
    }
    
}