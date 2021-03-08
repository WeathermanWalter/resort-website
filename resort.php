<?php header("Content-type: text/css; charset: UTF-8"); ?>

<?php
    //pick a random photo
    $files = scandir("Images");
    //TODO parse each file, finding epmty directories, and not include them
    do {
        $photo = $files[rand(2, sizeof($files))];
    } while (empty($photo));
?>

.header {
    background-image: url(Images/<?php echo $photo; ?>);
    color: #F0F0F0;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    padding: 5px;
    border-bottom: 3px solid darken(#237CBE, 10%);
    text-align: center;
    text-shadow: 2px 2px #000000;
}

.buttons {
    background-color: #ffaa80;
    border: none;
    color: white;
    padding: 16px 0px;
    text-align: center;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    transition-duration: 0.4s;
}

.button-nav {
    width: 20%;
    font-family: Arial, Helvitica, sans-serif;
    font-weight: bold;
    font-size: x-large;
}

.buttons:hover {
    background-color: #66b3ff;
    color: #ffb266;
}

/*input field*/
input[type=text] {
    font-family: Arial, Helvitica, sans-serif;
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 3px solid #ccc;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    outline: none;
}

input[type=password]:focus {
    border: 3px solid #555;
}

input[type=password] {
    font-family: Arial, Helvitica, sans-serif;
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 3px solid #ccc;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    outline: none;
}

input[type=text]:focus {
    border: 3px solid #555;
}

.entry {
    margin: 4px;
    padding: 10px 20px;
    font-family: Arial, Helvitica, sans-serif;
    background-color: #ffd9b3;
    border: none;
    width: 40%;
}

footer {
    text-align: center;
    margin:0;
    padding:5px;
    background-color: #ffaa80;
    color: white;
}
