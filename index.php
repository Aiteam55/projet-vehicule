<?php
require 'flight/Flight.php';
require_once('Fonction.php');
require_once('User.php');

Flight::route('/', function() {
    $f = new Fonction();
    $res = $f->getAllVehicles();
    Flight::json($res);
});

Flight::route('/test', function() {
    $f = new Fonction();
    $user = new User("1","mitia","1234");
    $a =  $f->insertToken($user);
    echo $a;
});

Flight::route('GET /vehicule/@id', function($id){
    $f = new Fonction();
    $res = $f->getVehicule($id);
    Flight::json($res);
});

Flight::route('POST /insert_vehicule/', function(){
    $f = new Fonction();
    $f->insertVehicule();
});

Flight::route('POST /login/', function(){
    $f = new Fonction();
    $tok = $f->login();
    echo $tok["token"];
});


Flight::route('DELETE /delete/@id', function($id){
    $f = new Fonction();
    $res = $f->delete($id);
    echo $res;
});

Flight::start();
