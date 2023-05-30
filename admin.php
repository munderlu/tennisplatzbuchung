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
$heutigeUhrzeit=date("H:i", $timestamp);
$dbh=new PDO("mysql: host=localhost; dbname=tennisplatzbuchung; charset=utf8", "root", "");
if((empty($_REQUEST["passwort"]) || empty($_REQUEST["benutzername"]))){
    print '<h1>Bitte geben Sie Ihren Benutzername und Ihr Passwort ein:</h1>
        <form method="post" action="admin.php">
            Benutzername: <input typ="text" name="benutzername"><br><br>
            Passwort: <input type="password" name="passwort">
            <input type="submit" value="Weiter">
        </form><br>
        <h2><a href="admin.php">Plätze buchen</a><br><br>
<a href="benutzer.php">Benutzer erstellen</a></h2><img src="Logo.png" height="250">';
}
if(!empty($_REQUEST["endgültig"])){
    $passwort=$_REQUEST["passwort"];
    $benutzername=$_REQUEST["benutzername"];
    $anfangszeit=$_REQUEST["anfangsuhrzeit"];
    $endzeit=$_REQUEST["enduhrzeit"];

    $datum=$_REQUEST["datum"];
    $geteiltesDatum=explode('-', $datum);
    $monat=$geteiltesDatum[1];
    $tag=$geteiltesDatum[2];
    $datum=$tag.".".$monat.".";

    $platznummer=$_REQUEST["platz"];
    $verwendungszweck=$_REQUEST["verwendungszweck"];
    $wiederholung=$_REQUEST["wiederholung"];
    $sql="SELECT d_id FROM daten WHERE d_datum='$datum' AND d_uhrzeit='$anfangszeit';";
    $rückgabe=$dbh->query($sql);
    $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
    $id=$ergebnis[0]["d_id"];
    $i=0;
    $uhrzeit=0;
    if($platznummer=="1"){
        $platz="d_platz1";
    }
    if($platznummer=="2"){
        $platz="d_platz2";
    }
    if($platznummer=="1 und 2"){
        $platz="beide";
    }
    if($wiederholung=="Ja"){
        $anfangsid=$id;
        while($anfangsid<11680){
            if($platz=="beide"){
                while($uhrzeit!=$endzeit){
                    $sql="UPDATE daten SET d_platz1=1, d_bucherplatz1='$verwendungszweck' WHERE d_id=$anfangsid+$i;";
                    $dbh->query($sql);
                    $sql2="SELECT d_uhrzeit FROM daten WHERE d_id=$anfangsid+$i+1;";
                    $dbh->query($sql);
                    $rückgabe=$dbh->query($sql2);
                    $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
                    $uhrzeit=$ergebnis[0]["d_uhrzeit"];
                    if($anfangszeit=="22:30"){
                        break;
                    }
                    $i++;
                }
                $uhrzeit=0;
                $i=0;
                while($uhrzeit!=$endzeit){
                    $sql="UPDATE daten SET d_platz2=1, d_bucherplatz2='$verwendungszweck' WHERE d_id=$anfangsid+$i;";
                    $dbh->query($sql);
                    $sql2="SELECT d_uhrzeit FROM daten WHERE d_id=$anfangsid+$i+1;";
                    $dbh->query($sql);
                    $rückgabe=$dbh->query($sql2);
                    $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
                    $uhrzeit=$ergebnis[0]["d_uhrzeit"];
                    if($anfangszeit=="22:30"){
                        break;
                    }
                    $i++;
                }
            }else{
                while($uhrzeit!=$endzeit){
                    $sql="UPDATE daten SET $platz=1, d_bucherplatz".$platz[-1]."='$verwendungszweck' WHERE d_id=$anfangsid+$i;";
                    $dbh->query($sql);
                    $sql2="SELECT d_uhrzeit FROM daten WHERE d_id=$anfangsid+$i+1;";
                    $dbh->query($sql);
                    $rückgabe=$dbh->query($sql2);
                    $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
                    $uhrzeit=$ergebnis[0]["d_uhrzeit"];
                    if($anfangszeit=="22:30"){
                        break;
                    }
                    $i++;
                }
            }
            $anfangsid+=224;
            $uhrzeit=0;
            $i=0;
        }
    }else{
        if($platz=="beide"){
            while($uhrzeit!=$endzeit){
                $sql="UPDATE daten SET d_platz1=1, d_bucherplatz1='$verwendungszweck' WHERE d_id=$id+$i;";
                $dbh->query($sql);
                $sql2="SELECT d_uhrzeit FROM daten WHERE d_id=$id+$i+1;";
                $dbh->query($sql);
                $rückgabe=$dbh->query($sql2);
                $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
                $uhrzeit=$ergebnis[0]["d_uhrzeit"];
                if($anfangszeit=="22:30"){
                    break;
                }
                $i++;
            }
            $uhrzeit=0;
            $i=0;
            while($uhrzeit!=$endzeit){
                $sql="UPDATE daten SET d_platz2=1, d_bucherplatz2='$verwendungszweck' WHERE d_id=$id+$i;";
                $dbh->query($sql);
                $sql2="SELECT d_uhrzeit FROM daten WHERE d_id=$id+$i+1;";
                $dbh->query($sql);
                $rückgabe=$dbh->query($sql2);
                $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
                $uhrzeit=$ergebnis[0]["d_uhrzeit"];
                if($anfangszeit=="22:30"){
                    break;
                }
                $i++;
            }
        }else{
            while($uhrzeit!=$endzeit){
                $sql="UPDATE daten SET $platz=1, d_bucherplatz".$platz[-1]."='$verwendungszweck' WHERE d_id=$id+$i;";
                $dbh->query($sql);
                $sql2="SELECT d_uhrzeit FROM daten WHERE d_id=$id+$i+1;";
                $dbh->query($sql);
                $rückgabe=$dbh->query($sql2);
                $ergebnis=$rückgabe->fetchAll(PDO::FETCH_ASSOC);
                $uhrzeit=$ergebnis[0]["d_uhrzeit"];
                if($anfangszeit=="22:30"){
                    break;
                }
                $i++;
            }
        }
    }
    print "<h1>Ihr Platz wurde erfolgreich gebucht!</h1>";
    print '<form method="post" action="admin.php">
        <input type="hidden" value="'.$benutzername.'" name="benutzername">
        <input type="hidden" value="'.$passwort.'" name="passwort">
        <input type="submit" value="Weiter"></form><img src="Logo.png" height="250">';
}
elseif(!empty($_REQUEST["passwort"]) && !empty($_REQUEST["benutzername"]) && !empty($_REQUEST["datum"]) && !empty($_REQUEST["anfangsuhrzeit"]) && !empty($_REQUEST["enduhrzeit"]) && !empty($_REQUEST["platz"]) && !empty($_REQUEST["verwendungszweck"])){
    $passwort=$_REQUEST["passwort"];
    $benutzername=$_REQUEST["benutzername"];
    $anfangsuhrzeit=$_REQUEST["anfangsuhrzeit"];
    $enduhrzeit=$_REQUEST["enduhrzeit"];
    $datum=$_REQUEST["datum"];
    $platz=$_REQUEST["platz"];
    $verwendungszweck=$_REQUEST["verwendungszweck"];
    $wiederholung=$_REQUEST["wiederholung"];
    $woechentlich="wöchentlich";
    if($wiederholung=="Nein"){
        $woechentlich="";
    }
    print "<h1>Möchten Sie am $datum von $anfangsuhrzeit bis $enduhrzeit $woechentlich Platz $platz buchen?</h1>";
    print '<form method="post" action="admin.php">
    <input type="hidden" name="datum" value="'.$datum.'">
    <input type="hidden" name="wiederholung" value="'.$wiederholung.'">
    <input type="hidden" name="anfangsuhrzeit" value="'.$anfangsuhrzeit.'">
    <input type="hidden" name="enduhrzeit" value="'.$enduhrzeit.'">
    <input type="hidden" name="platz" value="'.$platz.'">
    <input type="hidden" name="benutzername" value="'.$benutzername.'">
    <input type="hidden" name="passwort" value="'.$passwort.'">
    <input type="hidden" name="verwendungszweck" value="'.$verwendungszweck.'">
    <input type="hidden" name="endgültig" value="Ja">
    <input type="submit" value="Ja">
    </form><br>
    <form method="post" action="admin.php">
    <imput type="hidden" name="benutzername" value="'.$benutzername.'">
    <imput type="hidden" name="passwort" value="'.$passwort.'">
    <input type="submit" value="Zurück zum Start">
    </form><br>
    <img src="Logo.png" height="250">';
}
elseif(!empty($_REQUEST["passwort"]) && !empty($_REQUEST["benutzername"])){
    $passwort=$_REQUEST["passwort"];
    $benutzername=$_REQUEST["benutzername"];
    $richtigeEingabe=False;
    $sql="SELECT * FROM konten;";
    $ergebnis=$dbh->query($sql);
    $rueckgabewert=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
    foreach($rueckgabewert as $wert){
        if($wert["k_benutzername"]=="admin" && $wert["k_passwort"]==$passwort){
            $richtigeEingabe=True;
            print '<h1>Bitte geben Sie Ihre Daten ein:</h1>
            <form method="post" action="admin.php">
                Datum: <input type="date" name="datum"><br><br>
                Verwendungszweck: <input type="text" name="verwendungszweck"><br><br>
                Wöchentliche Wiederholung?: <select name="wiederholung">
                    <option value="Nein" selected>Nein</option>
                    <option value="Ja">Ja</option>
                    </select><br><br>
                Anfangsuhrzeit: <select name="anfangsuhrzeit">
                    <option value="07:00">07:00</option>
                    <option value="07:30">07:30</option>
                    <option value="08:00">08:00</option>
                    <option value="08:30">08:30</option>
                    <option value="09:00">09:00</option>
                    <option value="09:30">09:30</option>
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                    <option value="16:30">16:30</option>
                    <option value="17:00">17:00</option>
                    <option value="17:30">17:30</option>
                    <option value="18:00">18:00</option>
                    <option value="18:30">18:30</option>
                    <option value="19:00">19:00</option>
                    <option value="19:30">19:30</option>
                    <option value="20:00">20:00</option>
                    <option value="20:30">20:30</option>
                    <option value="21:00">21:00</option>
                    <option value="21:30">21:30</option>
                    <option value="22:00">22:00</option>
                    </select><br><br>
                Enduhrzeit: <select name="enduhrzeit">
                    <option value="07:30">07:30</option>
                    <option value="08:00">08:00</option>
                    <option value="08:30">08:30</option>
                    <option value="09:00">09:00</option>
                    <option value="09:30">09:30</option>
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                    <option value="16:30">16:30</option>
                    <option value="17:00">17:00</option>
                    <option value="17:30">17:30</option>
                    <option value="18:00">18:00</option>
                    <option value="18:30">18:30</option>
                    <option value="19:00">19:00</option>
                    <option value="19:30">19:30</option>
                    <option value="20:00">20:00</option>
                    <option value="20:30">20:30</option>
                    <option value="21:00">21:00</option>
                    <option value="21:30">21:30</option>
                    <option value="22:00">22:00</option>
                    <option value="22:30">22:30</option></select><br><br>
                Plätze: <select name="platz">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="1 und 2">beide</option>
                    </select>
                <input type="hidden" name="benutzername" value="'.$benutzername.'">
                <input type="hidden" name="passwort" value="'.$passwort.'"><br><br>
            <input type="submit" value="Weiter">
            </form><br>
            <form action="admin.php" method="post">
                <input type="submit" value="Abmelden"></form><img src="Logo.png" height="250">';
        }
    }
    if(!$richtigeEingabe){
        print "<h1>Sie haben ein falsches Passwort oder einen falschen Benutzername eingegeben.</h1>
            <form method='post' action='admin.php'><input type='submit' value='Weiter'></form>
            <img src='Logo.png' height='250'>";
    }
}
$dbh=null;
?>
</body>
</html>