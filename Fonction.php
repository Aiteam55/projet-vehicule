<?php
    require 'Vehicule.php';
    Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=flottevoiture','root','root'),function($db){
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    );
    class Fonction{
        public function getAllVehicles(){
            $db = Flight::db();
            $sql = "select * from vehicule";
            $result = $db->query($sql);
            $count = $result->rowCount();
            if($count > 0){
                $vehicles_arr = array();
                $vehicles_arr["status"] = "success";
                $vehicles_arr["data"] = array();
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $vehicle_item = array(
                        "idVehicule" => $row["idVehicule"],
                        "modele" => $row['modele'],
                        "numero" => $row['numero']
                    );
                    array_push($vehicles_arr["data"], $vehicle_item);
                }
                return $vehicles_arr;
            }
            else{
                echo json_encode(
                    array("message" => "No vehicles found.")
                );
            }
        }

        public function getVehicule($id){
            try {
                $db = Flight::db();
                $sql = "select * from vehicule where idVehicule = ".$id;
                $result = $db->query($sql);
                $count = $result->rowCount();
                if($count > 0){
                    $vehicles_arr = array();
                    $vehicles_arr["status"] = "success";
                    $vehicles_arr["data"] = array();
                    while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        $vehicle_item = array(
                            "idVehicule" => $row["idVehicule"],
                            "modele" => $row['modele'],
                            "numero" => $row['numero']
                        );
                        array_push($vehicles_arr["data"], $vehicle_item);
                    }
                    return $vehicles_arr;
                }
                else{
                    echo json_encode(
                        array("message" => "No vehicles found.")
                    );
                }
            } catch (ErrorException $e) {
                echo $e->getMessage();
            }
        }

        function delete($id)
        {
            Flight::db()->beginTransaction();
            $sql = "DELETE FROM vehicule WHERE idVehicule = '{$id}'";
            $result = Flight::db()->query($sql);
            if ($result == false) {
                Flight::db()->rollback();
                return false;
            }
            Flight::db()->commit();
            return true;
        }

        function insertVehicule()
        {
            $obj = json_decode(file_get_contents("php://input"));

            if(isset($obj)){
                if(!empty($obj->idVehicule)&&!empty($obj->modele)&&!empty($obj->numero)){
                    
                    $db = Flight::db();
    
                    $stmt = $db->prepare("INSERT INTO vehicule(idVehicule,modele,numero) VALUES ('".$obj->idVehicule."','".$obj->modele."','".$obj->numero."')");
                    $stmt->execute();
                }
                else{
                    echo'{
                        "status":"fail",
                        "statuscode":101,
                        "message":"Please try again"
                    }';
                }
            }
            else{
                echo'{
                    "status":"fail",
                    "statuscode":102,
                    "message":"Invalid Field"
                }';
            }             
        }

        function expiration($time){
            $datum= new DateTime();
            $heure=time($datum);
            $times=$heure+$time;
            $dates=date('d/m/Y', $times).' '.date('H:i:s', $times);
            return $dates;
        }

        function insertToken($user)
        {
            $f = new Fonction();
            $login = $user->getLogin();
            $id = $user->getId();
            $pwd = $user->getPassword();
            $db = Flight::db();
            $token = $f->generateToken($login);
            $date = $f->expiration(60);
            $sql = "INSERT INTO token(id,token,dateExpiration) VALUES ('".$id."','".$token."',"."(SELECT CURRENT_TIMESTAMP))";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            //return $log = array('id' => $id,'token'=> $token);
        }

        function getToken($idUser){
            $db = Flight::db();
            $sql = "select token , dateExpiration from token where id = ".$idUser;
            $stmt = $db->query($sql);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $result = array(
                    'token' => $row["token"],
                    'expiration' => $row["dateExpiration"]
                );
            }
            return $result;
        }

        function login(){
            $obj = json_decode(file_get_contents("php://input"));
            $f = new Fonction();
            if(isset($obj)){
                if(!empty($obj->login)&&!empty($obj->password)){
                    
                    $db = Flight::db();
                    $sql = "SELECT * FROM user where login ='".$obj->login."' and password = SHA1('".$obj->password."')";
                    $result = $db->query($sql);
                    $count = $result->rowCount();
                    if($count > 0){
                        while($row = $result->fetch(PDO::FETCH_ASSOC)){
                            $user = new User($row["id"],$row['login'],$row['password']);
                        }
                        $f->insertToken($user);
                        return $f->getToken($user->getId());
                    }
                }
                else{
                    echo'{
                        "status":"fail",
                        "statuscode":101,
                        "message":"Please try again"
                    }';
                }
            }
            else{
                echo'{
                    "status":"fail",
                    "statuscode":102,
                    "message":"Invalid Field"
                }';
            }     
        }

        function verifyConnection($id){
            $f = new Fonction();
            $token = $f->getToken($id);
            $dateNow = new DateTime();
            $expiration = $token["expiration"];
            $temps = time($date);
            $date = $dateNow->format('Y-m-d H:i:s');
            $tempsExpire = time($expiration);
            $dateExp = date('d/m/Y', $tempsExpire).' '.date('H:i:s', $tempsExpire);
            $result = null;
            if(strtotime($exp)<=strtotime($dates))
            {
                $result=null;
            }
            else
            {
                $result=$f->getBearerToken();
            }
            return $result;
        }

        // function getKilometrage($idVehicle){
        //     $db = Flight::db();
        //     $sql = "select * from kilometrage where idVehicle = '{$idVehicle}' ";
        //     $result = $db->query($sql);
        //     $count = $result->rowCount();
        //     if($count > 0){
        //         $kilometrage_arr = array();
        //         $kilometrage_arr["status"] = array(
        //             "code" => "200",
        //             "message" => "Success"
        //         );
        //         $kilometrage_arr["data"] = array();
        //         while($row = $result->fetch(PDO::FETCH_ASSOC)){
        //             $vehicle_item = array(
        //                 "idKilometrage" => $row["idKilometrage"],
        //                 "date" => $row['dateK'],
        //                 "DebutKm" => $row['debutKm'],
        //                 "FinKm" => $row['finKm'],
        //                 "idVehicle" => $row['idVehicle']
        //             );
        //             array_push($kilometrage_arr["data"], $kilometrage_item);
        //         }
        //         return $kilometrage_arr;
        //     }
        //     else{
        //         echo json_encode(
        //             array("message" => "No kilometrage found.")
        //         );
        //     } 

        function update($id, $nom, $num)
        {
            Flight::db()->beginTransaction();
            $sql = "UPDATE  vehicule Set value modele = '{$nom}' and numero = '{$num}' WHERE idVehicule = '{$id}'";
            $result = Flight::db()->query($sql);
            if ($result == false) {
                Flight::db()->rollback();
                return false;
            }
            Flight::db()->commit();
            return true;
        }
        
        function generateToken($log){
            $token = md5($log."mitia".mt_rand());
            return $token;
        }

        function getAuthorizationHeader(){
            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                //print_r($requestHeaders);
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }
        /**
        * get access token from header
        * */
        function getBearerToken() {
            $headers = getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            return null;
        }
    }
?>