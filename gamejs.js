function convertTimestamp(x) {	
	if (x<=0) return "Собрать!";		
            var sec = x ;
            var h = sec/3600 ^ 0 ;
            var m = (sec-h*3600)/60 ^ 0 ;
            var s = sec-h*3600-m*60 ;
            var res = ((h<10?"0"+h:h)+":"+(m<10?"0"+m:m)+":"+(s<10?"0"+s:s));            
            return res;
            }
function showInfo(i) {
	activeForms.innerHTML = cellArray[i].pr();
}
function createMap() {
	var result = "";
	for (i = 0; i < cellArray.length; i++) {		
		if(cellArray[i].x==1){
		result+="<tr>"	;	
		}		
		result+="<td onclick='createWorkForm("+i+")'><img class='mapCellImg' src='map/"+cellArray[i].cellType+".svg' width='64' height='64'/></td>";
		if(cellArray[i].x==5){
		result+="</tr>";
		}
	map.innerHTML = result;	
	}
}
function createTimers() {
	var result = "";
	for (i = 0; i < activityArray.length; i++){
		name = activityArray[i].resType.slice(5);
		result+=name + " "+ convertTimestamp(activityArray[i].finish)+"<br>";

	}
	activityList.innerHTML = result;

}

function validateWorkTime(min,max,objNum) {
	var x = workForm.workTime.value;
	if(isNaN(x)||(x>max)){
		workForm.workTime.value = max;
		currentWorkTime = max;
		workInfo(objNum);
		return;
	}
	if(x<min){
		workForm.workTime.value = min;
		currentWorkTime = min;
		workInfo(objNum);
		return;	
	}
	workForm.workTime.value = Math.round(x);
	currentWorkTime = Math.round(x);
	workInfo(objNum);	
}

function createWorkForm(objNum) {
	currenCellNumber = objNum;
	lastMapCell.style = "";
	if (!cellArray[objNum].actions.length){
		activeForms.innerHTML = "";
		activeFormsInfo.innerHTML = "";
		return;
		} 
	var result = "";
	var images = document.querySelectorAll('.mapCellImg');
	lastMapCell = images[objNum]; 
	images[objNum].style.width = "60px";
	images[objNum].style.height = "60px";
	images[objNum].style.border = "2px solid #FF4500";
	result += "<form id='workForm' method='POST' action=''>";
	result += "Действие("+cellArray[objNum].actions.length/2+"):<br>";
	result += "<select size = '1' name = 'workType'>";
	for (i = 0; i < cellArray[objNum].actions.length; i+=2) {
		result+="<option value='"+cellArray[objNum].actions[i]+"'>"+cellArray[objNum].actions[i+1]+"</option>"
	}
	result += "<select><br>";
	result += "Время(1-10 мин.):<br>";
	result += "<input type='text' name='workTime' value='10' onchange='validateWorkTime(1,10,currenCellNumber)'><br>";
	currentWorkTime = 10;
	result += "<input type='hidden' name='subType' value='newActivity'>";
	result += "<input type='hidden' name='cellX' value='"+cellArray[objNum].x+"'>";
	result += "<input type='hidden' name='cellY' value='"+cellArray[objNum].y+"'>";	
	result += "<input type='submit' name='submit' value='Начать'>";
	result += "</form>";
	activeForms.innerHTML = result;
	workInfo(objNum);
	
}

function workInfo(objNum) {
	var resultInfo = "";
	resultInfo+="<div id  = 'workFormInfo'>";
	var roadTime = (Math.abs(cellArray[objNum].x - 2) + Math.abs(cellArray[objNum].y - 4))*2;
	resultInfo+="Время на дорогу:<br>";
	resultInfo+=roadTime + " мин.<br>";
	var workReward = currentWorkTime*5 + roadTime*3;  
	resultInfo+="Стоимость работ:<br>";
	resultInfo+=workReward + " зол.<br>";
	resultInfo+="</div>";
	activeFormsInfo.innerHTML = resultInfo;
}
