<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Logo.png">
    <link rel="manifest" href="/manifest.webmanifest">
    <title>Tennisplatz Schopfloch</title>
</head>
<body>
    <h1 id="überschrift">Tennisplatzbuchung</h1>
    <?php
        $timestamp=time();
        $heutigesDatum=date("d.m.", $timestamp);
        $geteiltesHeutigesDatum=explode(".", $heutigesDatum);
        $dbh=new PDO("mysql: host=localhost; dbname=tennisplatzbuchung", "root", "");
        $sql="SELECT * FROM daten WHERE d_datum='".$heutigesDatum."';";
        $ergebnis=$dbh->query($sql);
        $daten=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
        print '<table>
                    <tr><th colspan="3">'.$heutigesDatum.'</th></tr>
                    <tr>
                        <th>Uhrzeit</th>
                        <th>Platz1</th>
                        <th>Platz2</th>
                    </tr>';
        foreach($daten as $j){
            if($j["d_platz1"]==0){
                $statusPlatz1="Frei";
                $farbe1="green";
            }
            else{
                $statusPlatz1=$j["d_bucherplatz1"]."";
                $farbe1="red";
            }
            if($j["d_platz2"]==0){
                $statusPlatz2="Frei";
                $farbe2="green";
            }
            else{
                $statusPlatz2=$j["d_bucherplatz2"]."";
                $farbe2="red";
            }
            print '<tr>';
            print '<td style="background-color: orange">'.$j["d_uhrzeit"].'</td>';
            print '<td style="background-color:'.$farbe1.'">'.$statusPlatz1.'</td>';
            print '<td style="background-color:'.$farbe2.'">'.$statusPlatz2.'</td></tr>';
        }
        print "</table>";
        $dbh=null;
    ?>
    <h1><a href="index.php">Zurück zur Startseite</a></h1>
    <img src="Logo.png" height="250">
</body>
</html>