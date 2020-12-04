<?php
include_once "myblackJack.php"; 
//include "blackJack_html.php";

function adjustPrintHand($hand){
	return($hand+1);
}

global $game;


session_start();


//include "blackJack_html.php";

//$this->player->setBets()


if (isset($_SESSION['game'])) {
	
	//print_r($_SESSION['game']);
}
 else { 
	 $_SESSION['game']=$game;
	 $_SESSION['newgame']=0;
	 $_SESSION['hand']=0;
} 
if(isset($_POST['add'])) {
	$game = $_SESSION['game'];
	if($_POST['balance']<0)
	   echo "Amount must be positive";
	else   
		updateUserBalance($_POST['balance'],$_SESSION["user"]);
	
}

global $bustForDouble;
$bustForDouble=false;

if(isset($_POST['deal'])) {
	
	for($i=0;$i<$_POST['hand'];$i++){
		$bets[$i] = $_POST['bet'.$i];
	}
	$game = new Game($_SESSION["user"],0, $_POST['hand'],$bets); // create new game-
    $game->begin($_POST['hand']); // begin new game 
	$_SESSION['newgame']=1;
	$_SESSION['hand']=0;
    
	for($i=0;$i<$_POST['hand'];$i++){

		$blackJack = $game->blackJackCheck($i,false); 
		
	}
	echo "<br>";
	for($i=0;$i<$_POST['hand'];$i++){
		if ($game->player[$i]->blackJack==0){
			$_SESSION['hand']=$i;
			//echo "NO BLACKJACK ".$_SESSION['hand'];
			echo "Player ".adjustPrintHand($_SESSION['hand'])." starts!\n";
			break;
		}
	}
	
	
}
elseif (!empty($_POST['stand']) || !empty($_POST['double']) || !empty($_POST['hit'])) {
	$game = $_SESSION['game'];
}

