<?php
class Autoloader{
    //put your code here
    function __construct() {
        include "Connection.php";
        include "Login.php";
        include "GameBoard.php"; 
        include "AI.php";
        include "Display.php";        
    }
}
?>