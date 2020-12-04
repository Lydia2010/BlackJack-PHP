
<?php

function createPdo()
    {
    	$host = 'localhost:81';
    	$user = 'root';
    	$password = '';
    	$dbname = 'black jack';
    	// Set DSN
    	$dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
    	// Create a PDO instance
    	$pdo = new PDO($dsn, $user, $password);
    	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    	return $pdo;
    }
	
	function updateUserBalance($balance,$username){
    	$pdo=createPdo();
    	//table must be created with autoincrement functionality
    	
     	$sql = "UPDATE balance SET balance = balance+:b WHERE username = :user";
     	$stmt = $pdo->prepare($sql);
     	
     	$success=$stmt->execute(array(':b' => $balance,  ':user' => $username));
     	$pdo=null;
    	
    	return $success;
	}
	
	function retrieveUserBalance($username){		
	   $pdo=createPdo();
    // count
	 	//$sql = "SELECT balance FROM balance WHERE id = :id";
	   $sql = "SELECT balance FROM balance WHERE username = ?";
		//$sql = "SELECT count(1) FROM balance WHERE id = 'user01'";
       $stmt = $pdo->prepare($sql);
       $stmt->execute(array($username)); 
       //$stmt->execute(array());
	 //$stmt->execute(array(':id' => $this->userId));   
       $userBalance = $stmt->fetchColumn(); 
	 
       return $userBalance;
		
	}


class Card {
	public $value = '';
	public $suit = '';
	function __construct($value = '', $suit = '') {
		$this->value = $value;
		$this->suit = $suit;
		
	}
	function title() {
		$suit = $this->suit[0];
		return "[$this->value $suit]";
		//
	}
	function cardIsAce($card) {
		$aceOrNot = '';
		if ($card->value == 11) {
			$aceOrNot = true;
		} else {
			$aceOrNot = false;
		}
		return $aceOrNot;
	}
	
	public function __toString()
    {
        $str=sprintf(" |".$this->value.$this->suit."| ");
        return $str;
    }
}
/*class Suit {
	public $name = '';
	function __construct($name = '') {
		$this->name = $name;
	}
	function build_suit() {
		$suit_array = [];
		for($i = 0; $i <= 9; $i++) {
			$suit_array[$i] = new Card(($i + 2), $this->name);
		}
		$suit_array[10] = new Card('J', $this->name);
		$suit_array[11] = new Card('Q', $this->name);
		$suit_array[12] = new Card('K', $this->name);
		return $suit_array;
	}
}*/
class Deck {
	
	public $deck = array();
	//public deck[];
	function __construct() {
		
		$deck = $this->build_deck();
	}
	function build_deck() {
		
		$suit_array= array();
		
		$i=0; 
		$j=0;
		
		$name="";
		for($j = 0; $j < 4; $j++) {
			switch ($j) {
    			case 0:
        	    	$name = 'H';
        			break;
    		    case 1:
        			$name = 'D';
        			break;
    			case 2:
        			$name = 'C';
					break;
				case 3:
        			$name = 'S';
					break;
			}

			for($i = 7; $i <= 9; $i++) {
				$suit_array[$i] = new Card(($i + 2), $name);
			}
			$suit_array[10] = new Card('J', $name);
			$suit_array[11] = new Card('Q', $name);
			$suit_array[12] = new Card('K', $name);
	       
	        
			$this->deck = array_merge($this->deck,$suit_array);
			
			//echo $this->deck[13]->__toString();
		
		}
		
	
   }
	
	
	function shuffleDeck() {
	       shuffle($this->deck);
	}
	
	function getFromDeck() {
	       
			$card  = array_shift($this->deck);
			return $card;
	}
}

class Hand {
	function __construct($name,  $hand = array(), $bet) {
		$this->name = $name;
		$this->hand = $hand;
		$this->bet = $bet;
		$this->end = 0;
		$this->blackJack = 0;
		
		//echo $this->hand;
		
	}
	function setBet($bet=0) {
	  $this->bet=$bet;
	 
	}
	function setDoubleBet(){
		$this->bet= 2*$this->bet;
	}
	
	function getHandTotal() {
		$total = 0;
		$aceNum = 0;
		foreach ($this->hand as $data) {
			$value = $data->value;
			if (is_string($data->value)) {
				$value = 10;
			}
			$total += $value;
			if ($data->cardIsAce($data)) {
				$aceNum++;
			}
		}
		while ($total > 21 && $aceNum > 0) {
			$total -= 10;
			$aceNum--;
		}
		return $total;
	}
	function drawCard($game, $amount = 1) {
		for ($i = 0; $i < $amount; $i++) {
			$this->hand[count($this->hand)] = array_shift($game->deck->deck);
		}
		if ($amount == 1) {
			return $this->hand[count($this->hand) - 1];
		}
	}
	function echoHand($player = true) {
		echo $this->name . ": ";
		if ($player) { 
			foreach($this->hand as $data) {
				echo $data->title() . " ";
			}
			echo "Total: " . $this->getHandTotal() . "\n";
		} else {
			echo "dealer card: ";
			echo $this->hand[0]->title() . " [XX] Total: XX\n";
		}
	}
}

