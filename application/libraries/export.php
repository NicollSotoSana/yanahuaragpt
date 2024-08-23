<?php 
class Export{
    
    function to_excel($array, $filename) {
        header('Content-type: application/vnd.ms-word');
        header('Content-Disposition: attachment; filename='.$filename.'.doc');

         //Filter all keys, they'll be table headers
	    echo $array;        
            
    }
    function writeRow($val) {
                echo '<td>'.utf8_decode($val).'</td>';              
    }

}
?>