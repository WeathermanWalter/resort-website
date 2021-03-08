<!doctype html>
<?php
session_start();
require('resort_functions.php');
html_head('Resort');
require('resort_header.php');
require('resort_nav.php');
require('resort_values.php');

if(we_are_not_logged_in()){
    exit;
}

#code for webpage
if (!isset($_POST['submit'])) {

//opening database up
try {
    $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
    //checking if there are any rooms
    $results = $db->query("SELECT count(*) FROM resort_rooms WHERE user_id = $_SESSION[valid_id]")->fetch();
    if ($results[0] == 0) {
        //the user has no rooms booked
        echo "<div class='entry'>";
        echo "<h1>You have booked no rooms in your name</h1>";

        echo "<h4>You can book a room today</h4>";
        echo "<a href='resort_book.php'><div class='buttons button-nav'>";
        echo "Book</div></a>";

        echo "</div>";
        require('resort_footer.php');
        $db = NULL;
        exit;
    }

    //getting rooms checked out to user
    $results = $db->query("SELECT * FROM resort_rooms WHERE user_id = $_SESSION[valid_id]");

    //closing off the connection
    $db = NULL;


    } catch (PDOException $e) {
        echo "Could not open database";
        echo $e->getMessage();
        $db =  null;

        require('resort_footer.php');
        exit(0);
    }
    ?>
    <div class="entry">
        <!--display a table of rooms checked into the user-->
        <form action="resort_checkout.php" method="post">
            <h1>Check out of your room</h1>
            <font size='5'>
                <table border="1">
                    <tr>
                        <td>Click one to checkout</td>
                        <td>Room number</td>
                        <td>Status</td>
                    </tr>

                    <?php
                    foreach ($results as $row) {
                        echo "<tr>";
                        echo "<td><input type='radio' name='number' value=".$row['number']."></td>";
                        echo "<td>".$row['number']."</td>";
                        echo "<td>".$row['status']."</td>";
                        echo "<tr>";
                    }
                    ?>
                </table>
            </font>
            <!--submit button-->
            <input type='submit' name='submit' value = 'Submit'/><br/>
        </form>
    </div>
    <?php
} else {
    # Process the information from the form displayed
    if (!isset($_POST['number'])) {
        //user did not make a selection
        try_again("You did not make a choice");
    }
    //user did make a choice
    $roomNumber = $_POST['number'];


    try {
        //opening database
        $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
        
        //removing person from room
        $db->query("UPDATE resort_rooms SET status = 'vacant', user_id = NULL WHERE number = $roomNumber");

    } catch (PDOException $e) {
        echo "Could not open database";
        echo $e->getMessage();
        $db =  null;

        require('resort_footer.php');
        exit(0);
    }
    ?>
    <div class="entry">
        <h1>Success, you have checked out of your room</h1>
        <table border="1">
            <tr>
                <td>Name</td>
                <td>Room Number</td>
                <td>Status</td>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>Available</td>";
            echo "<td>$roomNumber</td>";
            echo "<td>vacant</td>";
            echo "</tr>";
            ?>
        </table>
    </div>
    <?php
}

require('resort_footer.php');
?>
