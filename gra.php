<?php
session_start();
if(isset($_SESSION['username'])){
    include ("test.php");
}
else{
    echo "Nie obejdziesz tego :)))<br>";
    echo "<a href='index.php'>Powrót</a><br>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wisielec :)</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Muli'>
    <link rel="stylesheet" href="./style.css">
    <script type="text/javascript"></script>
</head>
<body>

</body>

</html>