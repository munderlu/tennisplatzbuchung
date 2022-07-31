<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tennisplatz Schopfloch</title>
</head>
<body>
<?php
$timestamp=time();
$heutigesDatum=date("d.m.", $timestamp);
$geteiltesHeutigesDatum=explode(".", $heutigesDatum);
//$heutigeUhrzeit=date("H:i", $timestamp);
$dbh=new PDO("mysql: host=localhost; dbname=tennisplatzbuchung", "luke", "Fallen2211");
if((empty($_REQUEST["passwort"]) || empty($_REQUEST["benutzername"])) && empty($_REQUEST["datum"]) && empty($_REQUEST["buchungsdatum"]) && empty($_REQUEST["endzeit"])){
    print '<h1>Bitte geben Sie Ihren Benutzername und Ihr Passwort ein:</h1>
        <form method="post" action="tennisplatzbuchung.php">
            Benutzername: <input typ="text" name="benutzername"><br><br>
            Passwort: <input type="password" name="passwort">
            <input type="submit" value="Weiter">
        </form>';
}
if(!empty($_REQUEST["passwort"]) && !empty($_REQUEST["benutzername"])){
    $passwort=$_REQUEST["passwort"];
    $benutzername=$_REQUEST["benutzername"];
    $sql="SELECT * FROM konten WHERE k_benutzername='$benutzername';";
    $ergebnis=$dbh->query($sql);
    if($ergebnis->rowCount()>0){
        $rueckgabewert=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
        $richtigesPasswort=$rueckgabewert[0]["k_passwort"];
    }
    else{
        $richtigesPasswort=0;
    }
    if($passwort==$richtigesPasswort && $ergebnis->rowCount()>0){
        for($i=0; $i<3; $i++){
            $timestamp=time()+60*60*24*$i;
            $datum=date("d.m.", $timestamp);
            $sql2="SELECT * FROM daten WHERE d_datum='".$datum."';";
            $ergebnis=$dbh->query($sql2);
            $daten[$i]=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
        }
        foreach($daten as $i){
            print '<table>
                        <tr>
                            <th>Datum</th>
                            <th>Uhrzeit</th>
                            <th>Platz1</th>
                            <th>Platz2</th>
                        </tr>';
            foreach($i as $j){
                if($j["d_platz1"]==0){
                    $statusPlatz1="Frei";
                    $farbe1="green";
                }
                else{
                    $statusPlatz1="Belegt von ".$j["d_bucherplatz1"]."";
                    $farbe1="red";
                }
                if($j["d_platz2"]==0){
                    $statusPlatz2="Frei";
                    $farbe2="green";
                }
                else{
                    $statusPlatz2="Belegt von ".$j["d_bucherplatz2"]."";
                    $farbe2="red";
                }
                print '<tr><td>'.$j["d_datum"].'</td>';
                print '<td>'.$j["d_uhrzeit"].'</td>';
                print '<td style="background-color:'.$farbe1.'"><form method="post" action="tennisplatzbuchung.php">
                    <input type="hidden" value="'.$j["d_datum"].'" name="buchungsdatum">
                    <input type="hidden" value="'.$j["d_uhrzeit"].'" name="uhrzeit">
                    <input type="hidden" value="'.$statusPlatz1.'" name="statusPlatz">
                    <input type="hidden" value="d_platz1" name="platz">
                    <input type="hidden" value="'.$benutzername.'" name="benutzer">
                    <input type="hidden" value="'.$passwort.'" name="passwort">
                    <input type="submit" value="'.$statusPlatz1.'"></form></td>';
                print '<td style="background-color:'.$farbe2.'"><form method="post" action="tennisplatzbuchung.php">
                    <input type="hidden" value="'.$j["d_datum"].'" name="buchungsdatum">
                    <input type="hidden" value="'.$j["d_uhrzeit"].'" name="uhrzeit">
                    <input type="hidden" value="'.$statusPlatz2.'" name="statusPlatz">
                    <input type="hidden" value="d_platz2" name="platz">
                    <input type="hidden" value="'.$benutzername.'" name="benutzer">
                    <input type="hidden" value="'.$passwort.'" name="passwort">
                    <input type="submit" value="'.$statusPlatz2.'"></form></td></tr>';
            }
            print "</table>";
        }
        //jetzt kommt die Abfrage für einen anderen Tag
        print '<h1>Möchten Sie an einem anderen Tag buchen?</h1>
            <form method="post" action="tennisplatzbuchung.php">
                <input type="date" name="datum">
                <input type="hidden" value="'.$benutzername.'" name="benutzer">
                <input type="hidden" value="'.$passwort.'" name="passwort">
                <input type="submit" value="Weiter">
            </form>';
    }
    else{
        print "<h1>Sie haben ein falsches Passwort oder einen falschen Benutzername eingegeben.</h1>
            <form method='post' action='tennisplatzbuchung.php'><input type='submit' value='Weiter'></form>";
    }
}
if(!empty($_REQUEST["datum"])){
    $datum=$_REQUEST["datum"];
    $benutzername=$_REQUEST["benutzer"];
    $passwort=$_REQUEST["passwort"];
    $geteiltesDatum=explode('-', $datum);
    $monat=$geteiltesDatum[1];
    $tag=$geteiltesDatum[2];
    $sql="SELECT * FROM daten WHERE d_datum='$tag.$monat.';";
    $rückgabe=$dbh->query($sql);
    $daten=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
    $id=$daten[0]["d_id"];
    $zeitInZweiWochen=time()+60*60*24*15;
    $datumInZweiWochen=date("d.m.", $zeitInZweiWochen);
    $sql="SELECT d_id FROM daten WHERE d_datum='$datumInZweiWochen';";
    $rückgabe=$dbh->query($sql);
    $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
    $idInZweiWochen=$ergebnis[0]["d_id"];
    if($id<$idInZweiWochen){
        print '<table>
                    <tr>
                        <th>Datum</th>
                        <th>Uhrzeit</th>
                        <th>Platz1</th>
                        <th>Platz2</th>
                    </tr>';
        foreach($daten as $i){
            if($i["d_platz1"]==0){
                $statusPlatz1="Frei";
                $farbe1="green";
            }
            else{
                $statusPlatz1="Belegt von ".$i["d_bucherplatz1"]."";
                $farbe1="red";
            }
            if($i["d_platz2"]==0){
                $statusPlatz2="Frei";
                $farbe2="green";
            }
            else{
                $statusPlatz2="Belegt von ".$i["d_bucherplatz2"]."";
                $farbe2="red";
            }
            print '<tr><td>'.$i["d_datum"].'</td>';
            print '<td>'.$i["d_uhrzeit"].'</td>';
            print '<td style="background-color:'.$farbe1.'"><form method="post" action="tennisplatzbuchung.php">
                <input type="hidden" value="'.$i["d_datum"].'" name="buchungsdatum">
                <input type="hidden" value="'.$i["d_uhrzeit"].'" name="uhrzeit">
                <input type="hidden" value="'.$statusPlatz1.'" name="statusPlatz">
                <input type="hidden" value="d_platz1" name="platz">
                <input type="hidden" value="'.$benutzername.'" name="benutzer">
                <input type="hidden" value="'.$passwort.'" name="passwort">
                <input type="submit" value="'.$statusPlatz1.'"></form></td>';
            print '<td style="background-color:'.$farbe2.'"><form method="post" action="tennisplatzbuchung.php">
                <input type="hidden" value="'.$i["d_datum"].'" name="buchungsdatum">
                <input type="hidden" value="'.$i["d_uhrzeit"].'" name="uhrzeit">
                <input type="hidden" value="'.$statusPlatz2.'" name="statusPlatz">
                <input type="hidden" value="d_platz2" name="platz">
                <input type="hidden" value="'.$benutzername.'" name="benutzer">
                <input type="hidden" value="'.$passwort.'" name="passwort">
                <input type="submit" value="'.$statusPlatz2.'"></form></td></tr>';
        }
        print "</table>";
        print '<h1>Wann möchten Sie am '.$tag.'.'.$monat.'. buchen?</h1>
            <form method="post" action="tennisplatzbuchung.php">
            <input type="hidden" value="'.$benutzername.'" name="benutzername">
            <input type="hidden" value="'.$passwort.'" name="passwort">
            <input type="submit" value="An einem anderen Tag buchen"></form>';
    }
    else{
        print '<h1>Die Platzbuchung ist nur für maximal zwei Wochen im Vorraus möglich.</h1>';
        print '<form method="post" action="tennisplatzbuchung.php">
            <input type="hidden" value="'.$benutzername.'" name="benutzername">
            <input type="hidden" value="'.$passwort.'" name="passwort">
            <input type="submit" value="An einem anderen Tag buchen"></form>';
    }
}
if(!empty($_REQUEST["statusPlatz"])){
    $buchungsdatum=$_REQUEST["buchungsdatum"];
    $uhrzeit=$_REQUEST["uhrzeit"];
    $statusPlatz=$_REQUEST["statusPlatz"];
    $platz=$_REQUEST["platz"];
    $benutzername=$_REQUEST["benutzer"];
    $passwort=$_REQUEST["passwort"];
    if($statusPlatz=="Frei"){
        print '<h1>Bis wann möchten Sie am '.$buchungsdatum.' um '.$uhrzeit.' Uhr den Platz '.$platz[-1].' buchen?</h1>';
        $sql="SELECT d_id FROM daten WHERE d_datum='$buchungsdatum' AND d_uhrzeit='$uhrzeit';";
        $rückgabe=$dbh->query($sql);
        $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
        $id=$ergebnis[0]["d_id"];
        for($i=1; $i<=4; $i++){
            if($uhrzeit=="22:30"){
                print '<form method="post" action="tennisplatzbuchung.php">
                    <input type="hidden" name="buchungsdatum" value="'.$buchungsdatum.'">
                    <input type="hidden" name="anfangszeit" value="'.$uhrzeit.'">
                    <input type="hidden" name="platz" value="'.$platz.'">
                    <input type="hidden" name="endzeit" value="23:00">
                    <input type="hidden" value="'.$benutzername.'" name="benutzer">
                    <input type="hidden" value="'.$passwort.'" name="passwort">
                    <input type="submit" value="23:00"></form><br>';
                break;
            }
            $sql="SELECT * FROM daten WHERE d_id=$id+$i;";
            $rückgabe=$dbh->query($sql);
            $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
            print '<form method="post" action="tennisplatzbuchung.php">
                <input type="hidden" name="buchungsdatum" value="'.$buchungsdatum.'">
                <input type="hidden" name="anfangszeit" value="'.$uhrzeit.'">
                <input type="hidden" name="platz" value="'.$platz.'">
                <input type="hidden" name="endzeit" value="'.$ergebnis[0]["d_uhrzeit"].'">
                <input type="hidden" value="'.$benutzername.'" name="benutzer">
                <input type="hidden" value="'.$passwort.'" name="passwort">
                <input type="submit" value="'.$ergebnis[0]["d_uhrzeit"].'"></form><br>';
            if($ergebnis[0][$platz]!=0 || $ergebnis[0]["d_uhrzeit"]=="22:30"){
                break;
            }
        }
        print '<form method="post" action="tennisplatzbuchung.php">
            <input type="hidden" value="'.$benutzername.'" name="benutzername">
            <input type="hidden" value="'.$passwort.'" name="passwort">
            <input type="submit" value="Zurück"></form>';
    }
    else{
        print '<h1>Dieser Platz ist zu der Uhrzeit leider belegt.</h1><br>
            <form method="post" action="tennisplatzbuchung.php">
            <input type="hidden" value="'.$benutzername.'" name="benutzername">
            <input type="hidden" value="'.$passwort.'" name="passwort">
            <input type="submit" value="Zurück"></form>';
    }
}
if(!empty($_REQUEST["entgültigesbuchungsdatum"])){
    $buchungsdatum=$_REQUEST["entgültigesbuchungsdatum"];
    $anfangszeit=$_REQUEST["anfangszeit"];
    $endzeit=$_REQUEST["endzeit"];
    $platz=$_REQUEST["platz"];
    $benutzername=$_REQUEST["benutzer"];
    $passwort=$_REQUEST["passwort"];
    $sql="SELECT d_id FROM daten WHERE d_datum='$buchungsdatum' AND d_uhrzeit='$anfangszeit';";
    $rückgabe=$dbh->query($sql);
    $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
    $id=$ergebnis[0]["d_id"];
    $i=0;
    $uhrzeit=0;
    while($uhrzeit!=$endzeit){
        $sql="UPDATE daten SET $platz=1, d_bucherplatz".$platz[-1]."='$benutzername' WHERE d_id=$id+$i;";
        $sql2="SELECT d_uhrzeit FROM daten WHERE d_id=$id+$i+1;";
        $dbh->query($sql);
        $rückgabe=$dbh->query($sql2);
        $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
        $uhrzeit=$ergebnis[0]["d_uhrzeit"];
        if($uhrzeit=="22:30"){
            break;
        }
        $i++;
    }
    print "<h1>Ihr Plazt wurde erfolgreich gebucht!</h1>";
    print '<form method="post" action="tennisplatzbuchung.php">
        <input type="hidden" value="'.$benutzername.'" name="benutzername">
        <input type="hidden" value="'.$passwort.'" name="passwort">
        <input type="submit" value="Weiter"></form>';
}
elseif(!empty($_REQUEST["endzeit"])){
    $buchungsdatum=$_REQUEST["buchungsdatum"];
    $anfangszeit=$_REQUEST["anfangszeit"];
    $endzeit=$_REQUEST["endzeit"];
    $platz=$_REQUEST["platz"];
    $benutzername=$_REQUEST["benutzer"];
    $passwort=$_REQUEST["passwort"];
    print '<h1>Möchten Sie am '.$buchungsdatum.' von '.$anfangszeit.' Uhr bis '.$endzeit.' Uhr den Platz '.$platz[-1].' buchen?</h1>
        <form method="post" action="tennisplatzbuchung.php">
        <input type="hidden" name="endzeit" value="'.$endzeit.'">
        <input type="hidden" name="entgültigesbuchungsdatum" value="'.$buchungsdatum.'">
        <input type="hidden" name="anfangszeit" value="'.$anfangszeit.'">
        <input type="hidden" name="platz" value="'.$platz.'">
        <input type="hidden" value="'.$benutzername.'" name="benutzer">
        <input type="hidden" value="'.$passwort.'" name="passwort">
        <input type="submit" value="Ja"></form><br>';
    print '<form method="post" action="tennisplatzbuchung.php">
        <input type="hidden" value="'.$benutzername.'" name="benutzername">
        <input type="hidden" value="'.$passwort.'" name="passwort">
        <input type="submit" value="Nein, zurück!"></form>';
}
$dbh=null;
?>
<img src="Logo.png" height="250">
</body>
</html>