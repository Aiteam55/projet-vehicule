<?php 
    class User {
        private $id;
        private $login;
        private $password;

        public function getId(){
            return $this->id;
        }
        public function getLogin(){
            return $this->login;
        }
        public function getPassword(){
            return $this->password;
        }

        public function __construct($id,$login,$password) {
            $this->id=$id;
            $this->login=$login;
            $this->password=$password;
        }
    }
?>