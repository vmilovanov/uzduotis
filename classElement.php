<?php
/**
 * Klase naudojama formos elementu kurimui.
 */
class Elements
{
    private $listFile ="./news";
    private $newsList = array();

    function getNewsList()
    {
        $file=$this->listFile;
        if (!$handle=fopen($file, 'r')) {
            echo "Neimanoma atidaryti naujienu failo";
            exit;
        }
        while (!feof($handle)) {
            $string=fgets($handle);
            $string=str_replace("\n", "", $string);
            $tempArray=explode(';', $string);
            array_push($this->newsList, array($tempArray[0], $tempArray[1]));
        }
        fclose($handle);
    }
    
    function createDropdownList()
    {
        $this->getNewsList();
        foreach ($this->newsList as $temp) {
            $output=$output."<option value='".$temp[0]."'>".$temp[1]."</option>";
        }
        return "<select name='news'>$output</select>";
    }
        
    function findNewsName($id)
    {
        $this->getNewsList();
        foreach ($this->newsList as $temp) {
            if ($temp[0] == $id) {
                return $temp[1];
            }
        }
    }
}
