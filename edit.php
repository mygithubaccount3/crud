<?php
require_once "pdo.php";
session_start();

if(isset($_GET)) {
    $_SESSION['auto_id'] = $_GET['auto_id'];
}

if(isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year']) && isset($_POST['mileage']) ) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1
        || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?auto_id=".$_SESSION['auto_id']);
        return;
    }

    /*if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: edit.php?user_id=".$_POST['user_id']);
        return;
    }*/

    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage
            WHERE auto_id = :auto_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':auto_id' => $_SESSION['auto_id']));
    $_SESSION['success'] = 'Record edited';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_SESSION['username']) ) {
  /*$_SESSION['error'] = "Missing user_id";
  header('Location: index.php');
  return;*/
  die("ACCESS DENIED");
}

$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_SESSION['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
# $user_id = $row['user_id'];
?>
<p>Edit Car</p>
<form method="post">
        <p>Make:
        <input type="text" name="make" value="<?= $make ?>" size="40"></p>
        <p>Model:
        <input type="text" name="model" value="<?= $model ?>"></p>
        <p>Year:
        <input type="number" name="year" value="<?= $year ?>"></p>
        <p>Mileage:
        <input type="number" name="mileage" value="<?= $mileage ?>" size="40"></p>
        <p><button type="submit" name="add">Save</button></p>
        <input type="submit" value="Cancel" name="cancel"/>
</form>
