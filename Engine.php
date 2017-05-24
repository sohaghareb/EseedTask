<?php
	//create all the 28 peices 
	
	/*$cards=[];*/

	if(isset($_GET['action']) && function_exists($_GET['action'])) {
	    $action = $_GET['action'];
	    //console_log($action);
	    //echo $action;
	    switch($action) {


		    case 'nextMove':nextMove();return;
		    case 'play':play();return;
		    case 'update_available':update_available();return;
		    default:
		        die('Access denied for this function!');
	}
    // do whatever with the result
}
	
	$player1=0;
	$player2=0;
	$player3=0;
	$player4=0;
	//i should make them global
	$GLOBALS['cards'] = [];
	


	/*$player1_cards=[];
	$player2_cards=[];
	$player3_cards=[];
	$player4_cards=[];
	
	$play=false;*/
	
	$GLOBALS['player1_cards'] = [];
	$GLOBALS['player2_cards'] = [];
	$GLOBALS['player3_cards'] = [];
	$GLOBALS['player4_cards'] =[];

	//$GLOBALS['index'] = 0;
	$index=0;
	$GLOBALS['play'] =false;





	/*$current_player=1;
	$avail_one=0;
	$avail_two=0;*/

	$GLOBALS['current_player'] = 1;
	$GLOBALS['avail_one'] = 0;
	$GLOBALS['avail_two'] = 0;

	$player1_codes=[];
	$player2_codes=[];
	$player3_codes=[];
	$player4_codes=[];


	for($i =0; $i< 7 ;++$i){
		for($j=$i;$j<7;++$j){
			$domino_card=array(
				"left_val"=> $i,
				"right_val"=> $j,
				"left_neigh"=>"",
				"right_neigh"=>"",
				"code" => ($i . $j),
				"index" => $index );
			array_push($GLOBALS["cards"], $domino_card);
			$taken=false;
			$player=rand(1,4);
			while(!$taken){	
				switch ($player) {
					case 1:
						if($player1 < 7){
							$taken=true;
							++$player1;
							array_push($GLOBALS["player1_cards"],$domino_card);
							array_push($player1_codes,$domino_card["code"]);
						}
						else{
							++$player;
						}
						break;
					case 2:
						if($player2 < 7){
							$taken=true;
							++$player2;
							array_push($GLOBALS["player2_cards"],$domino_card);
							array_push($player2_codes,$domino_card["code"]);
						}
						else{
							++$player;
						}
						break;
					case 3:
						if($player3 < 7){
							$taken=true;
							++$player3;
							array_push($GLOBALS["player3_cards"],$domino_card);
							array_push($player3_codes,$domino_card["code"]);
						}
						else{
							++$player;
						}
						break;

					default:#player4
						if($player4 < 7){
							$taken=true;
							++$player4;
							array_push($GLOBALS["player4_cards"],$domino_card);
							array_push($player4_codes,$domino_card["code"]);
						}
						else{
							$player=1;
						}
						break;
				}
			}
			$index++;

		}
	} 
	session_start();
    $_SESSION['current_player']=1;
    //console_log($_SESSION['current_player']);
    //$current_player=1;
	$all_players=[];
	array_push($all_players, $player1_codes);
	array_push($all_players, $player2_codes);
	array_push($all_players, $player3_codes);
	array_push($all_players, $player4_codes);
	 $_SESSION["player1_cards"]=$GLOBALS["player1_cards"];
	 $_SESSION["player2_cards"]=$GLOBALS["player2_cards"];
	 $_SESSION["player3_cards"]=$GLOBALS["player3_cards"];
	 $_SESSION["player4_cards"]=$GLOBALS["player4_cards"];


	 $_SESSION["player1_codes"]= $player1_codes;
	 $_SESSION["player2_codes"]= $player2_codes;
	 $_SESSION["player3_codes"]= $player3_codes;
	 $_SESSION["player4_codes"]= $player4_codes;

	 $_SESSION['avail_one']=1;
	 $_SESSION['avail_two']=2;

	function can_play_helper($avail_one,$avail_two,$array){
		$bool=false;
		session_start();
		// echo 'avail1 '.$avail_one;
		// echo 'avail2 '.$avail_two;
		// echo json_encode($array);
		// echo $_SESSION['current_player'];

		foreach ($array as $card) {
			//echo "left val is ".$card["left_val"]." right val is ".$card["right_val"].'is boolean '.is_int($card["left_val"]);
					if($card["left_val"] === $avail_one  ){
							$avail_one=$card["right_val"];
							$_SESSION["avail_one"]=$avail_one;
							$_SESSION["avail_two"]=$avail_two;
							return $card["index"];
					}
					else if($card["left_val"] === $avail_two  ){
							$avail_two=$card["right_val"];
							$_SESSION["avail_one"]=$avail_one;
							$_SESSION["avail_two"]=$avail_two;
							return $card["index"];

					}
					else if ($card["right_val"] === $avail_two  ){
							$avail_two=$card["left_val"];
							$_SESSION["avail_one"]=$avail_one;
							$_SESSION["avail_two"]=$avail_two;
							return $card["index"];

					}
					else if($card["right_val"] === $avail_one ){
							$avail_one=$card["left_val"];
							$_SESSION["avail_one"]=$avail_one;
							$_SESSION["avail_two"]=$avail_two;
							return $card["index"];
					}
					
					

					//|| $card["left_val"] == $avail_two || $card["right_val"] == $avail_one || $card["right_val"] == $avail_two
				}
				
				
				return -1;
	}

	echo json_encode($all_players);
	//check if the player can play
	function can_play(){
		//session_start();
		$player2_cards=$_SESSION["player2_cards"];
		$player3_cards=$_SESSION["player3_cards"];
		$player4_cards=$_SESSION["player4_cards"];
		$avail_one=$_SESSION["avail_one"];
		$avail_two=$_SESSION["avail_two"];

		switch ($_SESSION['current_player']) {
			case 2:
				return can_play_helper($avail_one,$avail_two,$player2_cards);
			
				break;

				case 3:
				return can_play_helper($avail_one,$avail_two,$player3_cards);
			
				break;

				case 4:
				return can_play_helper($avail_one,$avail_two,$player4_cards);
			
				break;
			default:
				# code...
				break;
		}
	}
	//pass $cards to player
	 function nextMove(){
	 	$move="";
	 	$winner=-1;
		session_start();
    	$_SESSION['current_player']= $_SESSION['current_player']+1;
    	if($_SESSION['current_player'] == 5){
    		$_SESSION['current_player']=1;
    	}
    	if($_SESSION['current_player'] != 1){
    		//play should return somethingto indicate what move has just happened 
    		$move=play($_SESSION['current_player']);
    		$winner=winner($_SESSION['current_player']);
    		//check if he finished his cardsto announc a winner

    	}
    	//here i should return the current_player, his move
    	$result=[];
    	array_push($result,$_SESSION['current_player']);
    	array_push($result,$move);
    	array_push($result,$_SESSION['avail_one']);
    	array_push($result,$_SESSION['avail_two']);
    	array_push($result,$winner);
    	
    	
	    echo json_encode($result);

	}
	function winner($player){
		$array=[];
		switch ($player) {
			case 2:
				$array=$_SESSION['player2_cards'];
				break;
			case 3:
				$array=$_SESSION['player3_cards'];
				break;
			case 4:
				$array=$_SESSION['player4_cards'];
				break;
			
			default:
				# code...
				break;
		}
		if(sizeof($array) == 0){
			return $player;
		}
		else{
			return -1;
		}

	}

