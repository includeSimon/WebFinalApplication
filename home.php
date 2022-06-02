<?php
session_start();
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

if (!isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit();
}
//show owned cars

//the carId stores cars id and the car array stores all information about cars
$ownedCarsId = [];
$ownedCars = [];

//get car id
$stmt = $link->prepare("SELECT carId FROM car_user where userId = ?");
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

//add results to array
foreach ($result as $row){
    $ownedCarsId[] = $row;
}

// store cars
foreach ($ownedCarsId as $id) {
    $stmt = $link->prepare("SELECT * FROM car where carId = ?");
    $stmt->bind_param("i", $id['carId']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach ($result as $row)
        $ownedCars[] = $row;

    $stmt->close();

}
    ?>


    <!DOCTYPE html>
<style>
    .image{
        max-width: 5%;
    }
</style>
    <html>
    <head>
        <title>Home</title>
    </head>
    <body>
        <h1>Hello, <?php echo $_SESSION['username']; ?></h1>
        <ul>
            <li>
                <a href="db_connection.php">Test database connection</a>
            </li>
            <li>
                <a href="db_operations.php">Insert car</a>
            </li>
            <li>
                <a href="home.php">Home</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
 <h2>Owned cars</h2>
<table>
    <thead>
    <th>Id</th>
    <th>Model</th>
    <th>Maker</th>
    <th>Electric</th>
    </thead>
    <tbody>
    <?php
    foreach ($ownedCars as $row) {
        $stmt = $link->prepare("SELECT imageName FROM car_image where carId = ?");
        $stmt->bind_param("i", $row["carId"]);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach ($result as $row2)
            $imageName = $row2['imageName'];
        $source = "images/".$imageName.".jpg";
        ?>
        <tr>
            <td><?php echo $row['carId'] ?></td>
            <td><?php echo $row['model'] ?></td>
            <td><?php echo $row['maker'] ?></td>
            <td><?php echo $row['electric'] ?></td>
            <td><img src="<?php echo $source?>" alt="bmw_m2" class="image"></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
    </body>
    </html>
