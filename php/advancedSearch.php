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

  require "./databaseConnection.php";

  function advancedSearch(){

    global $conn;

    /*
    * Basically the idea behind this function is to search for cards with more than 1 parameter specified
    * If i take all the fields input(even if they're blank) and surround them with %% I can easily perform this
    * If the input is null, the variable will look like this "%%", this means that all the value are accepted(no filter specified)
    * The only things I have to check are numbers, because I don't want that a 2 can find a 20 for example
    */

    $adv = $conn->prepare("USE mtg_collection_manager;");
    $adv->setFetchMode(PDO::FETCH_ASSOC);
    $adv->execute();

    //First
    $name = '%'.$_GET['advName'].'%';
    $description = '%'.$_GET['advDesc'].'%';
    $type = '%'.$_GET['advType'].'%';
    $set = '%'.$_GET['advSet'].'%';
    $power = '%'.$_GET['advPow'].'%';
    $toughness = '%'.$_GET['advTou'].'%';
    $loyalty = '%'.$_GET['advLoy'].'%';
    $w = '%'.$_GET['advW'].'%';
    $u = '%'.$_GET['advU'].'%';
    $b = '%'.$_GET['advB'].'%';
    $r = '%'.$_GET['advR'].'%';
    $g = '%'.$_GET['advG'].'%';
    $c = '%'.$_GET['advC'].'%';
    $cost = '%'.$_GET['advCost'].'%';

    //Now that I have everything, I can start build the query
    $adv = $conn->prepare("SELECT card_name as C_NAME, conv_mana AS MANA, expansion AS EXPANSION, card_id AS ID
                          FROM card
                          WHERE LOWER(card_name) LIKE ? AND LOWER(card_description) LIKE ? AND LOWER(card_type) LIKE ?
                          AND conv_mana LIKE ? AND card_power LIKE ? AND card_toughness LIKE ? AND card_loyalty LIKE ?
                          AND LOWER(expansion) LIKE ? AND white_mana LIKE ? AND blue_mana LIKE ? AND black_mana LIKE ?
                          AND red_mana LIKE ? AND green_mana LIKE ? AND colorless_mana LIKE ?
                          ORDER BY C_NAME ASC;");
    $adv->bindValue(1, $name, PDO::PARAM_STR);
    $adv->bindValue(2, $description, PDO::PARAM_STR);
    $adv->bindValue(3, $type, PDO::PARAM_STR);
    $adv->bindValue(4, $cost, PDO::PARAM_STR);
    $adv->bindValue(5, $power, PDO::PARAM_STR);
    $adv->bindValue(6, $toughness, PDO::PARAM_STR);
    $adv->bindValue(7, $loyalty, PDO::PARAM_STR);
    $adv->bindValue(8, $set, PDO::PARAM_STR);
    $adv->bindValue(9, $w, PDO::PARAM_STR);
    $adv->bindValue(10, $u, PDO::PARAM_STR);
    $adv->bindValue(11, $b, PDO::PARAM_STR);
    $adv->bindValue(12, $r, PDO::PARAM_STR);
    $adv->bindValue(13, $g, PDO::PARAM_STR);
    $adv->bindValue(14, $c, PDO::PARAM_STR);

    $adv->execute();

    while($row = $adv->fetch()){

      echo
          "<tr>
            <td>".$row['C_NAME']."</td>
            <td>".$row['MANA']."</td>
            <td>".$row['EXPANSION']."</td>
            <td>
              <form action='./showCard.php' method='get'>
                <button class='btn waves-effect waves-light' type='submit' name='detail' value='".$row['ID']."'>Card Details
                  <i class='material-icons right'>details</i>
                </button>
              </form>
            </td>
          </tr>";

    }

    return true;
    
  }

  try{
        
    //Connecting to database
    $conn = connectToDatabase();

    advancedSearch();

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
