<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Logo.png">
    <link rel="manifest" href="/manifest.webmanifest">
    <title>Tennisplatz Schopfloch Admin</title>
</head>
<body>
<h1 id="überschrift">Admin Tennisplatzbuchung</h1>
<h1>Benutzer erstellen</h1>
<?php
$timestamp=time();
$heutigesDatum=date("d.m.", $timestamp);
$geteiltesHeutigesDatum=explode(".", $heutigesDatum);
$heutigeUhrzeit=date("H:i", $timestamp);
$dbh=new PDO("mysql: host=localhost; dbname=tennisplatzbuchung; charset=utf8", "root", "");
if((empty($_REQUEST["passwort"]) || empty($_REQUEST["benutzername"]))){
    print '<h1>Bitte geben Sie Ihren Benutzername und Ihr Passwort ein:</h1>
        <form method="post" action="benutzer.php">
            Benutzername: <input typ="text" name="benutzername"><br><br>
            Passwort: <input type="password" name="passwort">
            <input type="submit" value="Weiter"></form><br>
            <h2><a href="admin.php">Plätze buchen</a><br><br>
<a href="benutzer.php">Benutzer erstellen</a><br><br><a href="freimachen.php">Plätze freigeben</a></h2>
        <img src="Logo.png" height="250">';
}
if(!empty($_REQUEST["endgültig"])){
}
elseif(!empty($_REQUEST["passwort"]) && !empty($_REQUEST["benutzername"]) && !empty($_REQUEST["neuerbenutzername"]) && !empty($_REQUEST["neuespasswort"]) && !empty($_REQUEST["name"])){
    $passwort=$_REQUEST["passwort"];
    $benutzername=$_REQUEST["benutzername"];
    $neuerbenutzername=$_REQUEST["neuerbenutzername"];
    $neuespasswort=$_REQUEST["neuespasswort"];
    $name=$_REQUEST["name"];
    $sql="INSERT INTO `konten`(`k_benutzername`, `k_passwort`, `k_name`) VALUES ('$neuerbenutzername','$neuespasswort','$name'); ";
    $dbh->query($sql);
    print '<h1>Der neue Benutzer wurde erfolgreich hinzugefügt</h1>
    <form method="post" action="benutzer.php"><input type="hidden" name="benutzername" value="'.$benutzername.'">
    <input type="hidden" name="passwort" value="'.$passwort.'"><input type="submit" value="Weiter"></form><img src="Logo.png" height="250">';
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
                Ganzer Name: <input type="text" name="name"><br>
                <input type="hidden" name="benutzername" value="'.$benutzername.'">
                <input type="hidden" name="passwort" value="'.$passwort.'"><br>
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