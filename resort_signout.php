<!doctype html>
<?php
session_start();
require('resort_values.php');
require('resort_functions.php');
html_head('Resort');
require('resort_header.php');
require('resort_nav.php');

$old_user = $_SESSION['valid_user'];
unset($_SESSION['valid_user']);
session_destroy();

echo "<div class='entry'>";
echo "<h1>Log Out</h1>";

if(empty($old_user)) {
    echo "Already logged out<br/>";
} else {
    echo "Logged out<br/>";
}
echo "</div>";

require('resort_footer.php');
?>
