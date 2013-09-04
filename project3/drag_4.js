var oDragTargets = {0:[], 1:[]};
var oDragTarget = null;
var oDragItem = null;
var iClickOffsetX = 0;
var iClickOffsetY = 0;
var oLeft = 0;
var oTop = 0;
var moved =0;
var tempii;
//var url_next="./images/football_s1.jpg";
//var url_pre="./images/football_s2.jpg";


// things need to pass in: 1. the total number of photo in this album	2. currently viewing which photo


function MakeDragable(oBox){
	
	oBox.onmousemove= function(e){DragMove(oBox,e)};
	oBox.onmouseup=function(e){DragStop(oBox,e)};
	oBox.onmousedown=function(e){DragStart(oBox,e);return false};
		
}


function DragStart(o,e){
	if(!e) var e = window.event;
	oDragItem = o;
	oLeft = oDragItem.style.left; 
	oTop = oDragItem.style.top;
	
if (e.clientX){
		//iClickOffsetX = e.offsetX;
		//iClickOffsetY = e.offsetY;
		iClickOffsetX = e.clientX;	
		iClickOffsetY = e.clientY;	
	}else{

		var oPos = GetObjPos(o);
		iClickOffsetX = e.clientX - oPos.x;
		iClickOffsetY = oPos.y;
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
	var x = e.clientX + document.body.scrollLeft - document.body.clientLeft - (iClickOffsetX+650*(ii));
	var y = e.clientY + document.body.scrollTop  - document.body.clientTop - iClickOffsetY;
	
	
	HandleDragMove(x,y);
}

function HandleDragMove(x,y){
	with(oDragItem.style){
		zIndex = 1000;
		position="absolute";
		var temp = (window.innerWidth-600)/2;
		//left = x-temp-50;
		//left = x-temp+300;
		left = x - 50;
		moved = x-parseInt(oLeft);
		top=300;
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

function HandleImg(){
	var pre = parseInt(Scroll.style.left);
	//Scroll.style.left=-700;
	
	if((parseInt(oLeft)-pre>270) && (ii<totalNumber-1))
	{
		//if(ii<4)//!!!!0,1,2,3:3 is max number
		Scroll.style.left=-50-650*(ii+1);
		//tempii=ii+1;
		ii=ii+1;
		
		if (ii < totalNumber-1 && ii>1){
		
			 var url_next = loadImage(ii+1);
			
			Scroll.childNodes[ii-2].removeChild(Scroll.childNodes[ii-2].firstChild);	//free the leftest container
			
			image_next=new Image();
			image_next.src=url_next;
			image_next.height=600;
			image_next.width=600;
		
			Scroll.childNodes[ii+1].appendChild(image_next);
		}
		else if(ii==totalNumber-1){
			Scroll.childNodes[ii-2].removeChild(Scroll.childNodes[ii-2].firstChild);	//free the leftest container
			
		}
		else{
			var url_next = loadImage(ii+1);
			image_next=new Image();
			image_next.src=url_next;
			image_next.height=600;
			image_next.width=600;
		
			Scroll.childNodes[ii+1].appendChild(image_next);
		}

		


		
			
		
	}	
	else if((parseInt(oLeft)-pre<-270)&&(ii>0))
	{
		Scroll.style.left=-50-650*(ii-1);
		ii=ii-1;
		
		
		if(ii>0 && ii<totalNumber-2){//not the first one
			var url_pre = loadImage(ii-1);
			
			Scroll.childNodes[ii+2].removeChild(Scroll.childNodes[ii+2].firstChild);	//??????
			
			
			image_pre=new Image();
			image_pre.src=url_pre;
			image_pre.height=600;
			image_pre.width=600;
			
			Scroll.childNodes[ii-1].appendChild(image_pre);

		}
		else if(ii==0){
			Scroll.childNodes[ii+2].removeChild(Scroll.childNodes[ii+2].firstChild);//??????
			
		}
		else{
			var url_pre = loadImage(ii-1);
			image_pre=new Image();
			image_pre.src=url_pre;
			image_pre.height=600;
			image_pre.width=600;
			
			Scroll.childNodes[ii-1].appendChild(image_pre);
		}
		
	}
	else{
		Scroll.style.left = oLeft;
		tempii = ii;
	}

		
}


function HandleDragStop(){
	HandleImg();
	if (oDragItem==null) return;
	//oDragItem.style.zIndex = 1;
	oDragItem = null;
	//ii = tempii;
	
	
	
	
	
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

