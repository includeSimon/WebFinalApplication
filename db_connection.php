<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'car_shop');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}



// Check connection
$connected = true;
$error = "";
if ($link->connect_errno) {
    $connected = false;
    $error = $link->connect_error;
}


if ($connected) {
?>
    <h1>Conectat cu succes!</h1>
    <h3>Infos: </h3>
    <div>Host: <?php echo DB_SERVER ?></div>
    <div>Username: <?php echo DB_USERNAME ?></div>
    <div>Password: <?php echo DB_PASSWORD ?></div>
    <div>DbName: <?php echo DB_NAME ?></div>
<?php
} else {
?>
    <h1>Nu s-a putut conecta :( </h1>
    <h3>Eroare: </h3>
    <div> <?php echo $error ?></div>
<?php
}


?>

<body>
    <p><a href="home.php">Back to home page</a>.</p>
</body>