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
if (!isset($_POST['submit']))
{

    try {
        //opening database
        $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
        
        //checking if there are any rooms
        $results = $db->query("SELECT count(*) FROM resort_rooms WHERE status = 'vacant'")->fetch();
        if ($results[0] == 0) {
            //the user has no rooms booked
            echo "<div class='entry'>";
            echo "<h1>We are all booked</h1>";

            echo "</div>";
            require('resort_footer.php');
            $db = NULL;
            exit;
        }

        //getting avaliable rooms
        $results = $db->query("SELECT * FROM resort_rooms WHERE status = 'vacant'  AND cleanliness = 'clean'");
        ?>
        
        <!--display table of avaliable rooms -->
        <div class='entry'>
            <h1>Book a room</h1>
            <form action='resort_book.php' method='post'>
                <font size='5'>
                    <table border='1' font-size='x-large'>
                        <tr>
                            <td>Click one to book</td>
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
                <h4>NOTE: dirty rooms are not listed until they are clean</h4>
            </form>
        </div>
        <?php
    } catch (PDOException $e) {
        echo "Could not open database";
        echo $e->getMessage();
        $db =  null;

        require('resort_footer.php');
        exit(0);
    }

} else {
    # Process the information from the form displayed
    $roomNumber = $_POST['number'];

    //update room to be occupied
    try {
        //opening database
        $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);
        //inserting data into room
        $db->query("UPDATE resort_rooms SET status = 'occupied', user_id = $_SESSION[valid_id], cleanliness = 'dirty' WHERE number = $roomNumber");
    } catch (PDOException $e) {
        echo "Could not open database";
        echo $e->getMessage();
        $db = NULL;

        require('resort_footer.php');
        exit(0);
    }
?>

<!-- confirm that the user booked their room -->
<div class='entry'>
    <h1>Sucess, your room has been reserved for you</h1>
    <table border="1">
        <tr>
            <td>Name</td>
            <td>Room Number</td>
            <td>Status</td>
        </tr>
        <?php
        echo "<tr>";
        echo "<td>$_SESSION[valid_user]</td>";
        echo "<td>$roomNumber</td>";
        echo "<td>occupied</td>";
        echo "</tr>";
        ?>
    </table>
</div>

<?php
}

require('resort_footer.php');
?>
