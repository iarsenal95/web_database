//var my_div = null;
var Black = null;
var PicFrame = null;
var Scroll = null;
var totalNumber;
var ii;
var Container = [];
var albumid;
var URLPRE;
var URLNEXT;
var URL;

function close_div(){
	Black.parentNode.removeChild(Black);
}

function addLayout(i, aid){
	albumid = aid;
	var xmlhttp;	
	if (aid === "" || i === "")
	{
  		return;
  	}
	if (window.XMLHttpRequest)
  	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else
  	{
		// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
 	}
	xmlhttp.onreadystatechange=function()
  	{
  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
    	{
			var parser = new DOMParser();
			var xmlDoc = parser.parseFromString(xmlhttp.responseText, "application/xml");    			
			URLPRE = xmlDoc.getElementsByTagName("url_pre")[0].firstChild.nodeValue;
			URLNEXT  = xmlDoc.getElementsByTagName("url_next")[0].firstChild.nodeValue;
			URL = xmlDoc.getElementsByTagName("url")[0].firstChild.nodeValue;
			totalNumber = Number(xmlDoc.getElementsByTagName("number")[0].firstChild.nodeValue);
			ii = i;
			// 1st layer
  			// create a new div element
  			Black = document.createElement("div");
  			Black.setAttribute("style", "position:absolute; width:100%;height:100%; top: 5px; left: 0px; background-color:rgba(68,68,68,0.8);");
  			Black.innerHTML="<p align='right' onclick='close_div()'>close</p>";
  			document.body.appendChild(Black);
 
   			//2nd layer
  			PicFrame = document.createElement("div");
  			PicFrame.setAttribute("style", "overflow:hidden;position:absolute; width:600px;height:600px; left: 50%; margin-left: -300px; top:50%;margin-top:-300px;background-color:rgba(68,68,68,0.8);");   //rgba(125,68,68,0.8) light red
  			Black.appendChild(PicFrame);
  
  			//3rd layer
  			Scroll = document.createElement("div");
  			Scroll.setAttribute("style", "position:absolute;height:600px;top:50%;margin-top:-300px;background-color:rgba(68,68,68,0.8);");
  			Scroll.style.width = (50+totalNumber*650)+"px";
			var j=-50-ii*650;
			Scroll.style.left=j+"px";
  			PicFrame.appendChild(Scroll);
  			//MakeDragable(Scroll);
  
  			//4th layer
  			//var Container=[];
  			
  			
  			//create containers
  			for(var k=0;k<totalNumber;k++){
	  			Container[k]= document.createElement("div");
	  			Container[k].setAttribute("style", "position:absolute; width:600px;height:600px;top:50%;margin-top:-300px;background-color:rgba(68,68,68,0.8);");   
	  			Container[k].style.left=50+650*k+"px";
	  			Scroll.appendChild(Container[k]);
	  		}
	  		
	  		
			if (ii > 0 && ii < totalNumber-1){//not lasr nor first pic
      			
		
				image=new Image();
  				image.src = URLPRE;
  				image.height=600;
  				image.width=600;
  				Container[ii-1].appendChild(image);

				image1=new Image();
  				image1.src = URL;
  				image1.height=600;
  				image1.width=600;
  				Container[ii].appendChild(image1);
		
				image2=new Image();
  				image2.src = URLNEXT;
  				image2.height=600;
  				image2.width=600;
  				Container[ii+1].appendChild(image2);
		
  			}
  			else if (ii == 0){//first pic
				
		
				image=new Image();
  				image.src = URL;
  				image.height=600;
  				image.width=600;
  				Container[ii].appendChild(image);

				image1=new Image();
  				image1.src = URLNEXT;
  				image1.height=600;
  				image1.width=600;
  				Container[ii+1].appendChild(image1);
			}
 			else if (ii == totalNumber-1){
				
				image=new Image();
  				image.src = URLPRE;
  				image.height=600;
  				image.width=600;
  				Container[ii-1].appendChild(image);

				image1=new Image();
  				image1.src = URL;
  				image1.height=600;
  				image1.width=600;
  				Container[ii].appendChild(image1);
			}
			MakeDragable(Scroll);
		//my_div = document.getElementById("org_div1");  
 			//document.body.appendChild(Black);
		}
  	}
	xmlhttp.open("POST","spv1.php",true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.send("albumid="+aid+"&index="+i);	
	
}


function loadImage(i){
	var xmlhttp;	
	if (albumid === "" || i === "")
	{
  		return;
  	}
	if (window.XMLHttpRequest)
  	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else
  	{
		// code for IE6, IE5
  		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
 	}
	xmlhttp.open("POST","spv2.php",false);
	//xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.send("albumid="+albumid+"&index="+i);
	var parser = new DOMParser();
	var xmlDoc = parser.parseFromString(xmlhttp.responseText, "application/xml");  			
	return xmlDoc.getElementsByTagName("url")[0].firstChild.nodeValue;
}






