<?
session_name('EFSESSION');
session_start(); 
require_once("connect2EFDB.php");

function countResult($num_of_acts,$output,$chance)
{
	$result = 0;
	for ($i=0; $i < $num_of_acts; $i++) {
		$r = rand(1, 100);
		if($chance >= $r) $result += $output;
	}
	return $result;
}

$game_id = $_SESSION['endlessFarmGameId'];
$login = $_SESSION['endlessFarmLogin']; 
$subType = $_POST["subType"];	
	//-----------SEND ACTION TO DB----------
	if(($subType == "newActivity")&&($login)&&($game_id)){ 
		$query = "SELECT res_gold FROM `game_account` WHERE user_name = '$login'";
		$result = mysqli_query($link,$query);
		$techArray = mysqli_fetch_array($result);
		$game_gold = $techArray['res_gold'];;		

		$workType = $_POST["workType"];		
		$workTime = $_POST["workTime"];	
		$cellX = $_POST["cellX"];
		$cellY = $_POST["cellY"];
		//---Координаты города, из которого отдаются задания. надо потом добавить в базу-----------	
		$officeX = 2;
		$officeY = 4;
		$query = "SELECT cell_type_name FROM `game_map` WHERE x = '$cellX' AND y = '$cellY'"; //Проверка соответствия квадрата заданию
		$result = mysqli_query($link,$query);
		$techArray = mysqli_fetch_array($result);
		$cell = $techArray['cell_type_name'];		
		$query = "SELECT * FROM `actions_of_cells` WHERE action_type_name = '$workType' AND cell_type_name = '$cell'";
		$result = mysqli_query($link,$query);
		$techArray = mysqli_fetch_array($result);
		$actCost = $techArray['cost_per_act'];		
		$valid = mysqli_num_rows($result);
		if($valid){
			$query = "SELECT * FROM `types_of_actions` WHERE action_type_name = '$workType'";
			$result = mysqli_query($link,$query);
			$techArray = mysqli_fetch_array($result);
			$action_output = $techArray['output_per_act'];
			$chance = $techArray['base_chance'];
			$startTime = time();
			$moveTime = (abs($cellX - $officeX) + abs($cellY - $officeY))*2 - 1;
			$finishTime = $startTime + $workTime*1 + $moveTime*1;//время работы
			$finalWorkCost =  $workTime * $actCost + $moveTime; //стоимость работы			
			if(($game_gold - $finalWorkCost)<0) {				
				//echo "<script>location='game.php';</script>";
			} else {
			$game_gold -= $finalWorkCost;
			$workResult = countResult( $workTime,$action_output,$chance); // Результат фарма					
			$query = "INSERT INTO current_activity (activity_id,account_id,action_type_name,activity_start,activity_finish,activity_result)
				VALUES (NULL,'$game_id','$workType','$startTime','$finishTime','$workResult')";
			$result	= mysqli_query($link,$query);
			$query = "UPDATE game_account SET res_gold = '$game_gold' WHERE account_id = '$game_id'";
			$result	= mysqli_query($link,$query);
			$outputJS = "activityArray = [];";
			$query = "SELECT * FROM `current_activity` WHERE account_id = '$game_id' ORDER BY activity_finish ASC";
			$result = mysqli_query($link,$query);
			while ($active = mysqli_fetch_array($result)){
				$interval =  $active['activity_finish'] - time();
				$outputJS.= "activityArray.push(new ActivityTimer(".$interval.",'".$active['action_type_name']."','".$active['activity_id']."'));";
				}
			$outputJS.= "$('#gold').text(".$game_gold.");";
			$outputJS.="TIMERJS.innerHTML='';";
			echo $outputJS;				
			}
		}
	}	
?>