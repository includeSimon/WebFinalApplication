<?php
session_start();

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'car_shop');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$username = $_POST["username"];
$password = $_POST["password"];

$sqldb = "SELECT * FROM user WHERE username= '".$username."' and password =  '".$password."'";
//$sql = $link->prepare($sqldb);
//$sql->bind_param("ss", $username, $password);
//$sql -> execute();
$result = $link->query($sqldb);
if ($result->num_rows > 0) {
    $_SESSION['logged_in'] = 'yes';

    while ($row = $result->fetch_assoc()) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['userId'] = $row['userId'];
    }
    header("location: home.php");
} else {
    header("location: login.php");
}