//play function note he should chose which one to play
	function play(){
		session_start();
		$can_play=can_play();
		//echo ($can_play);
		$player1_cards=$_SESSION['player1_cards'];
		$player2_cards=$_SESSION['player2_cards'];
		$player3_cards=$_SESSION['player3_cards'];
		$player4_cards=$_SESSION['player4_cards'];

		$player1_codes=$_SESSION["player1_codes"];
		$player2_codes=$_SESSION["player2_codes"];
		$player3_codes=$_SESSION["player3_codes"];
		$player4_codes=$_SESSION["player4_codes"];
		$current_player=$_SESSION["current_player"];

		if($can_play > -1){//he can play
			//remove it from the player then return its code
			switch ($current_player) {
				case 1:
					$card=$player1_cards[$can_play];
					$code=$card["code"];
					unset($player1_cards,$can_play);
					unset($player1_codes, $card["code"]);
					$_SESSION['player1_cards']=$player1_cards;
					return $code;
					break;
				case 2:
					$card=$player2_cards[$can_play];
					$code=$card["code"];
					unset($player2_cards,$can_play);
					unset($player2_codes, $card["code"]);
					$_SESSION['player2_cards']=$player2_cards;
					return $code;
					break;
				case 3:
					$card=$player3_cards[$can_play];
					$code=$card["code"];
					unset($player3_cards,$can_play);
					//unset($player3_codes, $card["code"]);
					$_SESSION['player3_cards']=$player3_cards;
					return $code;
					break;
				case 4:
					$card=$player4_cards[$can_play];
					$code=$card["code"];
					unset($player4_cards,$can_play);
					unset($player4_codes, $card["code"]);

					$_SESSION['player4_cards']=$player4_cards;
					return $code;
					break;
				
				default:
					# code...
					break;
			}
		}
		else return "";//-1 indicate cant play
	}
	//for debugginng 
	function console_log( $data ) {
		  $output  = "<script>console.log( 'PHP debugger: ";
		  $output .= json_encode(print_r($data, true));
		  $output .= "' );</script>";
		  echo $output;
}
function update_available(){
	$_SESSION["avail_one"]=$_POST["avail_1"];
	$_SESSION["avail_two"]=$_POST["avail_2"];
	echo  $_SESSION["avail_one"].$_SESSION["avail_two"];

}

 ?>