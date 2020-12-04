
<?php
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
        $str=sprintf("|".$this->value.$this->suit."|");
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
}
*/
class Deck {
	
	public  $deck = array();
	//public deck[];
	function __construct() {
		
		$deck = $this->build_deck();
	}
	function build_deck() {
		
		$suit_array= array();
		//global $i;
		//global $j;
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

			for($i = 0; $i <= 9; $i++) {
				$suit_array[$i] = new Card(($i + 2), $name);
			}
			$suit_array[10] = new Card('J', $name);
			$suit_array[11] = new Card('Q', $name);
			$suit_array[12] = new Card('K', $name);
	       
	        
			$this->deck = array_merge($this->deck,$suit_array);
			
			//echo $this->deck[13]->__toString();
		
		}
		//echo $this->deck[4]->__toString();
		//echo $this->deck[24]->__toString();
		//echo $this->deck[13]->__toString();
		 for($i = 0; $i <sizeof($this->deck); $i++) {
			 //echo $this->deck[$i];
		 }
		
	     $this->shuffleDeck();
		echo "<br><br>";
		for($i = 0; $i <sizeof($this->deck); $i++) {
			 //echo $this->deck[$i];
		 }
		echo "NEW CARD <br>";
		$card = $this->getFromDeck();
		echo $card;
		echo "<br><br>";
		for($i = 0; $i <sizeof($this->deck); $i++) {
			// echo $this->deck[$i];
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
	function __construct($name,  $hand = []) {
		$this->name = $name;
		$this->hand = $hand;
		//echo $this->hand;
		
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
			echo $this->hand[0]->title() . " [XX] Total: XX\n";
		}
	}
}

class Game {
	function __construct($pName = 'Player', $pMoney = 0) {
		$this->minBet = 5;
		$this->maxBet = 100;
		$this->pMoney = $pMoney;
		$this->deck = new Deck();
		$this->player = new Hand($pName);
		$this->dealer = new Hand('Dealer');
		//$this->deck_array = $this->deck->build_deck();
		//$this->finished_deck = call_user_func_array('array_merge', $this->deck_array);
		$this->deck->shuffleDeck();
	}
	function begin() {
		//delay();
		sleep(1);
		$this->player->drawCard($this, 2);
		$this->player->echoHand($this, 2);
		//delay();
		sleep(1);
		$this->dealer->drawCard($this, 2);
		$this->dealer->echoHand(false);
		for($i = 0; $i <sizeof($this->deck->deck); $i++) {
			 echo $this->deck->deck[$i];
		 }
	}
	function blackJackCheck() {
		$blackJack = false;
		if ($this->player->getHandTotal() == 21 && $this->dealer->getHandTotal() == 21) {
			delay();
			$this->dealer->echoHand();
			delay();
			echo "Player and Dealer had BlackJack! The hand is a Push!\n";
			$blackJack = true;
		} elseif ($this->player->getHandTotal() == 21) {
			delay();
			echo "BlackJack! You win!\n";
			delay();
			$blackJack = true;
		} elseif ($this->dealer->getHandTotal() == 21) {
			delay();
			$this->dealer->echoHand();
			delay();
			echo "Dealer had BlackJack! You lose!\n";
			$blackJack = true;
		}
		return $blackJack;
	}
	function hitOrStay() {
		$bust = false;
		while ($this->player->getHandTotal() < 21) {
			delay();
			echo "Would you like to (H)it or (S)tay? ";
			$input = get_input(true);
			if ($input == 'H') {
				$cardDrawn = $this->player->drawCard($this->finished_deck);
				delay();
				echo "You drew: " . $cardDrawn->title() . "\n";
				delay();
				$this->player->echoHand();
			} elseif ($input == 'S') {
				break;
			} else {
				echo "ERROR: Invalid input.\n";
			}
		}
		if ($this->player->getHandTotal() > 21) {
			$bust = true;
		}
		return $bust;
	}
	function dealerDraw() {
		delay();
		$this->dealer->echoHand();
		while ($this->dealer->getHandTotal() < 17) {
			$cardDrawn = $this->dealer->drawCard($this->finished_deck);
			delay();
			echo "Dealer drew: " . $cardDrawn->title() . "\n";
			delay();
			$this->dealer->echoHand();
		}
	}
	function handCombos() {
		$pTotal = $this->player->getHandTotal();
		$dTotal = $this->dealer->getHandTotal();
		if ($pTotal > 21) {
			delay();
			echo "You Busted!\n";
		} elseif ($dTotal > 21) {
			delay();
			echo "Dealer busted! You win!\n";
		} elseif ($dTotal == $pTotal) {
			delay();
			echo "Push! You tied!\n";
		} elseif ($pTotal > $dTotal) {
			delay();
			echo "You win!\n";
		} elseif ($pTotal < $dTotal) {
			delay();
			echo "You lose!\n";
		}
	}
}

//$dech1 = new deck();
$game = new Game();

$game->begin();

?>
