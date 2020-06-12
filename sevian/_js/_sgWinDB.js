// JavaScript Document
//var db=3;
var _sgWinDB = (function(){
	var linea = 0;
	var div = document.createElement("div");
	div.style.cssText = "background-color:white;min-width:300px;_min-height:100px;";
	
	
	var _win = sgPopup1.create({
		name: false,
		title: "Debug",
		draggable: true,
		autoClose: false,
		class: "",
		icon: false,
		delay: 0,
		onHide: function(){},
		onShow: function(){},
		resize: true,
		visible: true,
		position: "fixed",
		typePopup: false,
		mode: "auto",
		btnMin: true,
		btnMax: true,
		btnRestore: true,
		btnClose: true,
		child: div,
		x:"right",
		y:"top",
		height:"500px"		
	});	
	
	function db(msg, color, background){
		_win.show();
		linea++;
		var span = document.createElement("div");
		span.style.cssText = "border:dotted 1px;margin:1px;font-family:arial,font-size:8pt;"
		span.style.color = color;
		span.style.backgroundColor = background;
		div.insertBefore(span, div.firstChild);
		
		
		if(typeof(msg)=="object"){
			var aux = "";
			
			for(var x in msg){
				if(msg.hasOwnProperty(x)){
					aux += ((aux!=="")?", ":"")+x+":"+msg[x];	
				}
			}
			msg += aux;
			
		}
		
		
		span.innerHTML = linea + ".- " + msg;
		
		
		
		return msg;
	}


	var menu = document.createElement("div");
	var btnClear = document.createElement("input");
	btnClear.value = "Clear";
	btnClear.type = "button";
	btnClear.onclick = function(event){
		div.innerHTML = null;
		event.cancelBubble = true;
		event.returnValue = false;	
		
		return false;
	};
	menu.appendChild(btnClear);
	_win.body.insertBefore(menu, _win.body.firstChild);;
	
	window.db = db;	
	
}());