class Game {
	function __construct($pName = 'Player', $pMoney = 0, $hand = 1, $bets=array()) {
		$this->minBet = 5;
		$this->maxBet = 100;
		$this->pMoney = $pMoney;
		$this->hand = $hand;
		$this->deck = new Deck();
		$countBets = 0;
		for($i=0;$i<$hand;$i++){ //create hand 
			
			$this->player[$i] = new Hand($pName,array(),$bets[$i]);
			$countBets +=$bets[$i];
		}
		
		$this->dealer = new Hand('Dealer',array(),0);
		$this->userBalance = retrieveUserBalance($_SESSION["user"]);//get balance from DB
		//echo "SUM BETS ".$countBets;
		if ($this->userBalance<$countBets){
			echo "Please add to your balance";
			exit();
		}	  
		
		$this->deck->shuffleDeck();
	}
	function begin($hand) {  
		
		for($i=0;$i<$hand;$i++){
			//echo "Player ".$i+1;
			$this->player[$i]->drawCard($this, 2);//gives card, first card from deck
			$this->player[$i]->echoHand($this, 2);
			sleep(1);
			echo"<br>";
			//$blackJack = $this->blackJackCheck($i,false);
			
		}
		//delay();
		sleep(1);
		$this->dealer->drawCard($this, 2);
		$this->dealer->echoHand(false);
		/*for($i = 0; $i <sizeof($this->deck->deck); $i++) {
			 echo $this->deck->deck[$i];
		 }
		 */
	}
	
	function blackJackCheck($hand,$dealerCheck) {
		$blackJack = false;
		$tHand= $hand+1;
		if ($dealerCheck==true) {
			if ($this->player[$hand]->getHandTotal() == 21 && $this->dealer->getHandTotal() == 21) {
				sleep(1);
				$this->dealer->echoHand();
				sleep(1);
				echo "Hand ".$hand." and Dealer had BlackJack! The hand " .$tHand." is a Push!\n";
				$blackJack = true;
			} elseif ($this->player[$hand]->getHandTotal() == 21) {
				sleep(1);
				echo "BlackJack! Hand ".$tHand." win!\n";
				sleep(1);
				$blackJack = true;
				$this->player[$hand]->end = 1;
				
			} elseif ($this->dealer->getHandTotal() == 21) {
				sleep(1);
				$this->dealer->echoHand();
				sleep(1);
				echo "Dealer had BlackJack! Hand ".$tHand." lose!\n";
				$blackJack = true;
				$this->player[$hand]->end = 1;
			}
		}
		else
		{
			if ($this->player[$hand]->getHandTotal() == 21) {
				sleep(1);
				echo "BlackJack! Hand ".$tHand." win!\n";
				sleep(1);
				$blackJack = true;//this hand ends
				$this->player[$hand]->end = 1;
				$this->player[$hand]->blackJack = 1;
				$this->setUserBalance($this->player[$hand]->bet*1.5);
				
			}
		}
		return $blackJack;
	}
	function hitOrStay($hand) {
		$bust = false;
		if($this->player[$hand]->getHandTotal() < 21) {
			//sleep(2);
			//echo "(H)it or (S)tay?";
					
				$cardDrawn = $this->player[$hand]->drawCard($this);
				sleep(1);
				echo "You drew: " . $cardDrawn->title() . "\n";
				sleep(2);
				$this->player[$hand]->echoHand();
			    $this->dealer->echoHand(false);
			
		}
		if ($this->player[$hand]->getHandTotal() > 21) {
			$bust = true;
			$this->player[$hand]->end = 1;
			$this->setUserBalance($this->player[$hand]->bet*-1);//geo
		}
		return $bust;
	}
	function dealerDraw() {
		sleep(2);
		$this->dealer->echoHand();
		while ($this->dealer->getHandTotal() < 17) {
			$cardDrawn = $this->dealer->drawCard($this);
			sleep(2);
			echo "Dealer drew: " . $cardDrawn->title() . "\n";
			sleep(2);
			$this->dealer->echoHand();
		}
	}
	function handCombos($hand) {
		$win = 1;
		$bet = $this->player[$hand]->bet;
		$pTotal = $this->player[$hand]->getHandTotal();
		$dTotal = $this->dealer->getHandTotal();
		$tHand= $hand+1;
		if ($pTotal > 21) {
			sleep(1);
			echo "Hand ".$tHand." Busted!\n";
			$win=0;
			$this->player[$hand]->end = 1;
		} elseif ($dTotal > 21) {
			sleep(1);
			echo "Dealer busted! Hand ".$tHand." win!\n";
			$win=1;
		} elseif ($dTotal == $pTotal) {
			sleep(1);
			echo "Hand ".$tHand." is a Push! Hand ".$tHand." tied!\n";
			$win=3;
		} elseif ($pTotal > $dTotal) {
			sleep(1);
			echo "Hand ".$tHand." win!\n";
			$win=1;
		} elseif ($pTotal < $dTotal) {
			sleep(1);
			echo "Hand ".$tHand." lose!\n";
			$win=0;
			$this->player[$hand]->end = 1;
		}
		
		$this->player[$hand]->echoHand();
		
		if($win==0)
		 $bet = $bet * -1;
		 
		 if($win<>3)
		 	$this->setUserBalance($bet);
	}
	
	function setUserBalance($bet){
	   $this->userBalance += $bet;
	   updateUserBalance($bet,$_SESSION["user"]);
	}
	
	function getUserBalance(){
	   return $this->userBalance;	
	}
	
}


?>
