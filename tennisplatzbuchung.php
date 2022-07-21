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
$heutigesDatum=date("d.m", $timestamp);
$geteiltesHeutigesDatum=explode(".", $heutigesDatum);
$dbh=new PDO("mysql: host=localhost; dbname=tennisplatzbuchung", "luke", "Fallen2211");
if(empty($_REQUEST["passwort"]) && empty($_REQUEST["datum"]) && empty($_REQUEST["buchungsdatum"])){
    print '<h1>Bitte geben Sie das Passwort ein:</h1>
        <form method="post" action="tennisplatzbuchung.php">
            <input type="password" name="passwort">
            <input type="submit" value="Weiter">
        </form>';
}
if(!empty($_REQUEST["datum"])){
    $datum=$_REQUEST["datum"];
    $geteiltesDatum=explode('.', $datum);
    $ungültigeEingabe=False;
    if(count($geteiltesDatum)!=2){ //es wird geprüft, ob es nur zwei Teile gibt und ob die beiden Teile 2 ziffern enthalten
        $ungültigeEingabe=True;
    }
    else{
        if(strlen($geteiltesDatum[0])!=2 || strlen($geteiltesDatum[1])!=2){
            $ungültigeEingabe=True;
        }
    }
    if($ungültigeEingabe){
        print '<h1>Die Eingabe ist ungültig!</h1>
        <form method="post" action="tennisplatzbuchung.php">
            <input type="hidden" name="passwort" value="1234">
            <input type="submit" value="Weiter">
        </form>';
    }
    else{
        print $geteiltesDatum[0]."<br>";
        print $geteiltesDatum[1]."<br>";
        print "Hier wird jetzt angezeigt, welche Plätze wann frei sind.";// noch zu schreiben
    }
}
if(!empty($_REQUEST["passwort"])){
    $sql="SELECT * FROM inhalt WHERE i_name='passwort';";
    $ergebnis=$dbh->query($sql);
    $rueckgabewert=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
    if($_REQUEST["passwort"]==$rueckgabewert[0]["i_inhalt"]){
        for($i=0; $i<3; $i++){
            $timestamp=time()+60*60*24*$i;
            $datum=date("d.m", $timestamp);
            $sql2="SELECT * FROM daten WHERE d_datum='".$datum."';";
            $ergebnis=$dbh->query($sql2);
            $daten[$i]=$ergebnis->fetchAll(PDO::FETCH_ASSOC);
            //print $daten[$i][0]["d_platz1"]." ".$daten[$i][0]["d_datum"]."<br>";
        }
        print '<table>
                    <tr>
                        <th>Datum</th>
                        <th>Uhrzeit</th>
                        <th>Platz1</th>
                        <th>Platz2</th>
                    </tr>';
        foreach($daten as $i){
            foreach($i as $j){
                if($j["d_platz1"]==0){
                    $statusPlatz1="frei";
                }
                else{
                    $statusPlatz1="belegt";
                }
                if($j["d_platz2"]==0){
                    $statusPlatz2="frei";
                }
                else{
                    $statusPlatz2="belegt";
                }
                print '<tr><td>'.$j["d_datum"].'</td>';
                print '<td>'.$j["d_uhrzeit"].'</td>';
                print '<td><form method="post" action="tennisplatzbuchung.php">
                    <input type="hidden" value="'.$j["d_datum"].'" name="buchungsdatum">
                    <input type="hidden" value="'.$statusPlatz1.'" name="statusPlatz">
                    <input type="submit" value="'.$statusPlatz1.'"></form></td>';
                print '<td><form method="post" action="tennisplatzbuchung.php">
                    <input type="hidden" value="'.$j["d_datum"].'" name="buchungsdatum">
                    <input type="hidden" value="'.$statusPlatz2.'" name="statusPlatz">
                    <input type="submit" value="'.$statusPlatz2.'"></form></td></tr>';
            }
        }
        print "</table>";
        //jetzt kommt die Abfrage für einen anderen Tag
        print '<h1>Möchten Sie an einem anderen Tag buchen?</h1>
            <form method="post" action="tennisplatzbuchung.php">
                <input type="text" name="datum" placeholder="TT.MM">
                <input type="submit" value="Weiter">
            </form>';
        print "Heute ist der ".$heutigesDatum;
        print "<br>$geteiltesHeutigesDatum[0]<br>$geteiltesHeutigesDatum[1]";
    }
    else{
        print "Falsches Passwort. <form method='post' action='tennisplatzbuchung.php'><input type='submit' value='Weiter'></form>";
    }
}
$dbh=null;
?>
</body>
</html>