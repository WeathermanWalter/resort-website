<?php
function html_head($title) {
  echo '<html lang="en">';
  echo '<head>';
  echo '<meta charset="utf-8">';
  echo "<title>$title</title>";
  echo '<link rel="stylesheet" type="text/css" href="resort.php">';
  echo '</head>';
  echo '<body>';
}

function try_again($str) {
    echo "<div class='entry'>";
    echo $str;
    echo "<br>";
    //the following will emulate the back button
    echo "<div class='buttons buttons-nav>";
    echo '<a href="#" onclick="history.back(); return false;">Try Again</a>';
    echo "</div>";
    echo "</div>";
    require('resort_footer.php');
    exit(0);
}

// Check to see if we are logged in as an admin
function we_are_not_logged_in() {
    if (empty($_SESSION['valid_user'])) {
        echo "<div class='entry'>";
        echo "<h2>Only registered guests can use this function.</h2><br/>";

        echo"<a href='resort_home.php'><div class='buttons button-nav'>";
        echo "Login";
        echo "</div></a>";

        echo"<a href='resort_signup.php'><div class='buttons button-nav'>";
        echo "Sign up";
        echo "</div></a>";


        echo "</div>";
        require('resort_footer.php');
        return logged_in();
	}
}

function logged_in() {
    return (empty($_SESSION['valid_user']));
}
?>
