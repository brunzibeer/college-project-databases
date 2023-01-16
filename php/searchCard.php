<html>
  <head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="layout" content="">

    <title>Search Results</title>
  </head>

  <body>
    <div id="nav-placeholder">
        
    </div>

    <!-- Let's begin build our output table -->
    <div class = "container-fluid">
        <table class = "striped highlight centered">
            <thead>
                <tr>
                    <th>Card Name</th>
                    <th>Converted Mana Cost</th>
                    <th>Card Set</th>
                    <th>Show</th>
                </tr>
            </thead>
        


<?php 
    require './databaseConnection.php';

    function searchCardByName($search){

        global $conn;


        //This will be the query, cards will be in alphabetical order
        $search = '%'.$search.'%';
        $getCards = $conn->prepare("USE mtg_collection_manager;");
        $getCards->setFetchMode(PDO::FETCH_ASSOC);
        $getCards->execute();
        $getCards = $conn->prepare("SELECT card_id as ID, card_name as CARDNAME, expansion as CARDSET,
                                    conv_mana AS CONVMANA, card_number AS CNUMBER
                                    FROM card 
                                    WHERE LOWER(card_name) LIKE ? 
                                    ORDER BY CARDNAME ASC;");

        $getCards->bindValue(1, $search, PDO::PARAM_STR);

        //Execute the query
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
        
        //Connecting to database
        $conn = connectToDatabase();

        //Gathering results
        $toReturn = searchCardByName($_GET['search']);


        while ($row = $toReturn->fetch()) {
            //I now print all the info I want for every card whom matches the query
            echo "<tr>".
                        "<td>".$row["CARDNAME"]."</td>".
                        "<td>".$row["CONVMANA"]."</td>".
                        "<td>".$row["CARDSET"]."</td>".
                        "<td>
                            <form action='./showCard.php' method='get'>
                                <button class='btn waves-effect waves-light' type='submit' name='detail' value='".$row['ID']."'>Card Details
                                    <i class='material-icons right'>details</i>
                                </button>
                            </form>
                        </td>".
                "</tr>";
         }
    
    }
    catch(PDOException $e){
        echo "MySQL Error: " . $e->getMessage();
    }
?>

        </table>
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

