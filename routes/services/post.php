<?php
require_once "models/connection.php";
require_once "controllers/post.controller.php";
if(isset($_POST)){

$columns = array();



foreach (array_keys($_POST) as $key => $value) {


    array_push($columns,$value);
   
    # code...
}


    if(empty(Connection::getColumnsData($table, $columns))){

        $json = array(
            'status' => 400,
            'results' => "Error: Fields in the form do not match the database"
       );

       echo json_encode($json, http_response_code($json["status"]));

       return;


    }
    $response = new PostController();

    			/*=============================================
				registrar un usuario
				=============================================*/		

    
                if(isset($_GET["register"]) && $_GET["register"] == true){

                    $suffix = $_GET["suffix"] ?? "user";
            
                    $response -> postRegister($table,$_POST,$suffix);
            
                /*=============================================
                Peticion POST para login de usuario
                =============================================*/	
            
                } else if(isset($_GET["login"]) && $_GET["login"] == true){

                    $suffix = $_GET["suffix"] ?? "user";
            
                    $response -> postLogin($table,$_POST,$suffix);
            
                }else{
                    
                /*=============================================
                Peticion POST usuario autorizado
                =============================================*/	
                if (isset($_GET["token"])) {

                    

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
                        
                        $response -> postData($table,$_POST);


                    }else{







                    $tableToken = $_GET["table"] ?? "users" ;
                    $suffix = $_GET["suffix"] ?? "user" ;

                    $validate = Connection::tokenValidate($_GET["token"], $tableToken,  $suffix );




                    if ($validate == "ok") {


                        $response -> postData($table,$_POST);
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


                    
                    $json = array(
                        'status' => 400,
                        'results' => "Error: Authorization required"
                   );
            
                   echo json_encode($json, http_response_code($json["status"]));
            
                   return;



                }

             }
           }