<?php
include_once "myblackJack.php"; 
//include "blackJack_html.php";

global $game;


session_start();

if(isset($_POST['add'])) {
	$game = $_SESSION['game'];
	updateUserBalance($_POST['balance']);
	
	//exit();
}
include "blackJack_html.php";

//$this->player->setBets()


if (isset($_SESSION['game'])) {
	
	//print_r($_SESSION['game']);
}
 else { 
	 $_SESSION['game']=$game;
	 $_SESSION['newgame']=0;
	 $_SESSION['hand']=0;
} 

echo "SESSION HAND".$_SESSION['hand'];
if(isset($_POST['deal'])) {
	
	echo "HANDS".$_POST['hand'];
	for($i=0;$i<$_POST['hand'];$i++){
		echo "i=".$i;
		//echo "BET".$i=$_POST['bet'.$i];
		$bets[$i] = $_POST['bet'.$i];
	}
	$game = new Game('user',0, $_POST['hand'],$bets);
    $game->begin($_POST['hand']);
	$_SESSION['newgame']=1;
	$_SESSION['hand']=0;
	$game->blackJackCheck($_SESSION['hand'],false);  // Geo
	for($i=0;$i<$_POST['hand'];$i++){ // geo
		$blackJack = $game->blackJackCheck($_SESSION['hand'],false); // Geo
	}
	//$_POST['update_balance']=$game->getUserBalance();
	
	//echo "POST BALANCE".$_POST['update_balance'];
	
	
	//$game->player->setBet($_POST['bet1']);
	//echo "BALANCE".$game->getUserBalance();
}
elseif (!empty($_POST['stand']) || !empty($_POST['double']) || !empty($_POST['hit'])) {
	$game = $_SESSION['game'];
}

// add

	
	echo $_SESSION['newgame'];

    if(isset($_POST['hit']) && !empty($game)) {	
    	
		//$blackJack = $game->blackJackCheck($_SESSION['hand'],false);
    	if ($blackJack == false) {
			$bust = $game->hitOrStay($_SESSION['hand']);
			if ($bust == true) {
				echo "<br>Hand ".($_SESSION['hand']+1)." BUST! <br>";
			    if ($game->hand-$_SESSION['hand']==1){
			        
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
		 		      		sleep(1);
		 		      		echo "<br>";
		 	          	}
			 		  	$_SESSION['newgame']=0;
			        }
			    }   
			 	else {
			 		//unset($game->hand[$_SESSION['hand']]);
			 		echo "Next hand move: ";
			 		
			        $game->player[++$_SESSION['hand']]->echoHand();
			 	}
			
			// Don't let the dealer draw, go straight to combos
			} else {  // Geo
				$_SESSION['newgame']=0; // Geo
				$game->dealerDraw(); // Geo
			}
		//$game->handCombos();
		}
		else {
			//unset($game->hand[$_SESSION['hand']]);
			echo "Next hand move: ";
			//$_SESSION['hand']++;
			$game->player[++$_SESSION['hand']]->echoHand();
		} 
		//$_SESSION['hand']++;
		
		echo "SESSION HAND after HIT".$_SESSION['hand'];
		
	}
     if(isset($_POST['stand']) && !empty($game) ){
     	
     	echo "HANDS ".$game->hand;
     	echo "<br>";
     	echo "HANDS SESSION: ".$_SESSION['hand'];
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
	     	echo "<br>";
	     	echo "Next hand move ";
	     	$game->player[++$_SESSION['hand']]->echoHand();
	     	echo "current hand".$_SESSION['hand'];
	     }
	     //$_SESSION['hand']++;
		// $game->setUserBalance(-10);
	 }
    
    $_SESSION['game'] = $game;
	//do {
	//	echo "Want to play again? (Y)es (N)o ";
		//$input = get_input(true);
	//} while($input != 'Y' && $input != 'N');
//} while ($input == 'Y');

?>
<!-- <html>
	<head></head>
<body>

<h2>Black Jack</h2>



<form method="POST" action="blackJack_main.php ">

   Bet <input type="text" name="bet" value="bet"></br></br>	
   <input type="submit" name="hit" value="Hit" />
   <input type="submit" name="stand" value="Stand" />
   <input type="submit" name="deal" value="Deal" />
   <input type="submit" name="double" value="Double"/>
   <input type="submit" name="split" value="Split"/>
	


</form>
	</body>
</html>-->
