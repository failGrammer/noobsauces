<?php 
include "Autoloader.php";
$autoload = new Autoloader();
?>
<html>
<title>Connect Them!</title>
<div align="center"><font size="4">Welcome to Enhanced Connect... Them</font>
<br>
</html>
<?php
echo "Session_ID = ";
echo session_id();
echo "<br>";
echo "Today is ";
echo date('l jS \of F Y h:i A');

if(isset($_SESSION['logThem'])){
    echo "<br>";
    echo "Session is already set";
    $login = unserialize($_SESSION['logThem']);
    //$conn = new Connection();
    //$gameboard = New GameBoard();
    $gameboard = unserialize($_SESSION['GameBoard']);
    //if($login->get_loginState() === TRUE){
        //$login->connectFourLogin($login->get_userName(), $login->get_passWord());
	//if($login->get_loginState() === TRUE){
                //$_SESSION['logThem'] = serialize($login);
                //$_SESSION['connThem'] = serialize($conn);
                //$_SESSION['GameBoard'] = serialize($gameboard);
        //}
    //}
}
else 
{
    //SESSION[log] has not been set, User is not logged in
    $login = New Login();
    $conn = New Connection();
    $_SESSION['logThem'] = serialize($login);
    $_SESSION['connThem'] = serialize($conn);    
}
?>