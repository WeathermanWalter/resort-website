<!doctype html>
<?php
session_start();
require('resort_functions.php');
html_head('Resort');
require('resort_header.php');
require('resort_nav.php');

if(we_are_not_logged_in()){
    exit;
}

#code for webpage
if (!isset($_POST['submit']))
{
?>
<!-- Display a form to capture information -->
<h2>Your Title</h2>
<form action="resort_skeleton.php" method="post">
    <table border="0">
        <tr bgcolor="#cccccc">
            <td width="100">Field</td>
            <td width="300">Value</td>
        </tr>
        <tr>
            <td>Your Field</td>
            <td align="left"><input type="text" name="yourfield" size="35" maxlength="35"></td>
        </tr>
        <tr>
            <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"></td>
        </tr>
    </table>
</form>
<?php
} else {
    # Process the information from the form displayed
    $yourfield = $_POST['yourfield'];
    echo "<h1>This page is under construction</h1>";
    echo "<p>YOur field is:$yourfield</p>";
}

require('resort_footer.php');
?>
