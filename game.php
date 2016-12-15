<?php require_once("connect2EFDB.php");
session_name('EFSESSION');
session_start();
function countResult($num_of_acts,$output,$chance)
{
	$result = 0;
	for ($i=0; $i < $num_of_acts; $i++) {
		$r = rand(1, 100);
		if($chance >= $r) $result += $output;
	}
	return $result;
}

$login = $_SESSION['endlessFarmLogin'];
//--------------LOAD ACCOUNT-----------------------------------------
if($login){
	$query = "SELECT * FROM `game_account` WHERE user_name = '$login'";
	$result = mysqli_query($link,$query);
	$accInfo = mysqli_fetch_array($result);
	$game_id = $accInfo['account_id'];
	$game_gold = $accInfo['res_gold'];
	$game_drova = $accInfo['res_Lumber'];
	$game_ruda = $accInfo['res_Ore'];
	$game_food = $accInfo['res_Food'];
	$game_income = $accInfo['res_income'];
	

	$subType = $_POST["subType"];	
	//-----------SEND ACTION TO DB----------
	if($subType == "newActivity"){ 
		$workType = $_POST["workType"];		
		$workTime = $_POST["workTime"];	
		$cellX = $_POST["cellX"];
		$cellY = $_POST["cellY"];
		//---Координаты города, из которого отдаются задания. надо потом добавить в базу-----------	
		$officeX = 2;
		$officeY = 4;
		$query = "SELECT cell_type_name FROM `game_map` WHERE x = '$cellX' AND y = '$cellY'"; //Проверка соответствия квадрата заданию
		$result = mysqli_query($link,$query);
		$cells = mysqli_fetch_array($result);
		$cell = $cells['cell_type_name'];		
		$query = "SELECT * FROM `actions_of_cells` WHERE action_type_name = '$workType' AND cell_type_name = '$cell'";
		$result = mysqli_query($link,$query);
		$actArr = mysqli_fetch_array($result);
		$actCost = $actArr['cost_per_act'];		
		$valid = mysqli_num_rows($result);
		if($valid){
			$query = "SELECT * FROM `types_of_actions` WHERE action_type_name = '$workType'";
			$result = mysqli_query($link,$query);
			$farm_vars = mysqli_fetch_array($result);
			$action_output = $farm_vars['output_per_act'];
			$chance = $farm_vars['base_chance'];
			$startTime = time();
			$moveTime = (abs($cellX - $officeX) + abs($cellY - $officeY))*2 - 1;
			$finishTime = $startTime + $workTime*60 + $moveTime*60;//время работы
			$finalWorkCost =  $workTime * $actCost + $moveTime; //стоимость работы
			$game_gold -= $finalWorkCost;
			$workResult = countResult( $workTime,$action_output,$chance); // Результат фарма					
			$query = "INSERT INTO current_activity (activity_id,account_id,action_type_name,activity_start,activity_finish,activity_result)
				VALUES (NULL,'$game_id','$workType','$startTime','$finishTime','$workResult')";
			$result	= mysqli_query($link,$query);
			$query = "UPDATE game_account SET res_gold = '$game_gold' WHERE account_id = '$game_id'";
			$result	= mysqli_query($link,$query);
			echo "<script>location='game.php';</script>";
		}
	}	
	$closeActId = $_GET['harv'];
	//-------ЗАВЕРШЕНИЕ ДЕЙСТВИЯ---------------------------
	if($closeActId){
		$query = "SELECT * FROM current_activity WHERE activity_id = '$closeActId'";
		$result = mysqli_query($link,$query);
		$techArray = mysqli_fetch_array($result);
		if($techArray['activity_finish']>time()) echo "<script>location='game.php';</script>"; 
		$workType = $techArray['action_type_name'];
		//echo "workType:".$workType."<br>";
		$workResult = $techArray['activity_result'];
		$sendRes = "res_".substr($workType, 5);
		$query = "SELECT ".$sendRes." FROM game_account WHERE account_id = '$game_id'";
		$result = mysqli_query($link,$query);
		$techArray = mysqli_fetch_array($result);
		$sendValue = $techArray[$sendRes] + $workResult; 
		$query = "UPDATE game_account SET ".$sendRes." = '$sendValue' WHERE account_id = '$game_id'";
		$result = mysqli_query($link,$query);
		$query = "DELETE FROM current_activity WHERE activity_id = '$closeActId'";
		$result = mysqli_query($link,$query);
		echo "<script>location='game.php';</script>";
	}	
} else {
	echo "<script>location = 'index.php?status=unlog';</script>";
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<title>THE ENDLESS FARM</title>
	<link rel="stylesheet" type="text/css" href="gamecss.css">
	<script src = "gamejs.js" defer></script>
	<script>
		//GLOBALS
		var lastMapCell = new Object;
		var currentWorkTime = 1;
		var currenCellNumber = 0;
		var cellArray = [];
		var activityArray = [];
		class MapCell{
			constructor(x,y,cellType,actions){
			this.x = x;
			this.y = y;
			this.cellType = cellType;
			this.actions = actions;
		}
		pr(){
			var result = this.x+"<br>"+this.y+"<br>"+this.cellType+"<br>";
			for (var i = 0; i < this.actions.length; i++) {
				result += this.actions[i] + "<br>";
			}
		return result;	
		}
		}
		<?
		$query = "SELECT * FROM `game_map`";
		$result = mysqli_query($link,$query);			
		while ($map = mysqli_fetch_array($result)){
			$cell = $map['cell_type_name'];
			$query = "SELECT * FROM `actions_of_cells` WHERE cell_type_name = '$cell'";		
			$result_j = mysqli_query($link,$query);
			echo "var act=[];";
			while ($actions = mysqli_fetch_array($result_j)) {
				echo "act.push('".$actions['action_type_name']."');";
				echo "act.push('".$actions['action_name']."');";
			}
		echo "cellArray.push(new MapCell('".$map['x']."','".$map['y']."','".$cell."',act));";	
		}
		?>
		class ActivityTimer{
			constructor(finish,resType,id){
				this.finish = finish;
				this.resType = resType;
				this.id = id;
			}

		}
		<?
		//---------SHOW THE ACTIVITY-------------------------
		$query = "SELECT * FROM `current_activity` WHERE account_id = '$game_id' ORDER BY activity_finish ASC";
		$result = mysqli_query($link,$query);
		while ($active = mysqli_fetch_array($result)){
			$interval =  $active['activity_finish'] - time();
			echo "activityArray.push(new ActivityTimer(".$interval.",'".$active['action_type_name']."','".$active['activity_id']."'));";
		}
		?>
	</script>
</head>
<body onload="createMap();createTimers();">
<div id="contentbox">
	<div id="mainBlock">
		<table id="map">			
		</table>			
	</div>
	<div id="resAndAction">
		<p><b><? echo $login ?></b><br>
		<img src="respng/coin.png" width="18" height="18"> Золото: <? echo $game_gold ?><br>
		<img src="respng/Lumber.png"  width="18" height="18"> Бревна: <? echo $game_drova ?><br>
		<img src="respng/Ore.png"  width="18" height="18"> Руда: <? echo $game_ruda ?><br>
		<img src="respng/Food.png"  width="18" height="18"> Еда: <? echo $game_food ?></p>
		<div id="activityList">
			<table id = 'timerTable'>
			</table>				
		</div>	
	</div><br>
	<div id="activeForms">
		Активные формы<br>
	</div>
	<div id="activeFormsInfo">
		Информация<br>
	</div>
	<div id="actionLog">
		Лог действий<br>
				
	</div>
</div>
</body>
</html>
