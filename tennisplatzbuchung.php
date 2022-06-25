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
if(empty($_REQUEST["passwort"])){
    print '<h1>Bitte geben Sie das Passwort ein:</h1>
        <form method="post" action="tennisplatzbuchung.php">
            <input type="password" name="passwort">
        </form>';
}
if(!empty($_REQUEST["passwort"])){
    try{
        $dbh=new PDO("mysql:host=localhost; dbname=tennisplatzbuchung", "luke", "Fallen2211");
        $sql="SELECT * FROM inhalt WHERE i_name='passwort';";
        $ergebnis=$dbh->query($sql);
        $rueckgabewert=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
        if($_REQUEST["passwort"]==$rueckgabewert[0]["i_inhalt"]){
            print "Hier steht der Rest der Website";
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