<?php require_once("connect2EFDB.php");
session_name('EFSESSION');
session_start();

function lookForOverlap($mail,$login,$link)
{
	$mail_query = "SELECT user_email FROM users WHERE user_email='$mail'";
	$mail_overlap = mysqli_query($link,$mail_query);
	$take_mail = mysqli_fetch_array($mail_overlap);
	if($take_mail['user_email']){
		$answer = 'mail_overlap';
		return $answer;
	}
	$login_query = "SELECT user_name FROM users WHERE user_name='$login'";
	$login_overlap = mysqli_query($link,$login_query);
	$take_login = mysqli_fetch_array($login_overlap);
	if($take_login['user_name']){
		$answer = 'login_overlap';
		return $answer;
	}
	return 'no_overlap';
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>THE ENDLESS FARM</title>
	<meta charset="cp-1251">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" type="text/css" href="EndlessFarm.css">
<script src = "EndlessFarm.js" defer></script>
</head>
<body onload="
startSessionStorage();
setLoginWarningMessage(sessionStorage['newWarningMessage']);
setRegWarningMessage(sessionStorage['newWarningMessage']);
controlEnterAndExit();">

<div id="content">
<header>
<h1>THE ENDLESS FARM</h1>	
</header>	
<nav>
<ul id="menu">
	<li onclick="showMainPage();hideLoginWarningMessage();hideRegWarningMessage();">Главная</li>
	<li onclick="showForum();">Форум</li>
	<li id="regButton" onclick="pressRegCabBut(sessionStorage['isLogin']);hideLoginWarningMessage();hideRegWarningMessage();">Регистрация</li>
	<li id="enterButton" onclick="pressEnterExitBut(sessionStorage['isLogin']);hideLoginWarningMessage();hideRegWarningMessage();">Вход</li>
</ul>
<div id="currentUser" style="display: none;">
	Привет, usernamе!
</div>	
</nav>
<article id="contentbox">

<div id="main" style="display: none;">
	<h2>Последние новости</h2>
	<p>Идет работа над проектом!</p>
	<p>Что уже созданно:</p>
	<ol reversed="">
	<li>Много чего добавлено, впадлу дописывать.</li>
	<li>Добавлен функционал кнопки Вход.</li>
	<li>Настроен стиль кнопок с помощью <b>hover</b>.</li>
	<li>Создан txt файл для резервного хранения текста на кириллице.</li>
	<li>Создан Javascript для загрузки главной страницы.</li>
	<li>Добвлена тень от фона.</li>
	<li>Добавлен скроллбар в блок <b>article</b>.</li>
	<li>Добавлен функционал кнопки Вход.</li>
	<li>Настроен стиль кнопок с помощью <b>hover</b>.</li>
	<li>Создан txt файл для резервного хранения текста на кириллице.</li>
	<li>Создан Javascript для загрузки главной страницы.</li>
	<li>Добвлена тень от фона.</li>
	<li>Добавлен скроллбар в блок <b>article</b>.</li>
	</ol>	
</div>
<div id="login" style="display: none;">
	<h2>Авторизация</h2>
	<form id="log" method="POST" action="index.php" name="enter">
		<p>Введите логин:<br>
		<input id="enterName" type="text" name="login" onfocus="hideLoginWarningMessage();" value="GaraN" maxlength="15"></p>
		<p>Введите пароль:<br>
		<input id="enterPass" type="password" name="pass" onfocus="hideLoginWarningMessage();"></p>
		<input type="hidden" name="formType" value="loginForm">
		<p><input type="submit" name="submit" value="Войти" onclick="return validationEnter()"></p>
		<p id="loginWarningMessage" onclick="hideLoginWarningMessage();" style="display: none;"></p>
	</form>
</div>
<div id="regist" style="display: none;">
	<h2>Регистрация</h2>
	<form id="reg" method="POST" action="index.php" name="reg">
	Введите логин:<br>
	<input id="regLogin" type="text" name="regLogin" maxlength="15" onfocus="hideRegWarningMessage();"><div onclick="showFormTip('regLogin')">?</div><br>
	Введите e-mail:<br>
	<input id="regEmail" type="text" name="regEmail" onfocus="hideRegWarningMessage();"><div onclick="showFormTip('regEmail')">?</div><br>
	Введите пароль:<br>
	<input id="regPass1" type="password" name="regPass1" maxlength="15" onfocus="hideRegWarningMessage();"><div  onclick="showFormTip('regPass1')">?</div><br>
	Подтвердите пароль:<br>
	<input id="regPass2" type="password" name="regPass2" onfocus="hideRegWarningMessage();"><br>
	<input type="hidden" name="regTime" value="<?php echo date('Y-m-d H:i:s');?>">
	<input type="hidden" name="formType" value="regForm">
	<p><input type="submit" name="submit" value="Создать" onclick="return validationReg()"></p>	
	</form>
	<div id="regDiscription" style="display: block;">
	</div>
	<div id="regWarningMessage" onclick="hideRegWarningMessage();" style="display: none;"></div>
</div>
<div id="forum" style="display: none;">

		<?php
		$query = "SELECT * FROM blog_notes ORDER BY note_created DESC LIMIT 0,30";
	 	$allNotes = mysqli_query($link,$query);
	 	$notesNumber = mysqli_num_rows($allNotes);
	 	if($notesNumber){
	 		?>
	 		<div id="forumList" style="position: relative;">
	 		<table id="notesTableHeader">
			<tr>
			<th style="width: 320px;">Тема</th>
			<th style="width: 140px;">Автор</th>
			<th style="width: 120px;">Дата</th>
			</tr>
			</table>
			<div id="forumContent">
			<table id="notesTableBody">				
			<?				
		 	while ($note = mysqli_fetch_array($allNotes)) {
		 		$arg = $note['note_id'];
		 		$shortTime = date('d-m-Y',strtotime($note['note_created']));
		 		echo "<tr class = 'noteTR'>";
				echo "<td><div class='noteTitle' onclick = 'showForumPost($arg)'>".$note['note_title']."</div></td><td><div class='noteAuthor'>".$note['user_name']."</div></td><td><div class='noteDate'>".$shortTime."</div></td>";
				echo "</tr>";
		 	}
		 	?>
		 	</table>
		 	</div>
		 	<?
		 } else {
		 	?>
		 	<div id="forumList" style="height: 350px;position: relative;text-align: center;">Нет записей
		 	<?
		 }


	?>
	<br>
	<div id="newMessageButton" class="bigButton" onclick="forumList.style.display = 'none';newMesForm.style.display = 'block';width='250px;'">Добавить новую запись</div>
	</div>
	<div id="newMesForm" style="display: none;">
		<h2>Новая запись</h2>
		<form id="newMes" method="POST" action="index.php" name="newMes">
			Тема сообщения:<br>
			<input id="mesTitle" type="text" name="mesTitle"><br>
			Текст сообщения:<br>
			<textarea id="newMesTA" name="mesText"></textarea>
			<input type="hidden" name="mesPostTime" value="<?php echo date('Y-m-d H:i:s');?>">
			<input type="hidden" name="formType" value="newMessage">
			<p><input type="submit" name="submit" value="Создать" onclick="">
			<input type="button" name="cancel" value="Отмена" onclick="newMesForm.style.display = 'none';forumList.style.display = 'block';"></p>		
		</form>
	</div>
	<div id="forumMesDiv" style="display: none;">
		<div>
			<?
			$currentForumNote = $_GET['forum_note_id'];
			if($currentForumNote){
				$query = "SELECT * FROM `blog_notes` WHERE note_id = '$currentForumNote'";
				$result = mysqli_query($link,$query);
				$note = mysqli_fetch_array($result);
				if(($note['user_name']==$_SESSION['endlessFarmLogin'])||($_SESSION['sessionAccess']=='a')){
					$deletNote = '[удалить]';
				}
				echo "<div class='noteHead'>".$note['note_title']."<br>";				
				echo "<span style ='font-size:.75em;'>".$note['user_name']."</span><span style ='font-size:.7em;margin-left:10px;'>".$note['note_created'];
				echo "<span id='deletNoteSpan' onclick = 'deleteNoteFormStyle();'> ".$deletNote."</span></span></div>";
				echo "<div class='noteBody'>";
				?>
				<form id="deleteNoteForm" method="POST" action="index.php" style="display: none;">
					Удалить заметку?
					<input class="subCommentButton" type="submit" name="submit" value="Удалить" onclick="">
					<input type="hidden" name="delet_id" value="<?echo $currentForumNote;?>">
					<input type="hidden" name="formType" value="deletNote">
					<input class="subCommentButton" type="button" name="cancel" value="Отмена" onclick="deleteNoteForm.style.display = 'none'">
				</form>	
				<?				
				echo "<div style='padding-left:5px;white-space: pre-wrap;'>".$note['note_body']."</div>";
				//Грузим комменты
				$query = "SELECT * FROM `note_comments` WHERE note_id = '$currentForumNote'";
				$result = mysqli_query($link,$query);
				$comNumber = mysqli_num_rows($result);
				if($comNumber){
					echo "<div id='commentsInfo'>Комментарии (".$comNumber.")</div>";					
				} else {
					echo "<div id='commentsInfo'>Нет Комментариев</div>";
				}
				?>	
				<form id="newCommentForm" method="POST" action="index.php?forum_note_id=<? echo $currentForumNote; ?>" style="display: none;">
					<textarea id="newCommentTxtArea" name="comText" placeholder="Введите текст комментария..."></textarea><br>
					<input type="hidden" name="commentPostTime" value="<?php echo date('Y-m-d H:i:s');?>">
					<input type="hidden" name="formType" value="newComment">
					<input class="subCommentButton" type="submit" name="submit" value="Комментировать" onclick="">
				</form>
				<?				
				while($comments = mysqli_fetch_array($result)){
					echo "<div class='commentHead'><span style ='font-size:.75em;'>".$comments['user_name']."</span><span style ='font-size:.7em;margin-left:10px;'>".$comments['com_created']."</span></div>";
					echo "<div style='padding-left:5px;white-space: pre-wrap;font-size:.8em;'>".$comments['com_body']."</div>";
				}				
				echo "</div>"
				?>
				<script type="text/javascript">forumList.style.display = "none";forumMesDiv.style.display = "block";
				</script>
				<?
			}
			?>			
		</div>

		<div id="newCommentButton" class="bigButton"  onclick="newCommentForm.style.display = 'block';this.style.display = 'none';newCommentTxtArea.focus();"  style="margin-top: 10px;">Комментировать</div>
		<div class="bigButton" onclick="location = 'index.php'" style="margin-top: 10px;display: inline-block;">Назад</div>
	</div>
		
</div>

<div id="regSuccess" onclick="showMainPage()" style="display:none;">
	<h2>Регистрация прошла успешно!</h2>	
</div>
<div id="cabinet" style="display:none;">
	<h2>Тут будет кабинет</h2>	
</div>
</article>
<footer>
<h2>FOOTER</h2>	
</footer>
</div>
</body>
</html>
<?php //ВХОД В СИСТЕМУ
if($_SESSION['endlessFarmLogin']){
	$login = $_SESSION['endlessFarmLogin'];
	echo "<script>sessionStorage['isLogin'] = 'nowLogged';sessionStorage['userLogin'] = '$login' ;</script>";
}
$login = $_POST['login'];
	$pass = $_POST['pass'];
	if(($login)&&($pass)){
	$query = "SELECT * FROM users WHERE user_name='$login'";
	$user_info = mysqli_query($link,$query);
	$info = mysqli_fetch_array($user_info);
	if ($info['user_pass']==$pass){
		echo "<script>sessionStorage['userLogin'] ='".$info['user_name']."';</script>";
		$_SESSION['endlessFarmLogin'] = $info['user_name'];
		$_SESSION['sessionAccess'] = $info['user_access'];
		echo "<script>sessionStorage['isLogin'] = 'nowLogged' ; sessionStorage['currentMenuButton'] = 'main' ; location = 'index.php';</script>";
		} else {
			echo "<script>sessionStorage['newWarningMessage'] = 'Неверное имя пользователя или пароль!';location = 'index.php';</script>";
			}
	}
$formType = $_POST['formType'];
$regLogin = $_POST['regLogin'];
$regEmail = $_POST['regEmail'];
$regPass1 = $_POST['regPass1'];
$regTime = $_POST['regTime'];
$patMail = '/^([a-z0-9_\.\-]+)@([a-z0-9]+)\.([a-z]{2,6})$/i';
$patLogin = '/^[a-z0-9\-]{3,15}$/i';
$patPass = '/^.{5,15}$/';
$patTime = '/[^A-ZА-Я\-\s]/i';

if($formType == 'regForm') {
	if((preg_match($patMail, $regEmail))&&(preg_match($patLogin, $regLogin))&&(preg_match($patPass, $regPass1))&&(preg_match($patTime, $regTime))){
	$check = lookForOverlap($regEmail,$regLogin,$link);
	if($check == 'no_overlap'){
		//CREATE USER
		$query = "INSERT INTO users (user_id,user_name,user_pass,user_email,user_reg_time)
				VALUES (NULL,'$regLogin','$regPass1','$regEmail','$regTime')";
		$result = mysqli_query($link,$query);
		//CREATE ACC
		$query = "INSERT INTO game_account (account_id,user_name,res_gold,res_drova,res_ruda,res_food,income)
				VALUES (NULL,'$regLogin','1000','0','0','0','0')";
		$result = mysqli_query($link,$query);

		echo "<script>sessionStorage['currentMenuButton'] = 'regSuc' ; location = 'index.php';</script>";
			} 
	if($check == 'mail_overlap'){
		echo "<script>sessionStorage['newWarningMessage'] = 'Эта почта уже используется!' ;location = 'index.php';</script>";
			} 
	if($check == 'login_overlap'){
		echo "<script>sessionStorage['newWarningMessage'] = 'Пользователь с таким именем уже существует!' ;location = 'index.php';</script>";
			}		
	}
	else {
		//echo "<script>alert(sessionStorage['currentMenuButton'])</script>";
		echo "<script>sessionStorage['newWarningMessage'] = 'Ошибка ввода данных.' ;location = 'index.php';</script>";
	}
	}

$mesTitle = strip_tags($_POST['mesTitle']);
$mesText = strip_tags($_POST['mesText']);
$mesPostTime = $_POST['mesPostTime'];	
if($formType == 'newMessage'){
	//echo "<script>alert('!!!!!!!!!!!!!!!!')</script>";
	$login = $_SESSION['endlessFarmLogin'];
	//echo "<script>alert('$login')</script>";
	if(($mesTitle)&&($mesText)&&($login)){
		$query = "INSERT INTO blog_notes (note_id,user_name,note_title,note_body,note_created)
				VALUES (NULL,'$login','$mesTitle','$mesText','$mesPostTime')";
		$result = mysqli_query($link,$query);
		echo "<script>sessionStorage['currentMenuButton'] = 'forum' ; location = 'index.php';</script>";
	}
}
$comText = strip_tags($_POST['comText']);
$commentPostTime = $_POST['commentPostTime'];

if($formType == 'newComment'){
	//echo "<script>alert('!!!!!!!!!!!!!!!!')</script>";
	$login = $_SESSION['endlessFarmLogin'];
	//echo "<script>alert('$login')</script>";
	if(($comText)&&($login)&&($currentForumNote)){
		$query = "INSERT INTO note_comments (comment_id,user_name,com_created,com_body,note_id)
				VALUES (NULL,'$login','$commentPostTime','$comText','$currentForumNote')";
		$result = mysqli_query($link,$query);
		echo "<script>sessionStorage['currentMenuButton'] = 'forum' ; location = 'index.php?forum_note_id=$currentForumNote';</script>";
}
//if($formType == 'destroyForm'){
//}	
}
$delet_id = $_POST['delet_id'];
if($formType == 'deletNote'){
	$query = "DELETE FROM blog_notes WHERE note_id = '$delet_id'";
	$result = mysqli_query($link,$query);
	echo "<script>location = 'index.php';</script>";
}
?>

