
<?php
require_once "models/connection.php";
require_once "controllers/delete.controller.php";

if(isset($_GET["id"]) && isset($_GET["nameId"])){
 
	/*=============================================
	Capturamos los datos del formulario
	=============================================*/

    $columns = array($_GET["nameId"]);


    if(empty(Connection::getColumnsData($table, $columns))){

        $json = array(
            'status' => 400,
            'results' => "Error: Fields in the form do not match the database"
       );

       echo json_encode($json, http_response_code($json["status"]));

       return;


    }

    
                /*=============================================
                Peticion delete usuario autorizado
                =============================================*/	
                if (isset($_GET["token"])) {
    
                    $tableToken = $_GET["table"] ?? "users" ;
                    $suffix = $_GET["suffix"] ?? "user" ;

                    $validate = Connection::tokenValidate($_GET["token"], $tableToken,  $suffix );


                    

                    if ($validate == "ok") {
    			/*=============================================
				Solicitamos respuesta del controlador para eliminar datos en cualquier tabla
				=============================================*/		

				$response = new DeleteController();

                $response -> deleteData($table,$_GET["id"],$_GET["nameId"]);
                    }
                    

                    if ($validate == "expired") {



                        $json = array(
                            'status' => 303,
                            'results' => "Error: The token has expired"
                       );
                
                       echo json_encode($json, http_response_code($json["status"]));
                
                       return;


                    }

                    if ($validate == "no auth") {



                        $json = array(
                            'status' => 400,
                            'results' => "Error: The user is not authorized"
                       );
                
                       echo json_encode($json, http_response_code($json["status"]));
                
                       return;


                    }

                }
            else{


                    
                $json = array(
                    'status' => 400,
                    'results' => "Error: Authorization required"
               );
        
               echo json_encode($json, http_response_code($json["status"]));
        
               return;



            }

   
}