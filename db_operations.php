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


if (isset($_POST['id2'])) {
    // cauta daca exista un rand cu id-ul masinii respective
    $stmt = $link->prepare("SELECT * FROM car_user WHERE carId = ? and userId = ?");
    $stmt->bind_param("ii", $_POST['id2'], $_SESSION['userId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $createStmt = $link->prepare("delete from car_user where carId = ? and userId = ?");
        $createStmt->bind_param("ii", $_POST['id2'], $_SESSION['userId']);
        $createStmt->execute();
        $createStmt->close();
    }
    else echo "The delete id is invalid ";
}
if (isset($_POST['id'])){
    $id = $_POST['id'];

    // cauta daca exista un rand cu id-ul respectiv
    $stmt = $link->prepare("SELECT * FROM car WHERE carId = ?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0){
        //insereaza in car user perechea
        $stmt = $link->prepare("insert into car_user(userId, carId) values (?,?)");
        $stmt->bind_param("ii", $_SESSION['userId'],$_POST['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }
    else echo "The id is invalid";
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


$carsId = [];

//get all cars id
$stmt = $link->prepare("SELECT carId FROM car");
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
foreach ($result as $row)
    $carsId[] = $row;
$stmt->close();

//get available cars id
$availableCarsId = [];
$availableCars = [];

foreach ($carsId as $id)
    if (!in_array($id, $ownedCarsId))
        $availableCarsId[] = $id;



// save available cars
foreach ($availableCarsId as $id){
    $stmt = $link->prepare("SELECT * FROM car where carId = ?");
    $stmt->bind_param("i", $id["carId"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach ($result as $row) {
        $availableCars[] = $row;
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html>
<style>
    .image{
        max-width: 5%;
    }
</style>
<h1>Insert Car</h1>
<div class="form">
    <form method="post" action="db_operations.php">
        <div>
            <label>
                ID:
                <input type="number" name="id" value="" />
            </label>
        </div>
        <button type="insert">Insert</button>
    </form>
</div>
<body>
<h1>Delete Car</h1>
<div class="form">
    <form method="post" action="db_operations.php">
        <div>
            <label>
                ID:
                <input type="number" name="id2" value="" />
            </label>
        </div>
        <button type="delete">Delete</button>
    </form>
</div>

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

<h2>Available cars</h2>
<table>
    <thead>
    <th>Id</th>
    <th>Model</th>
    <th>Maker</th>
    <th>Electric</th>
    </thead>
    <tbody>
    <?php
    foreach ($availableCars as $row) {
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

<p><a href="home.php">Back to home page</a>.</p>
</body>
</html>