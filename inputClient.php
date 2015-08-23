<?php

/**
 * Failas naudojamas vartotojo sukurimui
 */

header('content-type: text/html; charset=utf-8');
include "./classElement.php";
include "./classUser.php";

$element= new Elements();
$dropDown=$element->createDropdownList();
if (isset($_GET['Ivesti']) && $_GET['Ivesti']=='Įvesti') {
    $user= new User();
    $validation=1;
    if (!$user->validateName($_GET['name'])) {
        echo "Neteisingai įvestas vardas, galimos tik raidės<br>";
        $validation=0;
    }
    if (!$user->validateEmail($_GET['email'])) {
        echo "Neteisingai įvestas email <br>";
        $validation=0;
    }
    if ($validation) {
        $user->initiate($_GET['name'], $_GET['email'], $_GET['news']);
        $user->writeToFile("a");
        echo "Vartotojas sekmingai įvestas";
    }
}
$userName=$_GET['name'];
$email=$_GET['email'];
include "inputForm.php";
echo "<a href='index.php'>Grižti</a><br>";
