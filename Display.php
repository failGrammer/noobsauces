<?php

/**
 * Display object includes all methods for generating user interface and properly displaying it in browser
 *
 * @author Jonath
 */
class Display {
    
    private $winState = 0;   
    
    function showDisplay($gameboard){
        if($gameboard->evalWinner($gameboard->get_boardState()) == 0){
            ?>
            <form name="playerMove" action="../../com.ConnectThem/index.php?page=playerMove" method="POST">---
            <input type="radio" name="columnMove" value="c1">-------
            <input type="radio" name="columnMove" value="c2">-------
            <input type="radio" name="columnMove" value="c3">-------
            <input type="radio" name="columnMove" value="c4">-------
            <input type="radio" name="columnMove" value="c5">-------
            <input type="radio" name="columnMove" value="c6">------
            <input type="radio" name="columnMove" value="c7">--
            <br>
            <?php
        }
        $gameSpaces = $gameboard->get_boardState();
        foreach($gameSpaces as $item){
            //$this->showLeftSideWall();
            foreach($item as $key){
                //echo "|----";
                $this->showLeftSideWall();
                switch($key){
                    case 0:
                        $this->showEmptyCell();
                        break;
                    case 1:
                        $this->showPlayerFilledCell();
                        break;
                    case 2:
                        $this->showPCFilledCell();
                        break;
                    default:
                        break;
                }
                $this->showRightSideWall();
                //$this->showRightSideWall();
                //echo "-----|";
            }
            //$this->showRightSideWall();
            echo "<br>";
        }
        $winState = $gameboard->get_winState();
        if($winState != 0){}
        else{
            ?>
            <input type="Submit" value="Move">
            </form>
            <?php
        }
    }
    
    function showLeftSidewall(){
        ?><img src="graphics/leftSideWall.bmp"/><?php
    }    
    
    function showRightSidewall(){
        ?><img src="graphics/rightSideWall.bmp"/><?php
    }    
    function showCenterWall(){
        ?><img src="graphics/centerSideWall.bmp"/><?php
    }
    
    function showEmptyCell(){
        ?><img src="graphics/emptyCell.bmp"/><?php
    }
    
    function showPlayerFilledCell(){
        ?><img src="graphics/playerCell.bmp"/><?php
    }
    
    function showPCFilledCell(){
        ?><img src="graphics/pCcell.bmp"/><?php
    }
    
    function set_winState($new_winState){
        $this->winState = $new_winState;
    }
}