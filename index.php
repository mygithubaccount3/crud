<?php
require_once "pdo.php";
session_start();

if(! isset($_SESSION['username'])) {
    header('Location: welcome.php');
    return;
}
?>
<html>
<head></head><body>
    <h1>Welcome to the Automobiles Database</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
$stmt = $pdo->query("SELECT * FROM autos");
if($stmt->fetch(PDO::FETCH_ASSOC) === false) {
 echo "No rows found";
} else {
    echo('<table border="1">'."\n");
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<tr><td>";
        echo(htmlentities($row['make']));
        echo("</td><td>");
        echo(htmlentities($row['model']));
        echo("</td><td>");
        echo(htmlentities($row['year']));
        echo("</td><td>");
        echo(htmlentities($row['mileage']));
        echo("</td><td>");
        echo('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / ');
        echo('<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a>');
        echo("</td></tr>\n");
    }
    echo "</table>";
}
?>
<a href="add.php">Add New Entry</a>
<a href="logout.php">Logout</a>
