<?php

/**
 * Klase naudojama vartotojo aptarnavimui
 */    

class User {
    private $id;
    private $name;
    private $email;
    private $news;
    private $inputDate;
    private $fileName="./data";
    private $backup="./databack";
    private $usersList=array();
    
    function  initiate ($name, $email, $news)
	{        
        $this->name=$name;
        $this->email=$email;
        $this->news=$news;
        $this->id=microtime(true);
        $this->inputDate=time()+3600*3;        
    }
    
    function writeToFile($action)
	{
        $file=$this->fileName;
        if (!$handle=fopen($file,$action)) {
            echo "Neįmanoma atidaryti vartotoju failo"; exit;
        }
        $string=$this->id.";".$this->name.";".$this->email.";".$this->news.";".$this->inputDate."\n";
        fwrite ($handle, $string);
        fclose(handle);
    }
    function readFromFile($keyField)
	{
        $file=$this->fileName;
        if (!$handle=fopen($file,'r')) {
            echo "Neįmanoma atidaryti vartotojų failo"; 
			exit;
        }
        while (!feof($handle)) {
            $string=fgets($handle);
            $string=str_replace("\n", "", $string);
            $tempArray=explode(';', $string);
/**
 * Sukuriam associatyvu masyva, tam kad ji butu galima rusiuoti. Rusiuojam pagal rakta
 */
            if ($keyField=="name" || $keyField=="") {
                $key=strtolower($tempArray[1])."_".$tempArray[0];
            } elseif ($keyField=="email") {
                $key=strtolower($tempArray[2])."_".$tempArray[0];
            } elseif ($keyField=="date") {
                $key=strtolower($tempArray[4])."_".$tempArray[0];
            }
            $this->usersList[$key]= array($tempArray[0], $tempArray[1], $tempArray[2], $tempArray[3], $tempArray[4]);
            ksort($this->usersList);
        }
        fclose($handle);
    }
    
    function createUsersList($sort)
	{        
        $this->readFromFile($sort);    
        $UsersList="";
        $UsersList=$UsersList."<tr>
                                    <td>
                                        <a href='listClients.php?sort=name'>Vardas</a>
                                    </td>
                                    <td>
                                        <a href='listClients.php?sort=email'>Paštas</a>
                                    </td>
                                    <td>
                                        Naujienos
                                    </td>
                                    <td>
                                        <a href='listClients.php?sort=date'>Data</a>
                                    </td>
                                    <td>
                                        Veiksmas
                                    </td>
                                </tr>";
        foreach ($this->usersList as $userData) {
            if ($userData[1]!="") {
                $news= new Elements();
                $newsName=$news->findNewsName($userData[3]);
                $date=gmdate("Y-m-d H:i:s", $userData[4]);
                $UsersList=$UsersList."<tr><td><a href='listClients.php?action=edit&id=".$userData[0]."'>".$userData[1]."</a></td><td>".$userData[2]."</td><td>".$newsName."</td><td>$date</td><td><a href='listClients.php?action=delete&id=".$userData[0]."'>Trinti</a></td></tr>";
            }
        }
        $UsersList="<table border=1>$UsersList</table>";
        return $UsersList;
    }
/**
 * Pries padarant veiksmus su duomenims, juos issaugojam
 */
    function clearData()
	{
        rename ($this->fileName,$this->backup);
    }
    
    function deleteUser($id)
	{
        $this->readFromFile();
        $this->clearData ();
        foreach ($this->usersList as $userData) {            
            if (!strpos ("a".$userData[0],$id)) {
                $this->name=$userData[1];
                $this->email=$userData[2];
                $this->news=$userData[3];
                $this->inputDate=$userData[4];
                $this->id=$userData[0];
                $this->writeToFile("a");
            } else {
                echo "Vartotojas sekmingai ištrintas, atnaujinkite puslapį tam kad pamatyti naują sarašą";
            }
        }
    }
/**
 * Vartotojo suradimas pagal id
 */
    function getUserData($id)
	{
        $this->readFromFile();
        foreach ($this->usersList as $userData) {            
            if (strpos ("a".$userData[0], $id)) {
                $this->name=$userData[1];
                $this->email=$userData[2];
                $this->news=$userData[3];
                $this->inputDate=$userData[4];
                $this->id=$userData[0];
            }
        }        
    }
/**
 * Vartotojo duomenu keitimas
 */    
    function changeUser($name,$email,$id)
	{
        $this->readFromFile();
        $this->clearData ();
        foreach ($this->usersList as $userData) {            
            $this->news=$userData[3];
            $this->id=$userData[0];
            $this->inputDate=$userData[4];            
            if (!strpos ("a".$userData[0],$id)) {
                $this->name=$userData[1];
                $this->email=$userData[2];            
            } else {
                $this->name=$name;
                $this->email=$email;
            }
            $this->writeToFile("a");
        }        
    }
    
    function getUserName()
	{
        return $this->name;
    }
    function getUserEmail()
	{
        return $this->email;
    }
    function validateName($string)
	{
        return preg_match("/^[a-zA-Z]+$/", $string);
    }
    
    function validateEmail($string)
	{
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }
}

?>