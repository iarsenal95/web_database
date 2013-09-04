var oDragTargets = {0:[], 1:[]};
var oDragTarget = null;
var oDragItem = null;
var iClickOffsetX = 0;
var iClickOffsetY = 0;
var oLeft = 0;
var oTop = 0;
var gLeft=0;
var gTop=0;
var interval;
var curLeft;
var curTop;

function editUser(aid, name, op)
{	
	var xmlhttp;	
	if (aid == "" || name == "")
	{
  		document.getElementById('newtable').innerHTML="";
  		return;
  	}
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else
  	{// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
 	}
	xmlhttp.onreadystatechange=function()
  	{
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	{
    		document.getElementById('newtable').innerHTML=xmlhttp.responseText;
    	}
  	}
	xmlhttp.open("POST","ACDD.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("albumid="+aid+"&username="+name+"&operation="+op);
}

function OnLoad(){	
	SetupDragDrop();
}

// item3 is dragged to target1, others are dragged to target2
function SetupDragDrop(){
	oDragTargets = {0:[], 1:[]};
	
	var oList = document.getElementsByTagName("div");
	for(var i=0; i<oList.length; i++){
		var o = oList[i];
		if (o.className == "Trash"){
			oDragTargets[0][oDragTargets[0].length] = GetObjPos(o);
		}
		else if (o.className == "AddUser"){
			oDragTargets[1][oDragTargets[1].length] = GetObjPos(o);
		}
		else if (o.className == "AlbumName" || o.className == "RMUser"){
			MakeDragable(o);
		}
	}
}

function MakeDragable(oBox){
	
	oBox.onmousemove= function(e){DragMove(oBox,e)};
	oBox.onmouseup=function(e){DragStop(oBox,e)};
	oBox.onmousedown=function(e){DragStart(oBox,e);return false};
		
}


function DragStart(o,e){
	//document.write("in");
	if(!e) var e = window.event;
	oDragItem = o;
	gLeft=e.layerX+220;
	gTop=e.layerY+65;
	oLeft = oDragItem.style.left; 
	oTop = oDragItem.style.top;
	if (e.offsetX){
		iClickOffsetX = e.offsetX;
		iClickOffsetY = e.offsetY;	
	}else{
		var oPos = GetObjPos(o);
		iClickOffsetX = e.clientX - oPos.x;
		iClickOffsetY = e.clientY - oPos.y;
	}
	// fuxian
	with(oDragItem.style){
		backgroundColor="rgba(84,204,115,1)";
		color="rgba(255,255,255, 1)";
	}
	if (o.setCapture){
		o.setCapture();
	}else{
		window.addEventListener ("mousemove", DragMove2, true);
		window.addEventListener ("mouseup",   DragStop2, true);
	}
}

function DragMove2(e){
	DragMove(oDragItem,e);
}

function DragStop2(e){
	DragStop(oDragItem,e);
}

function DragMove(o,e){
	if (oDragItem==null) return;

	if(!e) var e = window.event;
	var x = e.clientX + document.body.scrollLeft - document.body.clientLeft - iClickOffsetX;
	var y = e.clientY + document.body.scrollTop  - document.body.clientTop - iClickOffsetY;
	
	HandleDragMove(x,y);
}

function HandleDragMove(x,y){
	with(oDragItem.style){
		zIndex = 1000;
		position="absolute";
		//here!!!!!!!!!!!!!!!!!!!!!!!!
		left=x-(window.innerWidth-1024)/2;
		//left=x;
		top=y;
	}
	var id;
	if (oDragItem.className == "AlbumName")
		id = 1;
	if (oDragItem.className == "RMUser")
		id = 0;
	for (var i=0; i< oDragTargets[id].length; i++){
		var oTarget = oDragTargets[id][i];
		if (oTarget.x < x && oTarget.y < y && (oTarget.x + oTarget.w) > x && (oTarget.y + oTarget.h) > y){
			if (oDragTarget!=null && oDragTarget != oTarget.o) OnTargetOut();
			oDragTarget = oTarget.o;
			OnTargetOver();
			return;
		}
	}
	
	if (oDragTarget){
		OnTargetOut();
		oDragTarget = null;
	}
}


function DragStop(o,e){
	if (o.releaseCapture){
		o.releaseCapture();
	}else if (oDragItem){
		window.removeEventListener ("mousemove", DragMove2, true);
		window.removeEventListener ("mouseup",   DragStop2, true);
	}
	
	HandleDragStop();
}

function HandleDragStop(){
	if (oDragItem==null) return;
	curLeft=oDragItem.style.left;
	curTop=oDragItem.style.top;
	if (oDragTarget){
		OnTargetOut();
		OnTargetDrop();
		oDragTarget = null;
		with(oDragItem.style){
		backgroundColor="rgba(84,204,115,0)";
		color="rgba(255,255,255,0)";

		zIndex = 1000;
		position="absolute";
		left=oLeft;
		top=oTop;
		}
		//OnLoad();
		oDragItem = null;
	}
	else{

		 interval = window.setInterval(function(){goBack()}, 0.1); 	

		//with(oDragItem.style){
		//zIndex = 1000;
		//position="absolute";
		//left=oLeft;
		//top=oTop;
		//}
		//setTimeout(function(){oDragItem.style.backgroundColor = "rgba(84,204,115,0)"; oDragItem.style.color="rgba(255,255,255,0)";}, 25);

		
		//oDragItem.style.backgroundColor = "rgba(84,204,115,0)";
		//oDragItem.style.color="rgba(255,255,255,0)";
	}
	//oDragItem.style.zIndex = 1;
//	oDragItem = null;
}


function goBack()
{
		with(oDragItem.style){
                	zIndex = 1000;
               		position="absolute";
		}
                if((Math.abs(parseInt(oDragItem.style.left)-gLeft)<10)&&(Math.abs(parseInt(oDragItem.style.top)-gTop)<10)){
			clearInterval(interval);
			oDragItem.style.left=oLeft;
			oDragItem.style.top=oTop;
			oDragItem.style.backgroundColor = "rgba(84,204,115,0)";
                	oDragItem.style.color="rgba(255,255,255,0)";
                	oDragItem = null;
		}
                if(parseInt(oDragItem.style.left)<gLeft)
                       oDragItem.style.left=parseInt(oDragItem.style.left)+5;
                else if(parseInt(oDragItem.style.left)>gLeft)
                       oDragItem.style.left=parseInt(oDragItem.style.left)-5;
                
                if(parseInt(oDragItem.style.top)<gTop)
                	oDragItem.style.top=parseInt(oDragItem.style.top)+5;
		else if(parseInt(oDragItem.style.top)>gTop)
                	oDragItem.style.top=parseInt(oDragItem.style.top)-5;
                                                                                                                                                                                                
}


function $(s){
	return document.getElementById(s);
}

function GetObjPos(obj){
	var x = 0;
	var y = 0;
	var o = obj;
	
	var w = obj.offsetWidth;
	var h = obj.offsetHeight;
	if (obj.offsetParent) {
		x = obj.offsetLeft
		y = obj.offsetTop
		while (obj = obj.offsetParent){
			x += obj.offsetLeft;
			y += obj.offsetTop;
		}
	}
	return {x:x, y:y, w:w, h:h, o:o};
}

//Drag and Drop Events
function OnTargetOver(){
	oDragTarget.style.border = "3px solid red";
}

function OnTargetOut(){
	oDragTarget.style.border = "";
}

function OnTargetDrop(){
	

	// add albumid and username in AlbumAccess
	// update webpage
	//document.write(oDragTarget.username);
	if (oDragItem.className == "AlbumName"){
		editUser(oDragItem.getAttribute("data-albumid"), oDragTarget.getAttribute("data-username"), "add");	
	}
	// remove albumid and username in AlbumAccess
	// update webpage
	if (oDragItem.className == "RMUser"){
		editUser(oDragItem.getAttribute("data-albumid"), oDragItem.getAttribute("data-username"), "remove");
	}
}

