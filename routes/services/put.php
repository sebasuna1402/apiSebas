<?php
require_once "models/connection.php";
require_once "controllers/put.controller.php";

if(isset($_GET["id"]) && isset($_GET["nameId"])){
 
	/*=============================================
	Capturamos los datos del formulario
	=============================================*/
	
	$data = array();
   

	parse_str(file_get_contents('php://input'), $data);

    $columns = array();
    

    foreach (array_keys($data) as $key => $value) {


        array_push($columns, $value);

        # code...
    }

    

    array_push($columns, $_GET["nameId"]);

    $columns = array_unique($columns);


    if(empty(Connection::getColumnsData($table, $columns))){

        $json = array(
            'status' => 400,
            'results' => "Error: Fields in the form do not match the database"
       );

       echo json_encode($json, http_response_code($json["status"]));

       return;


    }
          
                /*=============================================
                Peticion POST usuario autorizado
                =============================================*/	
                if (isset($_GET["token"])) {

                         /*=============================================
                Peticion POST usuario no autorizado
                =============================================*/	


                    if ($_GET["token"] == "no" && isset($_GET["excep"])) {

                        $columns = array($_GET["excep"]);

                        if(empty(Connection::getColumnsData($table, $columns))){

                            $json = array(
                                'status' => 400,
                                'results' => "Error: Fields in the form do not match the database"
                           );
                    
                           echo json_encode($json, http_response_code($json["status"]));
                    
                           return;
                    
                    
                        }
                                /*=============================================
                Peticion POST usuario autorizado
                =============================================*/	
                $response = new PutController();

                $response -> putData($table,$data,$_GET["id"],$_GET["nameId"]);


                    }else{

                    
                    $tableToken = $_GET["table"] ?? "users" ;
                    $suffix = $_GET["suffix"] ?? "user" ;

                    $validate = Connection::tokenValidate($_GET["token"], $tableToken,  $suffix );


                    

                    if ($validate == "ok") {

    			/*=============================================
				Solicitamos respuesta del controlador para editar datos en cualquier tabla
				=============================================*/		

				$response = new PutController();

                $response -> putData($table,$data,$_GET["id"],$_GET["nameId"]);
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



            }else{
                      /*=============================================
               errror cuando no envia el token
                =============================================*/	


                    
                $json = array(
                    'status' => 400,
                    'results' => "Error: Authorization required"
               );
        
               echo json_encode($json, http_response_code($json["status"]));
        
               return;



            }

   
}