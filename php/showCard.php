<html>
  <head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="layout" content="">

    <title>Card Details</title>
  </head>

  <body>
    <div id="nav-placeholder">
        
    </div>
    <div class = "container">

<?php
    require './databaseConnection.php';

    function getCardInfo($id){

        global $conn;

        $getCards = $conn->prepare("USE mtg_collection_manager;");
        $getCards->setFetchMode(PDO::FETCH_ASSOC);
        $getCards->execute();
        
        $getCards = $conn->prepare("SELECT *
                                    FROM card
                                    WHERE card_id = ?;");

        $getCards->bindValue(1, $id, PDO::PARAM_INT);

        $getCards->execute();

        return $getCards;

    }

    function getSavedDecks(){
        global $conn;

        $getDecks = $conn->prepare("USE mtg_collection_manager;");
        $getDecks->setFetchMode(PDO::FETCH_ASSOC);
        $getDecks->execute();

        $getDecks = $conn->prepare("SELECT deck_id AS ID, deck_name AS DECKNAME
                                    FROM deck;");

        $getDecks->execute();

        return $getDecks;
    }

    try{
        //Connecting to DB
        $conn = connectToDatabase();

        $getCardInfo = getCardInfo($_GET['detail']);

        $deckList = getSavedDecks();

        $card = $getCardInfo->fetch();

        echo "<div class='row'>
                <div class='card col s5'>
                    <div class='card-image'>
                        <img src='https://img.scryfall.com/cards/large/en/".$card['expansion']."/".$card['card_number'].".jpg'>
                    </div>
                </div>
                <div class='card col s5 offset-s1'>
                    <div class='card-action black-text'>
                        <b>".$card['card_name']."</b>
                    </div>
                    <div class='card-action black-text'>
                        ".ucwords($card['card_type']);
                        if($card['card_type'] == "creature" || $card['card_loyalty'] != "ph"){
                            echo " - ".ucwords($card['card_clan']);
                        }
                    echo "</div>
                    <div class='card-action black-text'>
                        ".$card['card_description']."
                    </div>";
                        if($card['card_type'] == "creature"){
                            echo 
                            "<div class='card-action black-text'>
                                <b>Power / Toughness:</b> ".$card['card_power']." / ".$card['card_toughness']."
                            </div>";      
                        } elseif ($card['card_loyalty'] != "ph") {
                            echo
                            "<div class='card-action black-text'>
                                <b>Loyalty:</b> ".$card['card_loyalty']."
                            </div>";
                        }
                    echo "  
                        <div class='card-action black-text'>
                        <div class='col s6'>
                            <p><b>Add card to: </b></p>
                            <form action='insertCard.php' method='GET'>
                                <p>
                                    <label>
                                        <input name='collection' type='checkbox' value='".$card['card_id']."' />
                                        <span>My Collection</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input name='wishlist' type='checkbox' value='".$card['card_id']."' />
                                        <span>My Wishlist</span>
                                    </label>
                                </p>
                                </div>
                                <div class='col s6'>
                                    <p>
                                        <label>
                                            <input name='deck' type='radio' value='0' class='with-gap' checked />
                                            <span>None</span>
                                        </label>
                                    </p>";
                                while($deck = $deckList->fetch()){
                                    echo 
                                        "<p>
                                            <label>
                                                <input name='deck' type='radio' value='".$deck['ID']."' class='with-gap' />
                                                <span>".$deck['DECKNAME']."</span>
                                            </label>
                                        </p>";
                                }    
                                echo "</div>
                                    <p>
                                        <b>Number of copies: </b>
                                    </p>
                                    <p class='range-field'>
                                        <input type='range' id='cardRange' min='1' max='4' name='copies' />
                                    </p>
                                    <p>
                                        <button class='btn waves-effect waves-light tooltipped' type='submit' name='card' value='".$card['card_id']."'
                                            data-position='bottom' data-tooltip='If you select both, it will be added only to collection'>Save
                                            <i class='material-icons right'>save</i>
                                        </button>
                                    </p>
                            </form>
                        </div>                
                </div>
            </div>";
    }
    catch(PDOException $e){
        echo "MySQL Error: " . $e->getMessage();
    }


?>

    </div>

    <!--JavaScript at end of body for optimized loading-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/js/materialize.js"></script>
    <script>
            $(document).ready(function(){
                $("#nav-placeholder").load("/html/navbar.html");
                M.AutoInit();
            });
    </script>

  </body>
</html>