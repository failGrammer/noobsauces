<?php
//First page accessed by typical user, Default page
//Contains logic that runs sub-site
session_start();
include "com.ConnectThem.header.php";
if(isset($_GET['page'])){
    //USE SWITCH STATEMENT TO EVALUATE PAGE AND ALSO SANITIZE IT FIRST!!!
    $page = $_GET['page'];
    if(isset($_POST['columnMove']) && $_POST['columnMove'] != null){
        switch($_POST['columnMove']){
            case 'c1':
                $column = 'c1';
                break;
            case 'c2':
                $column = 'c2';
                break;
            case 'c3':
                $column = 'c3';
                break;
            case 'c4':
                $column = 'c4';
                break;
            case 'c5':
                $column = 'c5';
                break;
            case 'c6':
                $column = 'c6';
                break;
            case 'c7':
                $column = 'c7';
                break;
            default:
                break;
        }
    }
    else{
        $column = -1;
    }
    switch($page){ 
        case 'playerMove':
            $ai = unserialize($_SESSION['AI']);
            $gameboard = unserialize($_SESSION['GameBoard']);
            if($gameboard->checkLegalityOfMove($column) == 1){//$_POST['columnMove']) == 1){
                $gameboard->playerMoves($column); //$_POST['columnMove']);
                if($gameboard->evalWinner($gameboard->get_boardState()) == 0){
                    $gameboard->pcMoves($ai->ai_selectMove($gameboard->get_boardState(), $gameboard->get_moveHistory()));
                }
                $game = serialize($gameboard);
                //$gameboard->saveGameBoard(session_id(), $game);
            }
            else{
                echo "<br>You did not select a usable column for your move. Try again.<br>";
            }
            
            //$gameboard->display($gameboard);
            $display = New Display();
            $display->showDisplay($gameboard);
            if($gameboard->evalWinner($gameboard->get_boardState()) == 0){
                echo "Nobody Won with the most recent move";
            }
            else if($gameboard->evalWinner($gameboard->get_boardState()) == 1){
                echo "You Won this Game! ";                
                ?><a href="index.php">click here for a new game.</a><br><?php
            }
            else{
                if($gameboard->evalWinner($gameboard->get_boardState()) == 2){
                echo "You Lost This Game!LOSER!";
                ?><a href="index.php">click here for a new game.</a><br><?php
                }
            }
            //echo var_dump($gameboard->get_moveHistory());
            //echo var_dump($gameboard->get_gameSpaces());
            $_SESSION['GameBoard'] = serialize($gameboard);
            break;
        default:
           
            break;
    }
}
else{
    //User Has not chosen a page to view  
    $gameboard = New GameBoard();
    $display = New Display();
    $ai = New AI();
    $display->showDisplay($gameboard);
    $_SESSION['GameBoard'] = serialize($gameboard);
    //echo $_SESSION['GameBoard'];
    $_SESSION['AI'] = serialize($ai);
}
?>