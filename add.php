<?php
    require_once "pdo.php";

    session_start();

    if ( ! isset($_SESSION['username']) ) {
  /*$_SESSION['error'] = "Missing user_id";
  header('Location: index.php');
  return;*/
  die("ACCESS DENIED");
}

    /*$stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');*/
    $stmt = $pdo->prepare('INSERT INTO autos
        (make, year, mileage, model)
        VALUES ( :make, :year, :mileage, :model)');

    if ( isset($_POST['add'] ) ) {# It is a good practice to put the 'All fields are required' check before the other checks (like is_numeric)
        if(isset($_POST['make']) &&
            isset($_POST['year']) &&
            isset($_POST['model']) &&
            isset($_POST['mileage'])) {
            if(is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
                if(strlen($_POST['make']) > 0) {
                    /*$stmt->execute(array(
                        ':mk' => $_POST['make'],
                        ':yr' => $_POST['year'],
                        ':mi' => $_POST['mileage'])
                    );*/
                    $stmt->execute(array(
                        ':make' => $_POST['make'],
                        ':year' => $_POST['year'],
                        ':mileage' => $_POST['mileage'],
                        ':model' => $_POST['model']
                    ));
                    $_SESSION['success'] = "Record added";
                    header("Location: index.php");
                    return;
                } else {
                    $_SESSION['error'] = "Make is required";
                    header("Location: add.php");
                    return;
                }
            } else {
                # $_SESSION['error'] = "Fields must be an integer";
                $_SESSION['error'] = "All values are required";
                header("Location: add.php");
                return;
            }
        } else {
            $_SESSION['error'] = "All values are required";
            header("Location: add.php");
            return;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php
        if ( isset($_SESSION['error']) ) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']);
        }
    ?>
    <h1>Tracking autos for <?=$_SESSION['username']?></h1>
    <form method="post">
        <p>Make:
        <input type="text" name="make" size="40"></p>
        <p>Model:
        <input type="text" name="model"></p>
        <p>Year:
        <input type="number" name="year"></p>
        <p>Mileage:
        <input type="number" name="mileage" size="40"></p>
        <p><button type="submit" name="add">Add</button></p>
        <input type="submit" value="Cancel" name="cancel"/>
    </form>
</body>
</html>