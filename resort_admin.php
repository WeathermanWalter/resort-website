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

//for deleting a user
if (isset($_POST['user_submit'])) {
    //code for deleting user
    $user_id = $_POST['user_id'];
    
    //checking if user selected me
    if ($user_id == 1) {
        //user selected ME, the audacity of some people
        ?>
        <div class=entry>
            <h1>Error</h1>
            <p>You tried to delete the owner from the database?</p>
            <iframe width="100%" height="443" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <?php
        require('resort_footer.php');
        exit;
    }
    //user did not select me

    //opening database
    try {
        $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);

        //getting user details
        $user = $db->query("SELECT * FROM resort_users WHERE id = $user_id")->fetch();

        //clearing users rooms
        $db->exec("UPDATE resort_rooms SET status = 'vacant', user_id = NULL WHERE user_id = $user_id");

        //clearing user
        $db->exec("DELETE FROM resort_users WHERE id = $user_id");

        //display webpage
        ?>
        <div class="entry">
            <h1>User has been deleted, and their rooms have been cleared</h1>
            <table border="1">
                <tr>
                    <td>First name</td>
                    <td>Last name</td>
                </tr>
                <tr>
                    <?php
                    echo "<td>$user[first]</td>";
                    echo "<td>$user[last]</td>";
                    ?>
                </tr>
            </table>
        </div>
        <?php

    } catch (PDOException $e) {
        echo "Could not open database";
        echo $e->getMessage();
        $db =  null;

        require('resort_footer.php');
        exit(0);
    }

    require('resort_footer.php');
    exit;
}

//for clearing out the room
if (isset($_POST['room_submit'])) {
    //code for clearing room
    $room_num = $_POST['room_id'];

    //opening database
    try {
        $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);

        //getting the room
        $room = $db->query("SELECT * FROM resort_rooms WHERE number = $room_num")->fetch();

        //see if user selected me
        if ($room['user_id'] == 1 && $_SESSION['valid_id'] != 1) {
            //user selected ME, the audacity of some people
            ?>
            <div class=entry>
                <h1>Error</h1>
                <p>
                    You tried to kick the owner out of his own room? <br/>
                    Well I cant blame you for wanting my room, but this room is mine<br/>
                    But I can show you a nice video
                </p>
                <iframe width="100%" height="443" src="https://www.youtube.com/embed/kTcRRaXV-fg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <?php
            require('resort_footer.php');
            exit;
        }

        //clearing room
        $db->exec("UPDATE resort_rooms SET status = 'vacant', user_id = NULL, cleanliness = 'clean' WHERE number = $room_num");

        //clearing database
        $db = NULL;

        //displaying webpage
        echo "<div class='entry'>";
        echo "<h1>Cleared room</h1>";
        echo "<table border='1'>";

        echo "<tr>";
        echo "<td>Number</td>";
        echo "<td>Status</td>";
        echo "<td>Cleanliness</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td>$room_num</td>";
        echo "<td>Vacant</td>";
        echo "<td>Clean</td>";
        echo "</tr>";

        echo "</table>";
        echo "</div>";

        require('resort_footer.php');
        exit;

    } catch (PDOException $e) {
        echo "Could not open database";
        echo $e->getMessage();
        $db =  null;

        require('resort_footer.php');
        exit(0);
    }

    require('resort_footer.php');
    exit;
}

//code to set admin rights for the session
if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    $login = $_POST['login'];
    if ($login == "Admin" && $password == "Admin") {
        $_SESSION['is_admin'] = 'yes';
    } else {
        echo "<div class = 'entry'>";
        echo "<h1>Login was incorrect</h1>";
        echo "</div>";
    }
}

if ($_SESSION['is_admin'] == 'no')
{
?>
<!-- Display a form to capture information -->
<div class="entry">
    <h1>Enter login credentials</h1>
    <h3>This will last you the whole session</h3>
    <form action="resort_admin.php" method="post">
        <table border="0">
            <tr>
                <td>Login</td>
                <td align="left"><input type="text" name="login" size="35" maxlength="35"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td align="left"><input type="password" name="password" size="35" maxlength="35"></td>
            </tr>
            <tr>
                <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
            </tr>
        </table>
        <h6>*login:'Admin' <br/> *Password:'Admin'</h6>
    </form>
</div>
<?php
} else {

    echo "<div class='entry'>";
    echo "<h1>Admin Page</h1>";
    echo"</div>";

    //display admin page
    //opening database
    try {
        $db = new PDO(DB_PATH, DB_LOGIN, DB_PW);

        //getting users
        $users = $db->query("SELECT * FROM resort_users");

        //display users
        echo "<div class='entry'>";
        echo "<h1>Delete a user</h1>";
        echo "<form action='resort_admin.php' method='post'>";
        echo "<h3>Resort Users</h3>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td></td>";
        echo "<td>First name</td>";
        echo "<td>Last name</td>";
        echo "</tr>";
        foreach ($users as $row) {
            echo "<tr>";
            echo "<td><input type='radio' name='user_id' value=".$row['id']."></td>";
            echo "<td>".$row['first']."</td>";
            echo "<td>".$row['last']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='submit' name='user_submit' value = 'Submit'/><br/>";
        echo "</form>";
        echo "</div>";

        //getting rooms
        $rooms = $db->query("SELECT * FROM resort_rooms");

        //display rooms
        echo "<div class='entry'>";
        echo "<h1>Clear/Clean a room</h1>";
        echo "<h3>Cleans and evicts any room</h3>";
        echo "<form action='resort_admin.php' method='post'>";
        echo "<h3>Resort Rooms</h3>";
        echo "<table border='1'>";
        echo "<tr>";

        echo "<td></td>";
        echo "<td>Room</td>";
        echo "<td>Occupation</td>";
        echo "<td>Cleanliness</td>";

        echo "</tr>";
        foreach ($rooms as $row) {
            echo "<tr>";
            echo "<td><input type='radio' name='room_id' value=".$row['number']."></td>";
            echo "<td>".$row['number']."</td>";

            //finding name of whoever occupies the room
            if ($row['status'] == "vacant") {
                echo "<td>Vacant</td>";
            } else {
                $id = $row['user_id'];
                $user = $db->query("SELECT first,last FROM resort_users WHERE id = $id")->fetch();
                echo "<td>$user[first] $user[last]</td>";
            }
            //getting cleanliness
            if ($row['cleanliness'] == clean) {
                echo "<td bgcolor='#8cff66'>$row[cleanliness]</td>";
            } else {
                echo "<td bgcolor='#ff0000'>$row[cleanliness]</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='submit' name='room_submit' value = 'Submit'/><br/>";
        echo "</form>";
        echo "</div>";

        //closing database
        $db = NULL;

    } catch (PDOException $e) {
        echo "Could not open database";
        echo $e->getMessage();
        $db =  null;

        require('resort_footer.php');
        exit(0);
    }
}

require('resort_footer.php');
?>
