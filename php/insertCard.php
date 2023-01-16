<?php

    require './databaseConnection.php';

    function insertToCollection($copiesToAdd, $idToAdd){

        global $conn;

        //Getting # of copies
        $copiesToAdd = intval($copiesToAdd);

        //Preparing to use my DB
        $card = $conn->prepare("USE mtg_collection_manager;");
        $card->setFetchMode(PDO::FETCH_ASSOC);
        $card->execute();

        //Checking if the card is already in the collection
        $card = $conn->prepare("SELECT *
                                FROM collection
                                WHERE card_card_id = ?;");
        $card->bindValue(1, $idToAdd, PDO::PARAM_INT);

        $card->execute();

        //If there's no card yet
        if($card->rowCount() == 0){
            //I simply insert card into collection
            $card = $conn->prepare("INSERT INTO collection
                                    VALUES (1, ?, ?);");
            $card->bindValue(1, $idToAdd, PDO::PARAM_INT);
            $card->bindValue(2, $copiesToAdd, PDO::PARAM_INT);

            $card->execute();

            echo 
                "<script type='text/javascript'>
                    alert('Card(s) added to collection, redirecting...');
                    location='/php/myCollection.php';
                </script>";
            
            return true;
        }
        //If that card is already in the collection
        else{
            //I get that card row
            $row = $card->fetch();
            $copiesAlreadyThere = intval($row['card_copies']);
            $copiesToAdd += $copiesAlreadyThere;
            //Checking how many copies there are (MTG allows you to have 4 copies of the same card max)
            //If the total will be less or equal than 4
            if($copiesToAdd <= 4){
                //I UPDATE card_copies number
                $card = $conn->prepare("UPDATE collection
                                        SET card_copies = ?
                                        WHERE card_card_id = ?;");
                $card->bindValue(1, $copiesToAdd, PDO::PARAM_INT);
                $card->bindValue(2, $idToAdd, PDO::PARAM_INT);

                $card->execute();

                echo 
                    "<script type='text/javascript'>
                        alert('Card(s) added to collection, redirecting...');
                        location='/php/myCollection.php';
                    </script>";

                return true;
            }
            //If the total will be more than 4
            else{
                //Print an alert and redirecting to homePage
                echo 
                    "<script type='text/javascript'>
                        alert('Card copies limit exceeded, redirecting...');
                        location='/php/myCollection.php';
                    </script>";
                
                    return false;
            }
        }


    }

    function insertToWishlist($copiesToAdd, $idToAdd){

        global $conn;

        //Getting # of copies
        $copiesToAdd = intval($copiesToAdd);

        //Preparing to use my DB
        $card = $conn->prepare("USE mtg_collection_manager;");
        $card->setFetchMode(PDO::FETCH_ASSOC);
        $card->execute();

        //Checking if the card is already in the wishlist
        $card = $conn->prepare("SELECT *
                                FROM wishlist
                                WHERE card_card_id = ?;");
        $card->bindValue(1, $idToAdd, PDO::PARAM_INT);

        $card->execute();

        //If there's no card yet
        if($card->rowCount() == 0){
            //I simply insert card into wishlist
            $card = $conn->prepare("INSERT INTO wishlist
                                    VALUES (1, ?, ?);");
            $card->bindValue(1, $idToAdd, PDO::PARAM_INT);
            $card->bindValue(2, $copiesToAdd, PDO::PARAM_INT);

            $card->execute();

            echo 
                "<script type='text/javascript'>
                    alert('Card(s) added to wishlist, redirecting...');
                    location='/php/myWishlist.php';
                </script>";
            
            return true;
        }
        //If that card is already in the wishlist
        else{
            //I get that card row
            $row = $card->fetch();
            $copiesAlreadyThere = intval($row['card_copies']);
            $copiesToAdd += $copiesAlreadyThere;
            //Checking how many copies there are (MTG allows you to have 4 copies of the same card max)
            //If the total will be less or equal than 4
            if($copiesToAdd <= 4){
                //I UPDATE card_copies number
                $card = $conn->prepare("UPDATE wishlist
                                        SET card_copies = ?
                                        WHERE card_card_id = ?;");
                $card->bindValue(1, $copiesToAdd, PDO::PARAM_INT);
                $card->bindValue(2, $idToAdd, PDO::PARAM_INT);

                $card->execute();

                echo 
                    "<script type='text/javascript'>
                        alert('Card(s) added to wishlist, redirecting...');
                        location='/php/myWishlist.php';
                    </script>";

                return true;
            }
            //If the total will be more than 4
            else{
                //Print an alert and redirecting to homePage
                echo 
                    "<script type='text/javascript'>
                        alert('Card copies limit exceeded, redirecting...');
                        location='/php/myWishlist.php';
                    </script>";
                
                    return false;
            }
        }


    }

    function insertToDeck($copiesToAdd, $idToAdd, $deckIdToAdd){
        
        global $conn;

        //Getting # of copies
        $copiesToAdd = intval($copiesToAdd);

        //Preparing to use my DB
        $card = $conn->prepare("USE mtg_collection_manager;");
        $card->setFetchMode(PDO::FETCH_ASSOC);
        $card->execute();

        //If "None" is selected (NB: This represent the standard redirect also for "Collection" and "Wishlist)
        if($deckIdToAdd == 0){
            echo 
                "<script type='text/javascript'>
                    alert('No options selected, redirecting...');
                    location='/php/myDecks.php';
                </script>";
            
            return false;
        }
        //If a deck is selected
        else{
            //I have to check if the card is already in that specific deck, and how many copies
            $card = $conn->prepare("SELECT *
                                    FROM CardDeck
                                    WHERE card_card_id = ? AND deck_deck_id = ?;");
            $card->bindValue(1, $idToAdd, PDO::PARAM_INT);
            $card->bindValue(2, $deckIdToAdd, PDO::PARAM_INT);

            $card->execute();

            //If there's no card yet
            if($card->rowCount() == 0){
                //I simply add the desired copy to the desired deck
                $card = $conn->prepare("INSERT INTO CardDeck(card_card_id, deck_deck_id, card_copies)
                                        VALUES (?, ?, ?);");
            $card->bindValue(1, $idToAdd, PDO::PARAM_INT);
            $card->bindValue(2, $deckIdToAdd, PDO::PARAM_INT);
            $card->bindValue(3, $copiesToAdd, PDO::PARAM_INT);

            $card->execute();

            echo 
                "<script type='text/javascript'>
                    alert('Card(s) added to selected deck, redirecting...');
                    location='/php/myDecks.php';
                </script>";

            return true;

            }
            //If the card is already in the deck
            else{
                //I get that card row
                $row = $card->fetch();
                $copiesAlreadyThere = intval($row['card_copies']);
                $copiesToAdd += $copiesAlreadyThere;

                //If the total will be 4 or less
                if($copiesToAdd <= 4){
                    //I UPDATE the card copies number
                    $card = $conn->prepare("UPDATE CardDeck
                                            SET card_copies = ?
                                            WHERE card_card_id = ? AND deck_deck_id = ?;");
                    $card->bindValue(1, $copiesToAdd, PDO::PARAM_INT);
                    $card->bindValue(2, $idToAdd, PDO::PARAM_INT);
                    $card->bindValue(3, $deckIdToAdd, PDO::PARAM_INT);

                    $card->execute();

                    echo 
                        "<script type='text/javascript'>
                            alert('Card(s) added to selected deck, redirecting...');
                            location='/php/myDecks.php';
                        </script>";
                    
                    return true;
                }
                //If the total will exceed 4
                else{
                    echo 
                        "<script type='text/javascript'>
                            alert('Card copies limit exceeded, redirecting...');
                            location='/php/myDecks.php';
                        </script>";
                }

            }
        }
    }

    

    try{   

        //DB connection
        $conn = connectToDatabase();

        //If "Collection" is checked
        if(isset($_GET['collection'])){
            insertToCollection($_GET['copies'], $_GET['collection']);
        }

        //If "Wishlist" is checked
        if(isset($_GET['wishlist'])){
            insertToWishlist($_GET['copies'], $_GET['wishlist']);
        }

        //If a "Deck" or "None" is checked
        if(isset($_GET['deck'])){
            insertToDeck($_GET['copies'], $_GET['card'], $_GET['deck']);
        }

        

    }
    catch(PDOException $e){
        echo "MySQL Error: " . $e->getMessage();
    }



?>
