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
        </form><img src="Logo.png" height="250">';
}
if(!empty($_REQUEST["endgültig"])){
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
            print '<h1>Bitte geben Sie die Daten des neuen Benutzers ein:</h1>
            <form method="post" action="benutzer.php">
                Benutzername: <input type="text" name="neuerbenutzername"><br><br>
                Passwort: <input type="text" name="neuespasswort"><br><br>
                Ganzer Name: <input type="text" name="name"><br><br>
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