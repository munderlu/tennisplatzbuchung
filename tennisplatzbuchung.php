<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tennisplatz Schopfloch</title>
</head>
<body>
    
</body>
</html>


<?php
if(empty($_REQUEST["passwort"]) && empty($_REQUEST["datum"])){
    print '<h1>Bitte geben Sie das Passwort ein:</h1>
        <form method="post" action="tennisplatzbuchung.php">
            <input type="password" name="passwort">
            <input type="submit" value="Weiter">
        </form>';
}
if(!empty($_REQUEST["datum"])){
    $datum=$_REQUEST["datum"];
    print "TEST";
}
if(!empty($_REQUEST["passwort"])){
    try{
        $dbh=new PDO("mysql:host=localhost; dbname=tennisplatzbuchung", "luke", "Fallen2211");
        $sql="SELECT * FROM inhalt WHERE i_name='passwort';";
        $ergebnis=$dbh->query($sql);
        $rueckgabewert=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
        if($_REQUEST["passwort"]==$rueckgabewert[0]["i_inhalt"]){
            $sql2="SELECT * FROM inhalt WHERE i_name='datumsabfrage';";
            $ergebnis=$dbh->query($sql2);
            $rueckgabewert=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
            print $rueckgabewert[0]['i_inhalt'];
        }
        else{
            print "Falsches Passwort. <form method='post' action='tennisplatzbuchung.php'><input type='submit' value='Weiter'></form>";
        }
    }
    catch(PDOException $e){
        $e ->getMessage();
    }
}
?>