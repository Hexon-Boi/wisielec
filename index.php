<?php
session_unset();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Wisielec :)</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Muli'>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="pt-5">
        <div class="container">
            <div class="row">
                <div class="mx-auto">
                    <h1 class="text-center pb-5">Wisielec :)</h1>
                    <div class="card card-body">
                        <form id="submitForm" method="post"><input
                                type="hidden" name="_csrf">
                            <div class="form-group required">
                                <label for="username">Nazwa użytkownika</label>
                                <input type="text" class="form-control" id="username" maxlength="20" required="" name="username"
                                     value="">
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                                    value="option1" checked>
                                <label class="form-check-label" for="inlineRadio1">Łatwy</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                                    value="option2">
                                <label class="form-check-label" for="inlineRadio2">Średni</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3"
                                    value="option3">
                                <label class="form-check-label" for="inlineRadio3">Trudny</label>
                            </div>

                            <div class="form-group pt-3 mb-1">
                                <input class="btn btn-block" type="submit" name="graj" value="Graj">
                                <?php if(isset($_POST['graj'])){if(!preg_match('/^[A-Za-z]+[A-Za-z0-9]*$/', $_POST['username'])){
                                    echo "Zły nick! Usuń spację, znaki specjalne i nie używaj samych cyfr!<br>";
                                }
                                else{
                                    $_SESSION['username']=$_POST['username'];
                                    $_SESSION['poziom']=$_POST['inlineRadioOptions'];
                                    header("Location: gra.php");
                                }}?>
                            </div>
                        </form>

                    </div>

                    <h1 class="text-center mt-5 pb-1">Ranking</h1>
                    <h5 class="text-center mt-1 pb-1">Top 10</h5>
                    <div class="card card-body mt-5 mb-5">
                        <?php
                        mysqli_report(MYSQLI_REPORT_OFF);
                        $dbhost = '';
                        $dbuser = '';
                        $dbpass = '';
                        $dbname = '';
                        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
                        if($mysqli->connect_errno ) {
                            printf("Nie udało sie wczytać rankingu: %s<br>", $mysqli->connect_error);
                            exit();
                        }
                        $wynik=$mysqli->query("SELECT * FROM Ranking ORDER BY Czas ASC;");
                        echo "<table><tr><th>Miejsce</th><th>Nazwa</th><th>Czas</th><th>Poziom</th></tr>";
                        if ($wynik->num_rows > 0) {
                            $x=1;
                            while($row = $wynik->fetch_assoc()) {
                                if($x==11){
                                    break;
                                }
                                echo "<tr><td>".$x.". </td><td>".$row['Nazwa']."</td><td>".$row['Czas']."</td><td>".$row['Poziom']."</td></tr>";
                                $x++;
                            }
                        echo "</table>";
                        }
                        else {
                            echo "<tr><td>Brak rekordów.</td></tr>";
                        }
                        $mysqli->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>