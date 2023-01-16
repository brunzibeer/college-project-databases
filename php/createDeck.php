<?php 
    require 'databaseConnection.php';

    function createDeck($deckName, $deckAuthor){

        global $conn;

        $create = $conn->prepare("USE mtg_collection_manager;");
        $create->setFetchMode(PDO::FETCH_ASSOC);
        $create->execute();

        $create = $conn->prepare("INSERT INTO deck(deck_name, deck_author)
                                VALUES(?, ?);");
        $create->bindValue(1, $deckName, PDO::PARAM_STR);
        $create->bindValue(2, $deckAuthor, PDO::PARAM_STR);

        $create->execute();

        echo
                "<script type='text/javascript'>
                    alert('New deck created, redirecting...');
                    location='/php/myDecks.php';
                </script>";
    }

    try{
        $conn = connectToDatabase();

        createDeck($_GET['deck_name'], $_GET['deck_author']);
    }
    catch(PDOException $e){
        echo "MySQL Error: " . $e->getMessage();
    }

?>