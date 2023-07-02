<?php
if($_SESSION['poziom']!="Łatwy" && $_SESSION['poziom']!="Średni" && $_SESSION['poziom']!="Trudny"){
    mysqli_report(MYSQLI_REPORT_OFF);
    $dbhost = '';
    $dbuser = '';
    $dbpass = '';
    $dbname = '';
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if($mysqli->connect_errno ) {
        printf("Błąd: %s<br />", $mysqli->connect_error);
        exit();
    }
    $los=rand(1, 908);
    $slowo=$mysqli->query("SELECT slowko FROM Gierka WHERE id=$los;");
    $slowo = $slowo->fetch_assoc();
    $slowo = $slowo['slowko'];
    $slowo= str_split($slowo);
    $slowo2=array();
    for($x=0;$x<count($slowo);$x++){
        $slowo2[$x]="- ";
    }
    switch ($_SESSION['poziom']){
        case "option1":
            $_SESSION['proby']=10;
            $_SESSION['poziom']="Łatwy";
            break;
        case "option2":
            $_SESSION['proby']=7;
            $_SESSION['poziom']="Średni";
            break;
        case "option3":
            $_SESSION['proby']=5;
            $_SESSION['poziom']="Trudny";
            break;
        default:
            $_SESSION['proby']=10;
            $_SESSION['poziom']="Łatwy";
            break;
    }
    $_SESSION['slowo']=$slowo;
    $_SESSION['slowo2']=$slowo2;
    $_SESSION['$pozostale']=count($slowo2);
    $_SESSION['czas_pocz']=microtime(true);
    $mysqli->close();
    $pozostaleznaki= array("a"=>"a","b"=>"b","c"=>"c","d"=>"d","e"=>"e","f"=>"f","g"=>"g","h"=>"h",
        "i"=>"i","j"=>"j","k"=>"k","l"=>"l","m"=>"m","n"=>"n",
        "o"=>"o","p"=>"p","q"=>"q","r"=>"r","s"=>"s","t"=>"t","u"=>"u","v"=>"v","w"=>"w","x"=>"x","y"=>"y","z"=>"z");
    $_SESSION['pozostaleznaki']=$pozostaleznaki;
}
$zgadniete=true;
if(isset($_POST['przycisk'])){
    $zgadniete=false;
    foreach ($_SESSION['slowo'] as $klucz => $znak){
        if($_POST['przycisk']==$znak){
            $_SESSION['slowo2'][$klucz]=$znak;
            unset($_SESSION['pozostaleznaki'][$_POST['przycisk']]);
            $_SESSION['$pozostale']--;
            $zgadniete=true;
        }
        else{
            unset($_SESSION['pozostaleznaki'][$_POST['przycisk']]);
        }
    }
}
if(!$zgadniete){
    $_SESSION['proby']--;
}
echo "<div class='pt-5'>
    <div class='container'>
        <div class='row'>
            <div class='mx-auto' style='width: 51%'>
            <h1 class='text-center'>Witaj ".$_SESSION['username']." Miłej gry!</h1><br>
                <div class='card card-body mb-5'>";
echo "<h3 class='text-center mt-3'>Wybrany poziom trudności: ".$_SESSION['poziom']."</h3>";
echo "<img class='border border-danger border border-5 rounded-circle mt-4 mx-auto d-block' style='width: 300px; height:300px' src='".$_SESSION['proby'].".png'><br>";
echo "<h3 class='mt-2 mb-4 text-center'>Ilość pozostałych prób: ".$_SESSION['proby']."</h3>";
if($_SESSION['$pozostale']==0){
        $czas2=microtime(true);
        $czas3=$czas2-$_SESSION['czas_pocz'];
        $czas3=gmdate("H:i:s", $czas3);
        echo "<h3 class='text-center mb-4'>Brawo! Wygrałeś rozgrywkę!</h3>";
        echo "<h3 class='text-center mb-4'>Ukończyłeś rozgrywkę w: $czas3</h3>";
        $dbhost = '';
        $dbuser = '';
        $dbpass = '';
        $dbname = '';
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if($mysqli->connect_errno ) {
            printf("Błąd: %s<br />", $mysqli->connect_error);
            exit();
        }
        $check=$mysqli->query("SELECT * FROM Ranking WHERE Nazwa LIKE '".$_SESSION['username']."';");
        $row = $check->fetch_assoc();
        if($_SESSION['poziom']!="Łatwy" || $_SESSION['poziom']!="Średni" || $_SESSION['poziom']!="Trudny"){
            $_SESSION['poziom']="Łatwy";
        }
        if ($check->num_rows > 0 && strtotime($row['Czas'])>strtotime($czas3)) {
            $mysqli->query("UPDATE Ranking SET Czas = '".$czas3."', Poziom = '".$_SESSION['poziom']."' WHERE Nazwa='".$_SESSION['username']."';");
        } else {
            $mysqli->query("INSERT INTO Ranking VALUES ('".$_SESSION['username']."','".$czas3."', '".$_SESSION['poziom']."' );");
        }
        $mysqli->close();
        session_unset();
        echo "<a class='text-center btn' href='index.php'>Powrót do Strony Głównej</a>";
}
elseif($_SESSION['proby']==0){
    $czas2=microtime(true);
    $czas3=$czas2-$_SESSION['czas_pocz'];
    $czas3=gmdate("H:i:s", $czas3);
    echo "<h3 class='text-center mb-4'>Niestety, przegrałeś rozgrywkę!</h3>";
    echo "<h3 class='text-center mb-4'>Twój czas: $czas3</h3>";
    session_unset();
    echo "<a class='text-center btn' href='index.php'>Powrót do Strony Głównej</a>";
}
else{
    echo "<form method='POST' class='text-center'>";
    foreach ($_SESSION['slowo2'] as $znak){
        echo "$znak";
    }
    echo "<br>";
    foreach ($_SESSION['pozostaleznaki'] as $znak){
        echo "<input class='btn m-1' type='submit' name='przycisk' value='$znak'>";
    }
    echo "</form>";
}

echo "</div>
            </div>
        </div>
    </div>
</div>";
?>