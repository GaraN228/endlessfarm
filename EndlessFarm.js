function showFormTip(id) {
	switch(id){

	case "regLogin":
		regDiscription.innerHTML = "<br>����� ����� ���� �� 3 �� 15 ��������.�������� ��������� ����� � ����� ��������, ����� � �����.";
		break;
	case "regEmail":
		regDiscription.innerHTML = "<br>������� � ��� ���� ����������� ����������� �����.";
		break;
	case "regPass1":
		regDiscription.innerHTML = "<br>������ ������ ���� �� 5 �� 15 ��������.";
		break;
	}
}
function controlEnterAndExit() {
	if (sessionStorage['isLogin'] == 'nowLogged'){
		enterButton.innerText = "�����";
		regButton.innerText = "�������";
		currentUser.style.display = "block";
		currentUser.innerHTML = "������ , " + sessionStorage['userLogin']+"!"+'<a href="game.php" target="_blank">[������]</a>';
		newMessageButton.style.display = "block";
		newCommentButton.style.display = "inline-block";
		return;
	}
	if ((sessionStorage['isLogin'] == 'nowUnlogged')||(!sessionStorage['isLogin'])){
		enterButton.innerText = "����";
		regButton.innerText = "�����������";
		currentUser.style.display = "none";
		sessionStorage['userLogin'] = "";
		newMessageButton.style.display = "none";
		newCommentButton.style.display = "none";
	}
}
function pressEnterExitBut(logStatus) {
	if ((logStatus == 'nowUnlogged')||(!logStatus)) {
		showLogPage();
		return;
		}
	if (logStatus == 'nowLogged')  {
		sessionStorage['isLogin'] = 'nowUnlogged';
		sessionStorage["currentMenuButton"] = "main";
		location = "unloguser.php";
		}
}
function hideLoginWarningMessage(){
	//alert("!!!!!");
	loginWarningMessage.style.display = "none";
	sessionStorage['newWarningMessage'] = "";
}
function hideRegWarningMessage(){
	//alert("!!!!!");
	regWarningMessage.style.display = "none";
	sessionStorage['newWarningMessage'] = "";
}
function setLoginWarningMessage(wm){
	if (wm){
		loginWarningMessage.innerText = wm;
		loginWarningMessage.style.display = "block";
	}
}
function setRegWarningMessage(wm){
	if (wm){
		regWarningMessage.innerText = wm;
		regWarningMessage.style.display = "block";
	}
}
function setAllToNone(){
		main.style.display = "none";
		login.style.display = "none";
		regist.style.display = "none";
		forum.style.display = "none";
		regSuccess.style.display = "none";
		cabinet.style.display = "none";
}
function showMainPage() {
		setAllToNone();
		main.style.display = "block";
		sessionStorage["currentMenuButton"] = "main";
		//sessionStorage['isLogin'] = "";
		//alert(sessionStorage["currentMenuButton"]);
}
function showLogPage() {
		setAllToNone();
		login.style.display = "block";
		sessionStorage["currentMenuButton"] = "login";
}
function showRegPage() {
		setAllToNone();
		regist.style.display = "block";
		sessionStorage["currentMenuButton"] = "regist";	
}
function showCabinet() {
		setAllToNone();
		cabinet.style.display = "block";		
		sessionStorage["currentMenuButton"] = "cabinet";		
}
function showRegSuccess() {
		setAllToNone();
		regSuccess.style.display = "block";
		sessionStorage["currentMenuButton"] = "regSuc";		
}
function showForum() {
		setAllToNone();
		forum.style.display = "block";
		sessionStorage["currentMenuButton"] = "forum";	
}
function showForumPost(postNumber) {
	location = "index.php?forum_note_id="+postNumber+"";
}


function pressRegCabBut(logStatus) {
	if ((logStatus == 'nowUnlogged')||(!logStatus)) {
		showRegPage();
		return;
	}
	if (logStatus == 'nowLogged')  {
		showCabinet();
		return;
	}
}
function validationEnter() {
			
			if ((!enterName.value)||(!enterPass.value)) {
				setLoginWarningMessage("��������� ��� ����!");
				return false
			}

			
			return true;			
}
function validationReg() {
		var patternMail = /^([a-z0-9_\.\-]+)@([a-z0-9]+)\.([a-z]{2,6})$/i;
		var patternLogin = /^[a-z0-9\-]{3,15}$/i;
		var patternPass = /^.{5,15}$/;
		if ((!regLogin.value)||(!regEmail.value)||(!regPass1.value)||(!regPass2.value)) {
			setRegWarningMessage("��������� ��� ����!");
			return false;
		}
		if(!patternLogin.test(regLogin.value)) {
			setRegWarningMessage('������������ ������� � ������!');	
			return false;
		}
		if(!patternMail.test(regEmail.value)) {
			setRegWarningMessage('�������� ������ e-mail!');	
			return false;
		}
		if(!patternPass.test(regPass1.value)) {
			setRegWarningMessage('������ ������� ��������!');	
			return false;
		}
		if ((regPass1.value)!=(regPass2.value)) {
			setRegWarningMessage("������ �� ���������!");
			return false;
		}				
		return true;	
}
function newMessageValidation() {
}
function deleteNoteFormStyle() {
	deleteNoteForm.style.display = "block"; 
}
function startSessionStorage() {
	if (!sessionStorage["currentMenuButton"]){
	sessionStorage["currentMenuButton"] = "main";
	}
	switch(sessionStorage["currentMenuButton"]){

	case "main":
		showMainPage();
		break;
	case "login":
		showLogPage();
		break;
	case "regist":
		showRegPage();
		break;
	case "regSuc":
		showRegSuccess();
		break;
	case "cabinet":
		showCabinet();
		break;
	case "forum":
		showForum();
		break;
	}
}
