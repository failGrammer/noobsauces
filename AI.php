<?php
/**
 * Description of AI
 * This class holds logic for the PC to make moves following the player's moves
 * @author Jonath
 */
class AI {
    //put your code here
    private $column;
    private $aiStyle;
    private $bruteForceSelector;
    private $moveSelection;
    private $potentialPlayerWinList;
    
    function __construct(){
        $this->set_column(2);
        $rand = rand(0, 1);
        $this->aiStyle = $rand;
        $this->set_bruteForceSelector();
    }
    function ai_selectMove($new_boardState, $new_boardHistory){        
        switch($this->get_aiStyle()){
            case 0:
                //ai Brute Force
                $this->ai_bruteForce($new_boardState);
                break;
            case 1:
                //ai Random Attack
                $this->ai_random($new_boardState);
                break;
            case 2:
                //ai Prioritize Defense, then Attack Randomly;
                $this->ai_defend($new_boardState);
                break;
            case 3:
                //ai Prioritize Defense, then Attack Aggressively;
                break;
            case 4:
                //ai Evaluate History, Prioritize Defense, then Attack Based on Current Board;
                break;
            default:
                //ai Evaluate History, Prioritize Defense, Attack Based on Historical Effectiveness;
                break;
        }
        return $this->get_column();
    }
    function ai_random($new_boardState){
        //add check to see if move is legal <-- or place this later and re-run the pc AI if move was invalid
        $rand = rand(0,6);
        if($this->checkLegalityOfMove($rand, $new_boardState) == 1){
            $this->set_column($rand);
        }
        else{
            $this->ai_random($new_boardState);
        }
    }
    function ai_bruteForce($new_boardState){
        //add check to boardstate to see if column needs to be changed
        if($this->checkLegalityOfMove($this->get_bruteForceSelector(), $new_boardState) == 1){
            $this->set_column($this->get_bruteForceSelector());
        }
        else{
            $this->set_bruteForceSelector();
            //$this->ai_bruteForce($new_boardState);
            if($this->checkLegalityOfMove($this->get_bruteForceSelector(), $new_boardState) == 1){
                $this->set_column($this->get_bruteForceSelector());
            }
            else{
                $this->set_bruteForceSelector();
                //$this->ai_bruteForce($new_boardState);
                if($this->checkLegalityOfMove($this->get_bruteForceSelector(), $new_boardState) == 1){
                    $this->set_column($this->get_bruteForceSelector());
                }
                else{
                    $this->set_bruteForceSelector();
                    //$this->ai_bruteForce($new_boardState);
                    if($this->checkLegalityOfMove($this->get_bruteForceSelector(), $new_boardState) == 1){
                        $this->set_column($this->get_bruteForceSelector());
                    }
                }
            }  
        }
    }
    
    function ai_defend($new_boardState){
        $current_boardState = $new_boardState;
        //for each potential row for winning
        //DO{evalPotentialPlayerWinningMoves -- Evaluate each possible win scenario
        // ^ will return array of potential win values?
        $potentialPlayerWinMoves = $this->evalPotentialWinner($current_boardState);
        
        
    }
    
    function evalPotentialPlayerWinningMoves($new_holdingArray){
        //Gets fed 4 values, if 3 of them ==1 and the fourth is ==0, then returns the index of the 0 value
        $potentialWins = array();
        $hA = $new_holdingArray;
        for($i = 0, $j = 0; $i <=3; ++$i){
            if($hA[$i] == 1){
                ++$j;
            }
            if($j >=3 ){
                for($k = 0; $k <= 3; ++$k){
                    if($hA[$k] == 0){
                        array_push($potentialWins, $k); 
                        echo $hA; 
                        return $hA[$k];
                    }
                }
            }           
            //echo "potentialWins";
            //echo $potentialWins;
        }        
        $this->set_potentialPlayerWinList($potentialWins);
        //return $potentialWins;//Set has a possibility of winning in the next move
       // echo var_dump($potentialWins);
    }
    function set_potentialPlayerWinList($new_potentialWinList){
        $this->potentialPlayerWinList = $new_potentialWinList;
    }
    
    function evalPotentialWinner($new_gameSpaces){
        //evaluate if there is a potential winner on the next move
        //Send array of next moves to return
        $gs = $new_gameSpaces;
        $winState = 0;
        $holdingArrayForPotentialWinValues = array(0,0,0,0);
        //Check Consecutive Horizontals Via ROWS
        
        //Check COLUMNS
        if($winState == 0){
            foreach($gs as $row=>$array){
                $hA = $holdingArrayForPotentialWinValues;
                $i=0;
                foreach($array as $column=>$value){
                    //Do stuff to push data into array
                    $hA = $this->fourShifter($hA, $value);
                    if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                        $winState = $this->evalPotentialPlayerWinningMoves($hA);
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
                    if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                        $winState = $this->evalPotentialPlayerWinningMoves($hA);
                    }
                }
            }
        }
        
        //Check PRIMARY DIAGONALS
        if($winState == 0 ){
            
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][$i]);
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 1; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i-1)]);
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 2; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i-2)]);
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 5; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i+1)]);
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 4; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i+2)]);
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            for($i = 0; $i <= 3; ++$i){
                $hA = $this->fourShifter($hA, $gs[$i][($i+3)]);
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
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
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }           
            
            $hA = $holdingArrayForPotentialWinValues;
            $j = 0;
            for($i = 4; $i >= 0; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            
            $hA = $holdingArrayForPotentialWinValues;
            $j = 0;
            for($i = 5; $i >= 0; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            $j = 1;
            for($i = 5; $i >= 0; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            $j = 2;
            for($i = 5; $i >= 1; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }
            $hA = $holdingArrayForPotentialWinValues;
            $j = 3;
            for($i = 5; $i >= 2; --$i){
                $hA = $this->fourShifter($hA, $gs[$i][($j)]);
                ++$j;
                if($this->evalPotentialPlayerWinningMoves($hA) != 0){
                    $winState = $this->evalPotentialPlayerWinningMoves($hA);
                }
            }            
        }
        return $winState;
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
    function set_column($new_column){
        $this->column = $new_column;
    }
    function get_bruteForceSelector(){
        return $this->bruteForceSelector;
    }
    function set_bruteForceSelector(){
        $this->bruteForceSelector = rand(0,6);
    }
    function get_column(){
        return $this->column;
    }
    function get_aiStyle(){
        return $this->aiStyle;
    }
    function checkLegalityOfMove($new_column, array $new_boardState){
        //needs to be moved to GameBoard class, and playermove needs to be checked against this.
        $array = $new_boardState;
        if($array[0][$new_column] != 0){
            return 0;//Move is blocked and is not legal
        }
        else{
            return 1;//Move is not occupied/blocked and legal
        }
    }
    function get_moveSelection(){
        return $this->moveSelection;
    }
}

?>
