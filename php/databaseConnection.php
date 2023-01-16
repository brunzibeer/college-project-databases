<?php
    function connectToDatabase(){
        $servername = "localhost";
        $username = "root";
        $password = "rootroot";
        $dbName = "mtg_collection_manager";

        global $conn;

        try {
            $conn = new PDO('mysql:host = $servername;dbname = $dbName', $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
        return $conn;
    }
?>

