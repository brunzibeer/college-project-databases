<?php
    require './databaseConnection.php';

    function removeFromCollection($idToRemove, $copiesToRemove){

        global $conn;

        $remove = $conn->prepare("USE mtg_collection_manager;");
        $remove->setFetchMode(PDO::FETCH_ASSOC);
        $remove->execute();

        $idToRemove = intval($idToRemove);
        $copiesToRemove = intval($copiesToRemove);

        //Need to retrieve how many copies in the collection
        $remove = $conn->prepare("SELECT *
                                FROM collection
                                WHERE card_card_id = ?;");
        $remove->bindValue(1, $idToRemove, PDO::PARAM_INT);

        $remove->execute();

        $row = $remove->fetch();

        $copiesAlreadyThere = intval($row['card_copies']);

        //If the copies are >= than the copies saved
        if($copiesToRemove >= $copiesAlreadyThere){
            $remove = $conn->prepare("DELETE
                                FROM collection
                                WHERE card_card_id = ?;");
            $remove->bindValue(1, $idToRemove, PDO::PARAM_INT);

            $remove->execute();

            echo
                "<script type='text/javascript'>
                    alert('Cards removed from collection, redirecting...');
                    location='/php/myCollection.php';
                </script>";

            return true;
        }
        //If the copies are < than the copies saved
        else{
            $copiesToRemove = $copiesAlreadyThere - $copiesToRemove;

            $remove = $conn->prepare("UPDATE collection
                                    SET card_copies = ?
                                    WHERE card_card_id = ?;");
            $remove->bindValue(1, $copiesToRemove, PDO::PARAM_INT);
            $remove->bindValue(2, $idToRemove, PDO::PARAM_INT);

            $remove->execute();

            echo
                "<script type='text/javascript'>
                    alert('Card copies removed from collection, redirecting...');
                    location='/php/myCollection.php';
                </script>";
            
            return true;
        }


        


    }

    try{
        //DB connection
        $conn = connectToDatabase();

        //Removing cards
        removeFromCollection($_GET['remove'], $_GET['copies']);

    }
    catch(PDOException $e){
        echo "MySQL Error: " . $e->getMessage();
    }
?>