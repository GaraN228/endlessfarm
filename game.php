<?php require_once("connect2EFDB.php");
session_name('EFSESSION');
session_start();
$login = $_SESSION['endlessFarmLogin'];
if($login){
	$query = "SELECT * FROM `game_account` WHERE user_name = '$login'";
	$result = mysqli_query($link,$query);
	$accInfo = mysqli_fetch_array($result);
	$game_id = $accInfo['account_id'];
	$game_gold = $accInfo['res_gold'];
	$game_drova = $accInfo['res_drova'];
	$game_ruda = $accInfo['res_ruda'];
	$game_food = $accInfo['res_food'];
	$game_income = $accInfo['res_income'];
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
		var cellArray = [];
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
			$query = "SELECT action_name FROM `actions_of_cells` WHERE cell_type_name = '$cell'";		
			$result_j = mysqli_query($link,$query);
			echo "var act=[];";
			while ($actions = mysqli_fetch_array($result_j)) {
				echo "act.push('".$actions['action_name']."');";
			}
		echo "cellArray.push(new MapCell('".$map['x']."','".$map['y']."','".$cell."',act));";	
		}
		?>
	</script>
</head>
<body onload="createMap();">
<div id="contentbox">
	<div id="mainBlock">
		<table id="map">			
		</table>			
	</div>
	<div id="resAndAction">
		<p><? echo $login ?></p>
		<p>Золото: <? echo $game_gold ?></p>
		<p>Бревна: <? echo $game_drova ?></p>
		<p>Руда: <? echo $game_ruda ?></p>
		<p>Еда: <? echo $game_food ?></p>		
	</div><br>
	<div id="activeForms">
		Активные формы<br>
	</div>
	<div id="actionLog">
		Лог действий<br>
				
	</div>
</div>
</body>
</html>
