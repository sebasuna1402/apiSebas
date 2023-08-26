<?php

require_once "models/connection.php";
require_once "controllers/get.controller.php";

$routesArray = explode("/",$_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);


/*
cuando no se hace nignuna peticion a la api va a suceder esto

*/
if(count($routesArray) == 0 ){


    $json = array(

        'status' => 404,
        'results' => 'Not Found'
        
        );
        
        echo json_encode($json, http_response_code($json["status"]));
        
        return;


}

/*
cuando  se hacen peticiones a la api va a suceder esto

*/
/*
con esa linea echo es donde verificamos el tipo de metodo get put post delete

*/


if(count($routesArray) ==  1 &&   isset($_SERVER['REQUEST_METHOD'])){
   
    $table = explode("?",$routesArray[1])[0];

/*
Validar APIKEY

*/
    if (!isset(getallheaders()["Authorization"]) || getallheaders()["Authorization"] != Connection::apikey()) {
  
        if(in_array($table, Connection::publicAccess()) == 0){
         
            
        $json = array(

            'status' => 400,
            "results" => "You are not authorized to make this request"
        );

        echo json_encode($json, http_response_code($json["status"]));

        return;

    }else{

        /*=============================================
        Acceso público
        =============================================*/
        $response = new GetController();
        $response -> getData($table, "*",null,null,null,null);

        return;
    }


    }



/*
PETICION GET

*/
 if($_SERVER['REQUEST_METHOD'] == "GET"){

  include "services/get.php";
        
 }


 /*
PETICION POST

*/
if($_SERVER['REQUEST_METHOD'] == "POST"){

    include "services/post.php";
        
 }
 /*
PETICION PUT

*/
if($_SERVER['REQUEST_METHOD'] == "PUT"){

    include "services/put.php";

}

  /*
PETICION DELETE

*/
if($_SERVER['REQUEST_METHOD'] == "DELETE"){
    
    include "services/delete.php";

 }





}




