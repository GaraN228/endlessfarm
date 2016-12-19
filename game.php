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
	$_SESSION['endlessFarmGameId'] = $game_id;
	$game_gold = $accInfo['res_gold'];;
	$game_drova = $accInfo['res_Lumber'];
	$game_ruda = $accInfo['res_Ore'];
	$game_food = $accInfo['res_Food'];
	$game_income = $accInfo['res_income'];
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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
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
		//--------------------CREATE LOGS-----------------
			
		$query = "SELECT * FROM game_logs WHERE account_id = '$game_id' ORDER BY log_created DESC LIMIT 0,30";
		$result = mysqli_query($link,$query);
		$outputJS.="$(document).ready(function(){";
		$outputJS.="$('#actionLog').html('";
		while ($techArray = mysqli_fetch_array($result)){
			$outputJS.=$techArray['log_created']."<br>";
			$outputJS.=$techArray['log_text']."<hr>";
		}
		$outputJS.="');";
		$outputJS.="});";
		echo $outputJS;
		?>
	</script>
</head>
<body onload="createMap();createTimers();">	
<table id='new'>
	<tbody>
		<tr>
		<td class="tableHead" id="user" colspan="3"><? echo $login ?></td>
		<tr>
		<tr>
			<td id='mapPlace' colspan="2" rowspan="4">
				<table id="map">
				</table>
			</td>
			<td class="tableHead">
				Ресурсы
			</td>			
		</tr>
		<tr>
			<td>
				<div id="resAndAction">
				<table id="showRestable">					
					<tr>
						<td><img src="respng/coin.png" width="18" height="18"> Золото:</td>
						<td><span id="gold"><? echo $game_gold ?></span></td>
					</tr>
					<tr>
						<td><img src="respng/Lumber.png"  width="18" height="18"> Бревна:</td>
						<td><span id="lumber"><? echo $game_drova ?></span></td>
					</tr>
					<tr>
						<td><img src="respng/Ore.png"  width="18" height="18"> Руда:</td>
						<td><span id="ore"><? echo $game_ruda ?></span></td>
					</tr>
					<tr>
						<td><img src="respng/Food.png"  width="18" height="18"> Еда:</td>
						<td><span id="food"><? echo $game_food ?></span></td>
					</tr>
				</table>				
				</div>
			</td>
		</tr>
		<tr>
			<td class="tableHead">
				События
			</td>
		</tr>		
		<tr>
			<td>
			<div id="activityList">
				<table id = 'timerTable'>
				</table>
			</div>	
			</td>		
		</tr>
		<tr>
			<td class="tableHead">
				Действие 
			</td>
			<td class="tableHead">
				Информация
			</td>
			<td class="tableHead">
				Отчеты
			</td>
		</tr>
		<tr>
			<td>
				<div id="activeForms">
				</div>	
			</td>
			<td>
				<div id="activeFormsInfo">
					
				</div>
			</td>
			<td>
				<div id="actionLog">
				</div>
			</td>
		</tr>
	</tbody>		
</table>
<div id="JS"></div>
<div id="TIMERJS"></div><br>
</body>
</html>
