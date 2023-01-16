<html>
  <head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="layout" content="">

    <title>My Decks</title>
  </head>

  <body>
    <div id="nav-placeholder">
        
    </div>

    <!-- Let's begin build our output table -->
    <div class = "container-fluid">
        <table class = "striped highlight centered">
            <thead>
                <tr>
                    <th>Deck Name</th>
                    <th>Deck Author</th>
                    <th>Action</th>
                </tr>
            </thead>

    <?php

        require './databaseConnection.php';

        function showDeckList(){

            global $conn;

            $show = $conn->prepare("USE mtg_collection_manager;");
            $show->setFetchMode(PDO::FETCH_ASSOC);
            $show->execute();

            //Gathering all the cards
            $show = $conn->prepare("SELECT *
                                    FROM deck;");
            
            $show->execute();

            while($row = $show->fetch()){
                echo
                    "<tr>
                        <td>".$row['deck_name']."</td>
                        <td>".$row['deck_author']."</td>
                        <td>
                            <form action='./deckListOrDelete.php' method='get'>
                                <button class='btn waves-effect waves-light' type='submit' name='detail' value='".$row['deck_id']."'>Deck List
                                    <i class='material-icons right'>details</i>
                                </button>
                                <button class='btn waves-effect waves-light' type='submit' name='delete' value='".$row['deck_id']."'>Delete
                                    <i class='material-icons right'>delete_forever</i>
                                </button>
                            </form>
                        </td>
                    </tr>";
            }

        }

        try{
            //DB connection
            $conn = connectToDatabase();

            //Calling the function
            showDeckList();


        }
        catch(PDOException $e){
            echo "MySQL Error: " . $e ->getMessage();
        }



    ?>

    </table>
    </div>
    <hr>
    <div class = "container">
        <p class = "row">
            <b>Create a New Deck: </b>
        </p>
        <div class = "row">
            <div class = "col s12">
                <form action = "/php/createDeck.php" method = "GET">
                    <div class = "row">
                        <div class = "input-field col s5">
                            <input id="deck_name" type="text" class="validate" name="deck_name">
                            <label for="deck_name">Deck Name</label>
                        </div>
                        <div class = "input-field col s5">
                            <input id="deck_author" type="text" class="validate" name="deck_author">
                            <label for="deck_author">Deck Author</label>
                        </div>
                        <div class = "col s2">
                            <button class="btn waves-effect waves-light" type="submit" name="action">Create
                                <i class="material-icons right">create</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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