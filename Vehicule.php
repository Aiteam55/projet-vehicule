<?php 
    class Vehicule {
        private $idVehicule;
        private $modele;
        private $numero;

        public function getIdVehicule(){
            return $this->idVehicule;
        }
        public function getModele(){
            return $this->modele;
        }
        public function getNumero(){
            return $this->numero;
        }

        public function __construct($id,$modele,$num) {
            $this->idVehicule=$id;
            $this->modele=$modele;
            $this->numero=$num;
        }
        public static function hello() {
            echo 'hello world!';
        }

    }
?>