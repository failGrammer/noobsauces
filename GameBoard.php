<?php
class GameBoard {
    private $boardState = array();
    private $moveHistory = array();
    private $winState = 0;
        
    function __construct(){
        //$i = columns
        //$j = rows
        //0 = empty
        //1 = User Controlled Space
        //2 = Computer Controlled Space
        
        for($i=0; $i<6; $i++){
            for($j=0; $j<7; $j++){
                $this->boardState[$i][$j] = 0;
            }
        }
        
    }
    
    function set_playerMove($column){
        $switch = "on";
        $i = 0;
        foreach($this->boardState as $row){
            ++$i;
            if($row[$column] != 0){
                --$i;
                --$i;
                if($i >= 0){
                    if($this->boardState[$i][$column] != 2){
                       $this->boardState[$i][$column]  = 1;
                       array_push($this->moveHistory, array("Player"=>array("column"=>$column, "row"=>$i)));
                       $i= -5;
                    }
                    $switch = "off";
                }
                //else{
                    //$this->cannotMakeMove($column);
                //}
                ++$i;
                ++$i;
            }
        }
        if($switch == "on"){
            $this->boardState[5][$column] = 1;
                array_push($this->moveHistory, array("Player"=>array("column"=>$column, "row"=>$i)));
        }        
    }
    
    function playerMoves($new_columnSelection){
        echo "<br>Your move was at ";
        switch($new_columnSelection){
            case 'c1':
                $this->set_playerMove(0);
                echo " Column 1 ";
                break;
            case 'c2':
                $this->set_playerMove(1);
                echo " Column 2 ";
                break;
            case 'c3':                
                $this->set_playerMove(2);
                echo " Column 3 ";
                break;
            case 'c4':                
                $this->set_playerMove(3);
                echo " Column 4 ";
                break;
            case 'c5':                
                $this->set_playerMove(4);
                echo " Column 5 ";
                break;
            case 'c6':                
                $this->set_playerMove(5);
                echo " Column 6 ";
                break;
            case 'c7':                
                $this->set_playerMove(6);
                echo " Column 7 ";
                break;
            default:
                break;
        }        
        echo "<br>";
    }
    
    function pcMoves($column){        
        $switch = "on";
        $i = 0;
        foreach($this->boardState as $row){
            ++$i;
            if($row[$column] != 0){
                --$i;
                --$i;
                if($i >= 0){
                    if($this->boardState[$i][$column] !=1){
                        $this->boardState[$i][$column] = 2;
                        array_push($this->moveHistory, array("PC"=>array("column"=>$column, "row"=>$i)));
                        $i= -5;
                    }
                    $switch = "off";
                }
                //else{
                    //$this->cannotMakeMove($column);
               // }
                ++$i;
                ++$i;
            }
        }
        if($switch == "on"){
            $this->boardState[5][$column] = 2;
                       array_push($this->moveHistory, array("PC"=>array("column"=>$column, "row"=>$i)));
        }        
    }
    
    function get_moveHistory(){
        return $this->moveHistory;
    }
        
    function get_boardState(){
        return $this->boardState;
    }
    
    function evalHoldingArrayForPotentialWinValues($new_holdingArray){
        $hA = $new_holdingArray;
        if($hA[0] === 1 && $hA[1] === 1 && $hA[2] === 1 && $hA[3] === 1){
            //echo var_dump($hA); 
            return 1;
        }
        else{
            if($hA[0] === 2 && $hA[1] === 2 && $hA[2] === 2 && $hA[3] === 2){
                //echo var_dump($hA); 
                return 2;                
            }
        }        
        return 0;
    }
    
    function evalWinner($new_gameSpaces){
        //evaluate if there is a winner with the current layout of the game
        //SETUP
        $gs = $new_gameSpaces;
        $winState = $this->get_winState();
        $holdingArrayForPotentialWinValues = array(0,0,0,0);
        
        //Check Consecutive Horizontals Via ROWS
        if($winState == 0){
            foreach($gs as $row=>$array){
                $hA = $holdingArrayForPotentialWinValues;
                $i=0;
                foreach($array as $column=>$value){
                    //Do stuff to push data into array
                    $hA = $this->fourShifter($hA, $value);
                    if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                        $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                    }
                    ++$i;
                }
            }
        }
        
        //Check Consecutive Verticals Via COLUMNS
        if($winState == 0){
            for($j = 0; $j <= 6 ; ++$j){
                $hA = $holdingArrayForPotentialWinValues;
                foreach($gs as $row=>$array){
                    $hA = $this->fourShifter($hA, $array[$j]);
                    if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                        $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                    }
                }
            }
        }
        
        //Check PRIMARY DIAGONALS
        if($winState == 0 ){
            
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][$i]);
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 1; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i-1)]);
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 2; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i-2)]);
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i+1)]);
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 4; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i+2)]);
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 3; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i+3)]);
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
        }       
        
        //Check SECONDARY DIAGONALS
        if($winState == 0 ){
            $hA = $holdingArrayForPotentialWinValues;
            $j = 0;
            for($i = 3; $i >= 0; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }           
            
            $hA = $holdingArrayForPotentialWinValues;
            $j = 0;
            for($i = 4; $i >= 0; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            
            $hA = $holdingArrayForPotentialWinValues;
            $j = 0;
            for($i = 5; $i >= 0; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            $j = 1;
            for($i = 5; $i >= 0; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            $j = 2;
            for($i = 5; $i >= 1; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            $j = 3;
            for($i = 5; $i >= 2; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalHoldingArrayForPotentialWinValues($hA) != 0){
                    $winState = $this->evalHoldingArrayForPotentialWinValues($hA);
                }
            }            
        }
        $this->set_winState($winState);
        return $winState;
    }
    
    function record_moveHistory($new_move){
        $recent_move = $new_move;
        $i = array_count_values($this->moveHistory);
        ++$i;
        $this->$moveHistory[$i] = $recent_move;
        //$moveHistory = arrayPush($recent_move)
    }
    
    function fourShifter($new_array, $new_input){
        //Takes the Array, receives an input, puts it at the front and shifts the others back        
        $i3 = $new_array[2];
        $i2 = $new_array[1];
        $i1 = $new_array[0];
        $i0 = $new_input;
        $shiftedArray = array($i0, $i1, $i2, $i3);
        return $shiftedArray;
    }
    
    function checkLegalityOfMove($new_column){
        //needs to be moved to GameBoard class, and playermove needs to be checked against this.
        $column = 0;
        
        switch($new_column){
            case 'c1':
                $column = 0;
                break;
            case 'c2':
                $column = 1;
                break;
            case 'c3':
                $column = 2;
                break;
            case 'c4':
                $column = 3;
                break;
            case 'c5':
                $column = 4;
                break;
            case 'c6':
                $column = 5;
                break;
            case 'c7':
                $column = 6;
                break;
            default:
                break;
        }
        $array = $this->get_boardState();
        if($array[0][$column] != 0){
            return 0;//Move is blocked and is not legal
        }
        else{
            return 1;//Move is not occupied/blocked and legal
        }
    }
    
    function set_winState($new_winState){
        $this->winState = $new_winState;
    }
    function get_winState(){
        return $this->winState;
    }
}
?>