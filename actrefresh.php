<?
session_name('EFSESSION');
session_start(); 
require_once("connect2EFDB.php");

$game_id = $_SESSION['endlessFarmGameId'];
$login = $_SESSION['endlessFarmLogin']; 
$closeActId = $_POST['harv'];
$outputJS = "";
	//-------ЗАВЕРШЕНИЕ ДЕЙСТВИЯ---------------------------
if(($closeActId)&&($login)&&($game_id)){
		$query = "SELECT * FROM current_activity WHERE activity_id = '$closeActId'";
		$result = mysqli_query($link,$query);
		$techArray = mysqli_fetch_array($result);
		if($techArray['activity_finish']<time()){
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
			$query = "SELECT * FROM `game_account` WHERE user_name = '$login'";
			$result = mysqli_query($link,$query);
			$techArray = mysqli_fetch_array($result);
			$game_drova = $techArray['res_Lumber'];
			$game_ruda = $techArray['res_Ore'];
			$game_food = $techArray['res_Food'];
			$outputJS = "$('#lumber').text(".$game_drova.");";
			$outputJS .= "$('#ore').text(".$game_ruda.");";
			$outputJS .= "$('#food').text(".$game_food.");";
			$log_created = date('Y-m-d H:i:s',(time()-3600));
			switch ($sendRes) {
				case 'res_Ore':
					$log_text = "Добыто ".$workResult." руды.";
					break;				
				case 'res_Food':
					$log_text = "Добыто ".$workResult." еды.";
					break;
				case 'res_Lumber':
					$log_text = "Добыто ".$workResult." дерева.";
					break;	
			}
			$query = "INSERT INTO game_logs (log_id,account_id,log_text,log_created)
			VALUES (NULL,'$game_id','$log_text','$log_created')";
			$result = mysqli_query($link,$query);
			$query = "SELECT * FROM game_logs WHERE account_id = '$game_id' ORDER BY log_created DESC LIMIT 0,30";
			$result = mysqli_query($link,$query);
			$outputJS.="$('#actionLog').html('";
			while ($techArray = mysqli_fetch_array($result)){
				$outputJS.=$techArray['log_created']."<br>";
				$outputJS.=$techArray['log_text']."<hr>";
			}
			$outputJS.="');";
			$outputJS.="JS.innerHTML='';";
			echo $outputJS;		
		}		
				
	}
?>