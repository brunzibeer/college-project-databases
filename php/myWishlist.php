<html>
  <head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="layout" content="">

    <title>My Wishlist</title>
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
                    <th># of Copies</th>
                    <th>Card Set</th>
                    <th>Show</th>
                    <th>Action</th>
                </tr>
            </thead>

    <?php

        require './databaseConnection.php';

        function showWishlist(){

            global $conn;

            $show = $conn->prepare("USE mtg_collection_manager;");
            $show->setFetchMode(PDO::FETCH_ASSOC);
            $show->execute();

            //Gathering all the cards
            $show = $conn->prepare("SELECT card.card_name AS C_NAME, card.expansion AS EXPANSION, 
                                    wishlist.card_copies AS COPIES, card.card_id AS ID
                                    FROM wishlist, card
                                    WHERE wishlist.card_card_id = card.card_id;");
            
            $show->execute();

            while($row = $show->fetch()){
                echo
                    "<tr>
                        <td>".$row['C_NAME']."</td>
                        <td>".$row['COPIES']."</td>
                        <td>".$row['EXPANSION']."</td>
                        <td>
                            <form action='./showCard.php' method='get'>
                                <button class='btn waves-effect waves-light' type='submit' name='detail' value='".$row['ID']."'>Card Details
                                    <i class='material-icons right'>details</i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action='./removeFromWishlist.php' method='get'>
                            <p>
                                <label>
                                    <input name='copies' type='radio' class='with-gap' value='1' checked />
                                    <span>1</span>
                                </label>
                                <label>
                                    <input name='copies' type='radio' class='with-gap' value='2'  />
                                    <span>2</span>
                                </label>
                                <label>
                                    <input name='copies' type='radio' class='with-gap' value='3'  />
                                    <span>3</span>
                                </label>
                                <label>
                                    <input name='copies' type='radio' class='with-gap' value='4'  />
                                    <span>4</span>
                                </label>
                            </p>
                                <button class='btn waves-effect waves-light' type='submit' name='remove' value='".$row['ID']."'>Remove
                                    <i class='material-icons right'>delete</i>
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
            showWishlist();


        }
        catch(PDOException $e){
            echo "MySQL Error: " . $e ->getMessage();
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