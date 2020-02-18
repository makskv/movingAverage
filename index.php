<?php

$file = file('prevody.csv');
foreach ($file as $item) {
        $csv[] = explode(',', $item);
}

foreach ($csv as $item) {
    $arr[] = array_filter($item, 'strlen');
};
 
foreach ($arr as $item) {
    foreach ($item as $value) {
        if ($value == 'Obce') {
            $obec[] = $item;
        } 
        if ($value == 'Kraje') {
            $kraj[] = $item;
        } 
        if ($value == 'SFŽP') {
            $sfzp[] = $item;
        } 
        if ($value == 'Ostatní státní fondy') {
            $ostatni[] = $item;
        }
        if ($value == 'Národní fond') {
            $narodni[] = $item;
        } 
        if ($value == 'Rezervní fond') {
            $rezervni[] = $item;
        } 
        if ($value == 'SFDI ') {
            $sfdi[] = $item;
        } 
        if ($value == 'Penzijní společnosti') {
            $penzijni[] = $item;
        }
    }
}

function trader($array) {
    $arr1 = [];
    $arr2 = [];
    $arr3 = [];

    foreach ($array as $item) {
        $arr1[] = $item[2];
        $arr2[] = $item[3];
        $arr3[] = $item[4];
    } 

 
    $arr1 = trader_sma($arr1);
    $arr2 = trader_sma($arr2);
    $arr3 = trader_sma($arr3);
 
    $res=[];
    foreach ($array as $key => $item) { 
        $item=array_values($item);
        $i=[];
        $i[] = $item[0];
        $i[] = $item[1];
        $i[] = $item[2];
        $i[] = $item[3];
        $i[] = $item[4];
        $i[] = $arr1[$key];
        $i[] = $arr2[$key];
        $i[] = $arr3[$key];
        $res[]=$i;
    }
 
    return $res; 
}


$obec=trader($obec); 
$kraj=trader($kraj); 
$sfzp=trader($sfzp); 
$ostatni=trader($ostatni); 
$narodni=trader($narodni); 
$rezervni=trader($rezervni); 
$sfdi=trader($sfdi); 
$penzijni=trader($penzijni); 



$servername = "localhost";
$username = "root";
$password = "";
// Create connection
$conn = new mysqli($servername, $username, $password);
mysqli_select_db ($conn,'test2');
mysqli_query($conn,'TRUNCATE TABLE table1;');

mysqli_query($conn,"SET NAMES `utf8`");

save_db($conn,$obec);
save_db($conn,$kraj);
save_db($conn,$sfzp);
save_db($conn,$kraj);
save_db($conn,$ostatni);
save_db($conn,$narodni);
save_db($conn,$rezervni);
save_db($conn,$penzijni);


/*echo '<pre>' . var_export($obec, true) . '</pre>';
echo '<pre>' . var_export($kraj, true) . '</pre>';
echo '<pre>' . var_export($sfzp, true) . '</pre>';
echo '<pre>' . var_export($ostatni, true) . '</pre>';
echo '<pre>' . var_export($narodni, true) . '</pre>';
echo '<pre>' . var_export($rezervni, true) . '</pre>';
echo '<pre>' . var_export($sfdi, true) . '</pre>';
echo '<pre>' . var_export($penzijni, true) . '</pre>';
*/


function save_db($conn,$array) {
   
     $columns = "rok,prijemce,prevod_z_vynosu_dani,prevod_sankci,prevod_odvodu,prevod_z_vynosu_dani_after,prevod_sankci_after,prevod_odvodu_after";

    foreach ($array as $key => $value) {
        $_value=[];
        foreach ($value as   $v) {
            $encoded = $v;//htmlentities(utf8_decode($v));
            $_value[]="'".$encoded."'";


        }
        $values  = implode(", ", $_value); 
        $sql = "INSERT INTO `table1` ($columns) VALUES ($values)";

        $res=mysqli_query($conn,$sql); 
    }
}