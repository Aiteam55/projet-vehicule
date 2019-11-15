<?php
    function connect() {	
            $user='root';
            $pass='root';
            $dsn='mysql:host=localhost;dbname=flottevoiture';
            static $dbh=null;
            try {
                    $dbh = new PDO($dsn, $user, $pass);
                    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
             catch (PDOException $e) {
                    print "Erreur ! : " . $e->getMessage();
                    die();
            }
            return $dbh;
    }
?>