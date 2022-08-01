<?php
if(!empty($_REQUEST["status"])){
    $dbh=new PDO("mysql: host=localhost; dbname=tennisplatzbuchung", "root", "");
    $monat="12";
    $tageImMonat="31";
    for($j=1; $j<=9; $j++){
        for($k=7; $k<=9; $k++){
            $sql="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('0$j.$monat.', '0$k:00', 0, 0);";
            $sql2="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('0$j.$monat.', '0$k:30', 0, 0);";
            $dbh->query($sql);
            $dbh->query($sql2);
        }
        for($k=10; $k<=22; $k++){
            $sql="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('0$j.$monat.', '$k:00', 0, 0);";
            $sql2="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('0$j.$monat.', '$k:30', 0, 0);";
            $dbh->query($sql);
            $dbh->query($sql2);
        }
    }
    for($j=10; $j<=$tageImMonat; $j++){
        for($k=7; $k<=9; $k++){
            $sql="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('$j.$monat.', '0$k:00', 0, 0);";
            $sql2="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('$j.$monat.', '0$k:30', 0, 0);";
            $dbh->query($sql);
            $dbh->query($sql2);
        }
        for($k=10; $k<=22; $k++){
            $sql="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('$j.$monat.', '$k:00', 0, 0);";
            $sql2="INSERT INTO daten (d_datum, d_uhrzeit, d_platz1, d_platz2) VALUES ('$j.$monat.', '$k:30', 0, 0);";
            $dbh->query($sql);
            $dbh->query($sql2);
        }
    }
    $dbh=null;
}
else{
    print "<form action='tabellen_macher.php' method='post'><input type='hidden' name='status' value='los'><input type='submit' value='Los!'></form>";
}
?>