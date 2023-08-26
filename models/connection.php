<?php

require_once "models/get.model.php";


class Connection
{

    /*Informacion de la base de datos*/

    static public function infoDatabase()
    {
        /*CUANDO VAYAMOS A SUBIR ESO A UN HOSTING SOLO CAMBIAMOS ESOS 3 VALORES CON LOS NUEVOS DEL HOSTING*/
        $infoDB = array(

            "database" => "database-1",
            "user" => "root",
            "pass" => ""


        );

        return $infoDB;

    }

     /*API KEY*/

     static public function apikey()
     {
 
 
         return "BznWRk2rm8n8tbDeSkpn7zxKbQmfVj";
 
 
 
     }
 
 
       /*aca damos acceso publico a las tablas que queramos que el publico acceda*/
 
     static public function publicAccess(){
 
         $tables = ["courses"];
 
         return $tables;
 
     }

    /*conexion a la  base de datos*/

    static public function connect()
    {

        try {

            $link = new PDO(

                "mysql:host=localhost;dbname=" . Connection::infoDatabase()["database"],
                Connection::infoDatabase()["user"],
                Connection::infoDatabase()["pass"],
            );

            $link->exec("set names utf8");

        } catch (PDOException $e) {

            die("Error: " .$e->getMessage());

        }

        return $link;

    }
/*=============================================
	Validar existencia de una tabla en la bd
	=============================================*/

	static public function getColumnsData($table, $columns){

		/*=============================================
		Traer el nombre de la base de datos
		=============================================*/

		$database = Connection::infoDatabase()["database"];

		/*=============================================
		Traer todas las columnas de una tabla
		=============================================*/

		$validate = Connection::connect()
		->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
		->fetchAll(PDO::FETCH_OBJ);

		/*=============================================
		Validamos existencia de la tabla
		=============================================*/

		if(empty($validate)){

			return null;

		}else{
            
            if($columns[0] == "*"){
				
				array_shift($columns);

			}
			/*=============================================
			Ajuste de selección de columnas globales
			=============================================*/
            $sum = 0;
     
			foreach ($validate as $key => $value) {
               

          
                $sum += in_array($value->item, $columns);	
               
               
              
               


        
            
				
						
			}
         
			
  return $sum == count($columns) ? $validate : null;
    
		}

	}
      /*Generar Token*/

      static public function jwt( $id, $email )
      {


        $time = time();

		$token = array(

			"iat" =>  $time,//Tiempo en que inicia el token
			"exp" => $time + (60*60*24), // Tiempo en que expirará el token (1 día)
			"data" => [

				"id" => $id,
				"email" => $email
			]

		);


    
             return $token;
       

      }
       /*Validar el token*/
       static public function tokenValidate($token,$table,$suffix){

         /*Traemos el usuario de acuerdo al token*/
         $user = GetModel::getDataFilter($table, "token_exp_".$suffix, "token_".$suffix, $token, null, null, null, null);
       
        

         if (!empty($user)) {
                 /*valdidar que el token no haya expirado*/

                 $time = time();
                 if ($time < $user[0]->{"token_exp_".$suffix} ) {


                    return  "ok";


                 }else{

                    return "expired";

                 }


                 
         }else{


            return "no auth";




         }

   






       }





}