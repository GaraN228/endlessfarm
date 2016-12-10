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
	<meta charset="cp-1251">	
	<title>THE ENDLESS FARM</title>
	<link rel="stylesheet" type="text/css" href="gamecss.css">
</head>
<body>
<div id="contentbox">
	<div id="mainBlock">
		<table id="map">
			<?
			$query = "SELECT * FROM `game_map`";
			$result = mysqli_query($link,$query);			
			while ($map = mysqli_fetch_array($result)){
				$r = $map['x'];
				if($map['x']==1){
					echo "<tr>";
				}
				echo "<td><img src='map/".$map['cell_type_name'].".svg' width='64' height='64'/></td>";
				if($map['x']==5){
					echo "</tr>";
				}
			}
			?>	
			
		</table>			
	</div>
	<div id="resAndAction">
		<p><? echo $login ?></p>
		<p>������: <? echo $game_gold ?></p>
		<p>������: <? echo $game_drova ?></p>
		<p>����: <? echo $game_ruda ?></p>
		<p>���: <? echo $game_food ?></p>		
	</div><br>
	<div id="activeForms">
		�������� �����
	</div>
	<div id="actionLog">
		��� ��������
	</div>

</div>
</body>
</html>
<!--<tr>
				<td>1x1</td>
				<td>1x2</td>
				<td>1x3</td>
				<td>1x4</td>
				<td>1x5</td>
			</tr>
			<tr>
				<td>2x1</td>
				<td>2x2</td>
				<td>2x3</td>
				<td>2x4</td>
				<td>2x5</td>
			</tr>
			<tr>
				<td>3x1</td>
				<td>3x2</td>
				<td>3x3</td>
				<td>3x4</td>
				<td>3x5</td>
			</tr>
			<tr>
				<td>4x1</td>
				<td>4x2</td>
				<td>4x3</td>
				<td>4x4</td>
				<td>4x5</td>
			</tr>
			<tr>
				<td>5x1</td>
				<td>5x2</td>
				<td>5x3</td>
				<td>5x4</td>
				<td>5x5</td>
			</tr>-->