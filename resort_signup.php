<!doctype html>
<?php
session_start();
require('resort_functions.php');
html_head('Resort');
require('resort_header.php');
require('resort_nav.php');
require('resort_values.php');

if (isset($_SESSION['valid_user'])) {
    echo "<div class='entry'>";
    echo "<h1>This page is for new users</h1>";

    //display logout button
    echo "<a href='resort_signout.php'><div class='buttons button-nav'>";
    echo "Sign out";
    echo "</div></a>";
    echo "</div>";
    require('resort_footer.php');
    exit;
}

#code for webpage
if (!isset($_POST['submit']))
{
?>
<!-- Display a form to capture information -->
<div class="entry">
<h1>Sign up</h1>
<form action="resort_signup.php" method="post">
    <table border="0">
        <tr>
            <th><h3>First name:</h3></th>
            <th><input type="text" name="first"></th>
        </tr>
        <tr>
            <th><h3>Last name:</h3></th>
            <th><input type="text" name="last"></th>
        </tr>
        <tr>
            <th><h3>Password:</h3></th>
            <th><input type="password" name="password"></th>
        </tr>
        <tr>
            <th><h3>Email:</h3></th>
            <th><input type="text" name="email"></th>
        </tr>
    </table>
    <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
</form>
</div>
<br/>
<?php
} else {
    # Process the information from the form displayed
    $first = $_POST['first'];
    $last = $_POST['last'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //clean up and validate data
    $first = trim($first);
    if (empty($first)) {
        try_again("First name cannot be empty.");
    }
    $last = trim($last);
    if (empty($last)) {
        try_again("Last name cannot be empty.");
    }
    $email = trim($email);
    if (empty($email)) {
        try_again("Email cannot be empty.");
    }
    $password = trim($password);
    if (empty($password)) {
        try_again("Password can not be emptie");
    }

    //hashing the password
    $password = sha1($password);

    try {
        //setting up database
        $db = new PDO(DB_PATH,DB_LOGIN,DB_PW);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //check for duplicate user name
        $results = $db->query("SELECT count(*) FROM resort_users WHERE first = '$first' AND last = '$last'")->fetch();
        if ($results[0] > 0) {
            try_again($first." ".$last." is not unique. Names must be unique.");
        }

        //check for duplicate email
        $sql = "SELECT count(*) FROM resort_users WHERE email = '$email'";
        $results = $db->query($sql)->fetch(); //count the number of entries with the email
        if ( $results[0] > 0) {
            try_again("$email is not unique. Email must be unique.");
        }

        //Adding new entry into the database
        $db->query("INSERT INTO resort_users (first, last, email, password, is_admin) VALUES ('$first', '$last', '$email', '$password', 'no');");

        //displaying webpage
        echo "<div class='entry'>";
        echo "<h1>Suceess, added new user</h1>";

        //display the table
        echo "<table border='0'>";
        echo "<tr>";
        echo "<th><h3>First name:</h3></th>";
        echo "<th><h4>$first</h4></th>";
        echo "</tr>";

        echo "<tr>";
        echo "<th><h3>Last name:</h3></th>";
        echo "<th><h4>$last</h4></th>";
        echo "</tr>";

        echo "<tr>";
        echo "<th><h3>Email:</h3></th>";
        echo "<th><h4>$email</h4></th>";
        echo "</tr>";

        echo "</table>";

        //display buttons
        echo"<a href='resort_home.php'><div class='buttons button-nav'>";
        echo "Login";
        echo "</div></a>";

    } catch (PDOException $e) {
        echo ("Could not open database<br>");
        echo 'Exception : '.$e->getMessage();
        $db = null;
        exit(1);
    }
    echo "</div>";
}

require('resort_footer.php');
?>
