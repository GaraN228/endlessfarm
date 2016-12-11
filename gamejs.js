function showInfo(i) {
	activeForms.innerHTML = cellArray[i].pr();
}
function createMap() {
	var result = "";
	for (var i = 0; i < cellArray.length; i++) {		
		if(cellArray[i].x==1){
		result+="<tr>"		
		}		
		result+="<td onclick='showInfo("+i+")'><img class='mapCellImg' src='map/"+cellArray[i].cellType+".svg' width='64' height='64'/></td>"
		if(cellArray[i].x==5){
		result+="</tr>";
		}
	map.innerHTML = result;	
	}
}
