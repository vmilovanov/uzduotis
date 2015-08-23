<?php

/**
 * HTML vartotojo sukurimui ir redagavimui
 */

if (isset($dropDown)) {
        $additionalInfo="Type of news:<br>".$dropDown;
} else {
        $additionalInfo="<input type=hidden name=action value='edit'>
        <input type=hidden name=id value='$id'>
        ";
}

echo "
<form action='' name='uzklausu_forma' method='GET'>
    Name:<br>
    <input type='text' name='name' value=".$userName.">
    <br>
    Email:<br>
    <input type='text' name='email' value=".$email."><br>
    $additionalInfo
    <input class='button' alt='Ivesti' name='Ivesti' type='submit' value='Įvesti'>
</form><br>
";
