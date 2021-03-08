<!doctype html>
<?php
session_start();
require('resort_values.php');
require('resort_functions.php');
html_head('Resort');
require('resort_header.php');
require('resort_nav.php');

#code for webpage
//did the user enter a login and password?
if (isset($_POST['email']) && isset($_POST['password'])) {
    //log user in
    $email = $_POST['email'];
    $passwd = $_POST['password'];
    $password = sha1($passwd);


    try {
        //setting up database
        $db = new PDO(DB_PATH,DB_LOGIN,DB_PW);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //check for login in the users table
        $results = $db->query("SELECT count(*) FROM resort_users WHERE email = '$email' AND password = '$password'")->fetch();
         //count the number of entries with the login name

        //if we find something, the person is real
        if ($results[0] == 1) {
            //login user
            $results = $db->query("SELECT * FROM resort_users WHERE email = '$email'")->fetch();
            $_SESSION['valid_user'] = $results['first'];
            $_SESSION['valid_id'] = $results['id'];
            $_SESSION['is_admin'] = $results['is_admin'];
        }

    } catch (PDOException $e) {
        echo 'Exception : '.$e->getMessage().'<br/>';
        $db = NULL;
    }
    //closing off db
    $db = NULL;
}

//display site if user is logged in
if (isset($_SESSION['valid_user'])) {
    echo "<div class='entry'>";
    echo "<h1>You are logged in as ".$_SESSION['valid_user']."</h1>";

    echo "<h3>Please book a room to claim it</h3>";

    //display book button
    echo "<a href='resort_book.php'><div class='buttons button-nav'>";
    echo "Book</div></a>";
    
    //display logout button
    echo "<a href='resort_signout.php'><div class='buttons button-nav'>";
    echo "Sign out";
    echo "</div></a>";
    echo "</div>";
} else {
?>
<!-- Display a form to capture information -->
<div class="entry">
<h1>
    Welcome to the Wordelman Resort<br/>
    Please sign in to claim your booked room<br/>
</h1>
<form action="resort_home.php" method="post">
    <table border="0">
        <tr>
            <th><h3>Email:</h3></th>
            <th><input type="text" name="email"></th>
        </tr>
        <tr>
            <th><h3>Password:</h3></th>
            <th><input type="password" name="password"></th>
        </tr>
    </table>
    <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
</form>
</div>
<br/>
<?php
}

require('resort_footer.php');
?>
