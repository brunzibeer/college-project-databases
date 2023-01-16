<?php
    require './databaseConnection.php';

    function removeFromDeck($idToRemove, $copiesToRemove, $deckIdToRemove){

        global $conn;

        $remove = $conn->prepare("USE mtg_collection_manager;");
        $remove->setFetchMode(PDO::FETCH_ASSOC);
        $remove->execute();

        $idToRemove = intval($idToRemove);
        $copiesToRemove = intval($copiesToRemove);
        $deckIdToRemove = intval($deckIdToRemove);

        //Need to retrieve how many copies in the collection
        $remove = $conn->prepare("SELECT *
                                FROM CardDeck
                                WHERE card_card_id = ?
                                AND deck_deck_id = ?;");
        $remove->bindValue(1, $idToRemove, PDO::PARAM_INT);
        $remove->bindValue(2, $deckIdToRemove, PDO::PARAM_INT);

        $remove->execute();

        $row = $remove->fetch();

        $copiesAlreadyThere = intval($row['card_copies']);

        //If the copies are >= than the copies saved
        if($copiesToRemove >= $copiesAlreadyThere){
            $remove = $conn->prepare("DELETE
                                FROM CardDeck
                                WHERE card_card_id = ?
                                AND deck_deck_id = ?;");
            $remove->bindValue(1, $idToRemove, PDO::PARAM_INT);
            $remove->bindValue(2, $deckIdToRemove, PDO::PARAM_INT);

            $remove->execute();

            echo
                "<script type='text/javascript'>
                    alert('Cards removed from deck, redirecting...');
                    location='/php/myDecks.php';
                </script>";

            return true;
        }
        //If the copies are < than the copies saved
        else{
            $copiesToRemove = $copiesAlreadyThere - $copiesToRemove;

            $remove = $conn->prepare("UPDATE CardDeck
                                    SET card_copies = ?
                                    WHERE card_card_id = ?
                                    AND deck_deck_id = ?;");
            $remove->bindValue(1, $copiesToRemove, PDO::PARAM_INT);
            $remove->bindValue(2, $idToRemove, PDO::PARAM_INT);
            $remove->bindValue(3, $deckIdToRemove, PDO::PARAM_INT);

            $remove->execute();

            echo
                "<script type='text/javascript'>
                    alert('Card copies removed from deck, redirecting...');
                    location='/php/myDecks.php';
                </script>";
            
            return true;
        }


        


    }

    try{
        //DB connection
        $conn = connectToDatabase();

        session_start();

        //Removing cards
        removeFromDeck($_GET['remove'], $_GET['copies'], $_SESSION['deckId']);

    }
    catch(PDOException $e){
        echo "MySQL Error: " . $e->getMessage();
    }
?>