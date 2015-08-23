<?php

/**
 * Skriptas naudojamas vartotoju saraso atvaizdavimui ir atskiro vartotojo redagavimui
 * Norint pamatyti vartotoju sarasa reikia autorizuotis. Autorizacijos saugojimui naudojami sessijos kintamieji
 */

header('content-type: text/html; charset=utf-8');
session_start();
include "./classElement.php";
include "./classUser.php";

if (isset($_POST['Registruotis']) && $_POST['Registruotis']=='Registruotis') {
    $_SESSION["user"]=$_POST['user'];
    $_SESSION["pass"]=$_POST['pass'];
}

if ($_SESSION["user"] == "trinti" && $_SESSION["pass"] == "leidziama") {
    $user=new User();
/**
 * Vartotojo trinimas
 */
    if ($_GET['action']=="delete") {
        $user->deleteUser($_GET['id']);
        echo $user->createUsersList($_GET['sort']);
/**
 * Vartotojo duomenu keitimas
 */
    } elseif ($_GET['action']=="edit") {
        $user->getUserData($_GET['id']);
        if (isset($_GET['name'])) {
            $userName=$_GET['name'];
            $email=$_GET['email'];
        } else {
            $userName=$user->getUserName();
            $email=$user->getUserEmail();
        }
        $id=$_GET['id'];
        if (isset($_GET['Ivesti']) && $_GET['Ivesti']=='Įvesti') {
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
                $user->changeUser($_GET['name'], $_GET['email'], $_GET['id']);
                echo "Vartotojas sekmingai įvestas";
            }
        }
        include "./inputForm.php";
    } else {
        echo $user->createUsersList($_GET['sort']);
    }
    
    echo    "<a href='' onClick='history.go(0)'>Atnaujinti</a> | <a href='index.php'>Grįžti</a><br>";
} else {
/**
 * Autorizacijos dalis
 */
    echo "
    <form action='' name='uzklausu_forma' method='POST'>
        Username:<br>
        <input type='text' name='user'>
        <br>
        Pass:<br>
        <input type='password' name='pass'><br>
        <input class='button' alt='Įvesti' name='Registruotis' type='submit' value='Registruotis'>
    </form>";
}
