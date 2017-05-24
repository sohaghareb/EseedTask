let current_player=1;
let avail_one=-1;
let avail_two=-1;

let player1_codes=[];
let player2_codes=[];
let player3_codes=[];
let player4_codes=[];

$(document).ready(function() {
	// var current_player=1;
	// var avail_one=-1;//not zero coz some of domino has zero onthe side 
	// var avail_two=-1;

	// var player1_codes=[];
	// var player2_codes=[];
	// var player3_codes=[];
	// var player4_codes=[];

	$.ajax({
	    url:"Engine.php",  
	    success:function(data) {
	    console.log('players are',data); 
	     $.each(data, function(index, val) {
				        	add_cards(index+1,val);
				        	switch (index) {
							    case 0:
							        player1_codes=val;
							        break; 
							    case 1:
							         player2_codes=val;
							        break; 
							     case 2:
							      	player3_codes=val;
							     	break;
							     case 3:
							      	player4_codes=val;
							     	break;
							    default: 
							        text = "Looking forward to the Weekend";
							}
				        });

		add_img_listner();
		

    },
    dataType:"json"
  });
	$(this).find('.next').click(function(event) {
		console.log('in the button event');
		$.ajax({
    	url:"Engine.php?action=nextMove",  
    	success:function(data) {
        console.log('after calling next move ',data[0]); //this is the current player 
     //    $result[0]=$_SESSION['current_player'];
    	// $result[1]=$move;
    	// $result[2]=$_SESSION['avail_one'];
    	// $result[3]=$_SESSION['avail_two'];
    	// $result[4]=$winner;
        current_player=data[0];
        if(data[1] ==""){
        	alert("Note that player "+current_player+" Doesnthave a move");
        }
        else{
        	//I should delete this move from the panel of that player
        	var oldPlayer=current_player-1;
        	if(oldPlayer == 0){
        		oldPlayer=4;// the last player is 4
        	}
        	let array=[];
        	switch(oldPlayer){
        		case 2: remove_from_array(data[1],player1_codes);
        				array=player1_codes;
        				break;
        		case 3:remove_from_array(data[1],player2_codes);
        				array=player2_codes;
        				break;
        		case 4: remove_from_array(data[1],player3_codes);
        				array=player3_codes;
        				break;
        		default:break;
        	}
        	update_player_view(oldPlayer,array);


        }
        avail_one=data[2];
        avail_two=data[3];

        if(data[4] != -1){
        	alert("Player"+data[4]+" Won the Game :) You are a looser ");

        }


    },
    dataType:"json"

  });
	});

	

	
});

function add_cards(player, cards){
	
	var player_div=$(document).find('.player'+player);
	//console.log("player div  ",player_div);
	$.each(cards, function(index, val) {
			        	player_div.append("<img src=img/"+val+".png code="+val+" class=player"+player+"_img> ");
			        });
}

function check_validity(code,avail_one,avail_two){
	if(code.indexOf(avail_one) > -1  || code.indexOf(avail_two) > -1 || avail_two == -1 || avail_one ==-1 ){
		return true;
	}
	return false;
}
function player1_play(code ,avail_one,avail_two,current_player){
	//check the turn
	var result=[];
	if(current_player == 1 ){
		if(check_validity(code,avail_one,avail_two)){
			var card_left= parseInt(code.charAt(0));
			var card_right=parseInt(code.charAt(1));
			if(avail_one==-1){
				//this is the first play
				avail_one=card_left;
				avail_two=card_right;
			}
			else if(card_left== avail_one){
				avail_one=card_right;
			}
			else if(card_left== avail_two){
				avail_two=card_right;
			}
			else if(card_right == avail_one){
				avail_one=card_left;
			}
			else {//card_right==avail_two
				avail_two=card_left;
			}
			result[0]=avail_one;
			result[1]=avail_two;

		}else{
			alert("You dont have a move click next to continue");
		}
	}
	else{
		console.log("it is not ur turn");
		alert("It is not your turn Click next to continue");
	}
	return result;
}

function remove_from_array(item,array){
	var index = $.inArray(item, array);
	if (index != -1) {
    	array.splice(index, 1);
	}
}

function  update_player_view(player, list){
	console.log('delete the player1 content',$(document).find(('.player'+player)));
	$(document).find(('.player'+player)).empty();
	//add cards
	add_cards(player,list);

}

function update_avail_text(text1,text2){
	$(document).find('.avail_one').text(''+text1);
	$(document).find('.avail_two').text(''+text2);
}

function add_img_listner(){
		$(document).find('.player1_img').click(function(event) {
			//console.log('domino card click event ',$(this).attr('code'));
			var result=player1_play($(this).attr('code'), avail_one,avail_two,current_player);
			if(result.length > 0){
				avail_one=result[0];
				avail_two=result[1];
				console.log('available after updates are ',avail_one,avail_two);
				current_player++;//to prevent player from playing twice in a row
				// i should delete the domino card from front and back end
				//delet from the front
				playe1_codes=remove_from_array($(this).attr('code'),player1_codes);
				if(player1_codes.length == 0){//announce a winner 
					alert("You Win :) ")
				}
				//update the view
				console.log('after deleting from player1 list',player1_codes);
				update_player_view(1,player1_codes);
				update_avail_text(avail_one,avail_two);
				add_img_listner();
				//update the available in the back end 
				$.ajax({
					type:"POST",
			    	url:"Engine.php?action=update_available",  
			    	data: { avail_1: avail_one,avail_2: avail_two },
			    	success:function(data) {
				        console.log('updated the available in the backend ',data); //this is the current player 
				        


    }
  });
			}
	});
}