if(isset($_POST['double'])) {
	$game->player[$_SESSION['hand']]->setDoubleBet();
}
// add

	
	//echo $_SESSION['newgame'];

    if((isset($_POST['hit']) || isset($_POST['double'])) && !empty($game)) {	
    	
	  $blackJack = $game->blackJackCheck($_SESSION['hand'],false);
       if ($blackJack == false) {
    	if ($game->player[$_SESSION['hand']]->blackJack==0){
			$bust = $game->hitOrStay($_SESSION['hand']);
			if ($bust == true) {
				echo "<br>Hand ".adjustPrintHand($_SESSION['hand'])." BUST! <br>";
				$bustForDouble=true;
			    if ($game->hand-$_SESSION['hand']==1){  //if the last hand, we started to check all who not has BJ and didn't bust
			        
			        //unset($game->hand[$_SESSION['hand']]);
			        //$game->dealerDraw();
			        $skip=0;
			        for($i=0;$i<=$_SESSION['hand'];$i++){
		 	          if ($game->player[$i]->end==1){
		 	          	 $skip++;
		 	             //continue;
		 	          }
			        }
			         // echo "Player : ".$i+1;
			         if ($skip<>$game->hand){
			         	$game->dealerDraw();
			        	for($i=0;$i<=$_SESSION['hand'];$i++){
			          		if ($game->player[$i]->end==1){
		 	             		continue;
		 	          		}
		 	          		echo "<br>";
		 	         
		 		      		$game->handCombos($i);
		 		      		sleep(2);
		 		      		echo "<br>";
		 	          	}
			 		  	$_SESSION['newgame']=0;
			        }
			    }   
			 	else {
			 		//unset($game->hand[$_SESSION['hand']]);
			 		for($i=$_SESSION['hand'];$i<$game->hand;$i++){
	     	 		   //echo "hand ".$i." blackjack= ".$game->player[$i]->blackJack;
					    if ($game->player[$i]->blackJack==0 && $game->player[$i]->end==0){
						 $_SESSION['hand']=$i;
						 echo "Next Hand ".adjustPrintHand($_SESSION['hand'])."<br>";
			 		
			             $game->player[$_SESSION['hand']]->echoHand();
						 break;
					    }
					}
			 		
			 		
			 	}
			
			// Don't let the dealer draw, go straight to combos
			} else {
			//$_SESSION['newgame']=0;
			//$game->dealerDraw();
			}
		//$game->handCombos();
		}
		else {
			
			//echo "Hand move: ";
			$_SESSION['hand']++;
			//$game->player[++$_SESSION['hand']]->echoHand();
		}
       }
	   else{
	    
	   	for($i=$_SESSION['hand'];$i<$game->hand;$i++){
		 if ($game->player[$i]->blackJack==0 && $game->player[$i]->end==0  ){
			$_SESSION['hand']=$i;
			//echo "NO BLACKJACK ".$_SESSION['hand'];
			echo "Next Hand ".adjustPrintHand($_SESSION['hand'])." turn\n";
			break;
		  }
	     }
	             if($i==$game->hand){
	                $count=0;
		 	   		for($i=0;$i<=$game->hand;$i++){
		 				if ($game->player[$i]->end==0 && $game->player[$i]->blackJack==0){
		 					$count++;
		 					if($count==1)
		 					  $game->dealerDraw();
		 					  
		 					echo "<br>"; 
		 					$game->handCombos($i);
		 				sleep(2);
		 				echo "<br>"; 
		 				}
		
		 				
		 			}
	             }
	    
	   } 
		//$_SESSION['hand']++;
		
		//echo "SESSION HAND after HIT".$_SESSION['hand'];
		
	}
     if((isset($_POST['stand']) || (isset($_POST['double']) && $bustForDouble==false)) && !empty($game) ){
     	
     	//echo "HANDS ".$game->hand;
     	echo "<br>";
     	//echo "HANDS SESSION: ".$_SESSION['hand'];
     	echo "<br>";
	     if ($game->hand-$_SESSION['hand']==1) {
		 	$game->dealerDraw();
		 	for($i=0;$i<=$_SESSION['hand'];$i++){
		 		if ($game->player[$i]->end==1)
		 	       continue;
		 	    //echo "Player : ".$i+1;
		 	    echo "<br>";
		 		$game->handCombos($i);
		 		sleep(2);
		 		echo "<br>";
		 	}
		 
	     }
	     else{
	     	 
	     	 if ($game->hand - $_SESSION['hand']>1){
	     	    $_SESSION['hand']++;
	     	  
	     	    //echo "POST =".$_POST['hand'];
	     	 	for($i=$_SESSION['hand'];$i<$game->hand;$i++){
	     	 		//echo "hand ".$i." blackjack= ".$game->player[$i]->blackJack;
					if ($game->player[$i]->blackJack==0){
						$_SESSION['hand']=$i;
						echo "<br>";
	     				echo "Next Hand ".adjustPrintHand($_SESSION['hand'])."turn\n";
	     				$game->player[$_SESSION['hand']]->echoHand();
	     				//echo "current hand".$_SESSION['hand'];
				
			    		break;
					}
				}
				
			   if ($i==$game->hand) {	
	     	   		$game->dealerDraw();
		 	   		for($i=0;$i<=$_SESSION['hand'];$i++){
		 				if ($game->player[$i]->end==1 || $game->player[$i]->blackJack==1)
		 	       		continue;
		 	    		//echo "Player : ".$i+1;
		 	    		echo "<br>";
		 				$game->handCombos($i);
		 				sleep(1);
		 				echo "<br>";
		 			}
			   }
				
	     	 }
	     	 else 
	     	 {
	     	      $game->dealerDraw();
		 	   		for($i=0;$i<=$_SESSION['hand'];$i++){
		 				if ($game->player[$i]->end==1 || $game->player[$i]->blackJack==1)
		 	       		continue;
		 	    		//echo "Player : ".$i+1;
		 	    		echo "<br>";
		 				$game->handCombos($i);
		 				sleep(1);
		 				echo "<br>";
		 			}
	     	 	
	     	 	
	     	 	
	     	 }
	     	 
	     	 
	
	     	
	     	
	     	
	     }
	     //$_SESSION['hand']++;
		// $game->setUserBalance(-10);
	 }
    
    $_SESSION['game'] = $game;
	
include "blackJack_html.php";
?>

