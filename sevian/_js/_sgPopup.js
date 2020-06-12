/**/
/*

19 JUNIO 2016

*/


function addEventSimple(obj,evt,fn) {
	if(obj.addEventListener){
		evt = evt.replace(/^\s*on/gi,"");
		obj.addEventListener(evt,fn,false);
	}else if(obj.attachEvent){
		obj.attachEvent(evt,fn);
	}// end if
}// end function

function removeEventSimple(obj,evt,fn) {
	if(obj.removeEventListener){
		evt = evt.replace(/^\s*on/gi,"");
		obj.removeEventListener(evt,fn,false);
	}else if(obj.detachEvent){
		obj.detachEvent(evt,fn);
	}// end if
}// end function

var sgFloat = {

	getXY: function(e){
		var cW = document.documentElement.clientWidth;
		var cH = document.documentElement.clientHeight;
		var sT = document.documentElement.scrollTop;
		var sL = document.documentElement.scrollLeft;

		var w = e.offsetWidth;
		var h = e.offsetHeight;
		var X = e.offsetLeft;
		var Y = e.offsetTop;
		
		var ctlTag = e.offsetParent;
		
		while(ctlTag !== null){
			X += ctlTag.offsetLeft - ctlTag.scrollLeft;
			Y += ctlTag.offsetTop - ctlTag.scrollTop;
			ctlTag = ctlTag.offsetParent;
		}// end while
		
		return {x:X, y:Y, x2:X + w, y2: Y + h, w: w, h: h, cW: cW, cH: cH, sT: sT,sL: sL};
	},

	showElem: function(e, x, y){
		
		e.style.top = y + "px";
		e.style.left = x + "px";
		
		return x, y;
		
	},
	
	show: function(elem, xx, yy, deltaX, deltaY){
		var x;
		var y;
		var c;
		
		if(typeof xx !== "number" || yy !== "number"){
			c = this.getXY(elem);
		}
		
		
		if(typeof xx != "number"){
			switch(xx){
			case "center":
				x = (c.cW-c.w)/2;
				break;	
			case "left":
				x = 0;
				break;	
			case "right":
				x = c.cW-c.w;
				break;	
			}// end switch
		}else{
			x = xx;
		}// end if

		if(typeof yy != "number"){
			switch(yy){
			case "middle":
				y = c.sT+(c.cH-c.h)/2;
				break;	
			case "top":
				y = c.sT;
				break;	
			case "bottom":
				y = c.sT+c.cH-c.h;
				break;	
			}// end switch
		}else{
			y = yy;
		}// end if
	
		return this.showElem(elem, x + (deltaX || 0) ,y + (deltaY || 0));
		
	},

	showMenu: function(e, ref, xx, yy, xdelta, ydelta){
		var x;
		var y;
		
		var fixed = e.style.position == "fixed";
		
		xdelta = xdelta || 0;
		ydelta = ydelta || 0;
		var c = this.getXY(ref);

		var w = e.offsetWidth;
		var h = e.offsetHeight;
		var cW = c.cW;
		var cH = c.cH;
		var sL = c.sL;
		var sT = c.sT;
		
		
		switch(xx){
		case "center":
			x = c.x + c.w/2;
			break;	
		case "left":
			x = c.x;
			break;	
		case "right":
			x = c.x + c.w;
			break;
		case "back":
			x = c.x - w;
			break;
		default:
			x = c.x + c.w - 10;
			
		}// end switch
		
	
		switch(yy){
		case "middle":
			y = c.y + c.h/2;
			break;	
		case "top":
			y = c.y;
			break;	
		case "bottom":
			y = c.y  + c.h;
			break;
		case "up":
			y = c.y - h;
			break;
		default:
			y = c.y + c.h - 10;	
		}// end switch

		if(fixed){
			x = x - sL;	
			y = y - sT;	
		}

		x = x + xdelta;
		y = y + ydelta;

		if ((x+w) > (cW+sL)){
			x = cW + sL - w;
			//x = c.x - w;
		}// end if
		if (x < sL){
			x = sL;
		}// end if

		if ((y+h) > (cH+sT)){
			y = cH + sT - h; 
		}// end if

		if (y < sT && !fixed){
			alert(8)
			y = sT; 
		}// end if	

		return this.showElem(e, x, y);
		
	},
	
	dropDown: function(e, ref, xx, yy, xdelta, ydelta){
		var x;
		var y;
		
		xdelta = xdelta || 0;
		ydelta = ydelta || 0;
		var c = this.getXY(ref);

		var w = e.offsetWidth;
		var h = e.offsetHeight;
		var cW = c.cW;
		var cH = c.cH;
		var sL = c.sL;
		var sT = c.sT;
		
		
		switch(xx){
		case "center":
			x = c.x + c.w/2;
			break;	
		case "left":
			x = c.x;
			break;	
		case "right":
			x = c.x + c.w;
			break;
		case "back":
			x = c.x - w;
			break;
		default:
			x = c.x + c.w - 10;
			
		}// end switch
		
	
		switch(yy){
		case "middle":
			y = c.y + c.h/2;
			break;	
		case "top":
			y = c.y;
			break;	
		case "bottom":
		
			y = c.y + c.h;
			break;
		case "up":
		
			y = c.y - h;
			break;
		default:
			y = c.y + c.h - 10;	
		}// end switch
	

		x = x + xdelta;
		y = y + ydelta;
		

		if ((x+w) > (cW+sL)){
			x = cW + sL - w;
			//x = c.x - w;
		}// end if
		if (x < sL){
			x = sL;
		}// end if
		if ((y+h) > (cH+sT)){
			//y = cH + sT - h; 
		}// end if
		if (y < sT){
			
			y = sT; 
		}// end if	
		
		if ((c.y+c.h+h) > (cH+sT)){
			y = c.y - h;
		}// end if
		
		return this.showElem(e, x, y);
		
	},	
	
};// end object

var sgDrag = {
	capture: false,
	release: false,
	iniX: false,
	iniY: false,
	
	init: function(elem, opt) {

		var ME = this;

		elem.onmousedown = function(event){

			event = event || window.event;
			ME.iniX = event.clientX;
			ME.iniY = event.clientY;
			
			opt.onstart();
			
			if(ME.capture){
				ME.removeEventSimple(document, "mousemove", ME.capture);
			}
			
			if(ME.release){
				ME.removeEventSimple(document, "mouseup", ME.release);			
			}
			
			ME.addEventSimple(document, "mousemove", ME.capture = function(event){
				event = event || window.event;
				opt.oncapture(event.clientX, event.clientY, ME.iniX, ME.iniY);				
			});

			ME.addEventSimple(document, "mouseup", ME.release = function(event){
				
				ME.removeEventSimple(document, "mousemove", ME.capture);
				ME.removeEventSimple(document, "mouseup", ME.release);
				opt.onrelease(event.clientX, event.clientY, ME.iniX, ME.iniY);
				
			});
			
		};
		
	},// end function

	addEventSimple: function(obj, _event, _function) {
		if(obj.addEventListener){
			obj.addEventListener(_event, _function, false);
		}else if(obj.attachEvent){
			obj.attachEvent('on' + _event, _function);
		}
	},// end function
	
	removeEventSimple: function(obj, _event, _function) {
		if(obj.removeEventListener){
			obj.removeEventListener(_event, _function, false);
		}else if(obj.detachEvent){
			obj.detachEvent('on' + _event, _function);
		}
	}// end function
	
};// end object

var sgDragDrop = (function(){

	var xA;
	var yA;
	var sX;
	var sY;
	var posX;
	var posY;
	var cW;//document.documentElement.clientWidth;
	var cH;//document.documentElement.clientHeight;

	var _main;
	var _hand;
	var _opt;	
	
	var _rs_mode;

	var _rs_onstart = function(main, opt, mode){
		
		return function(){
			_main = main;
			_opt = opt;
			xA = _main.offsetWidth;
			yA = _main.offsetHeight;
			
			_rs_mode = mode;

			cW = document.documentElement.clientWidth;
			cH = document.documentElement.clientHeight;
			
		};
	};

	var rs_oncapture = function(x, y, iniX, iniY){
		
		//var cW = document.documentElement.clientWidth;
		//var cH = document.documentElement.clientHeight;

		if(x < 0 || x > cW){
			return;
		}

		if(y < 0 || y > cH){
			return;
		}
		
		var dx = (x - iniX);
		var dy = (y - iniY);
		var W;
		var H;

		
		switch(_rs_mode){
		case 1:
		
			W = (xA - dx) + "px";
			_main.style.left = x+"px";
			
			H = (yA - dy) + "px";
			_main.style.top = y + "px" ;
			break;	
		case 2:
			H = (yA - dy) + "px";
			_main.style.top = y + "px" ;
		
			break;	
		case 3:
			W = (xA + x - iniX) + "px";
		
			H = (yA - dy) + "px";
			_main.style.top = y + "px" ;

		
			break;	
		case 4:
			W = (xA - dx) + "px";
			_main.style.left = x + "px";
			break;	
		case 5:
			W = (xA + x - iniX) + "px";
			break;	
		case 6:
			W = (xA - dx) + "px";
			_main.style.left = x + "px";
			
			H = (yA + y - iniY) + "px";
			
			break;	
		case 7:
			H = (yA + y - iniY) + "px";
			break;	
		case 8:
			W = (xA + x - iniX) + "px";
			H = (yA + y - iniY) + "px";
			break;	
		}

		_main.style.width = W; 
		_main.style.height = H; 

		if(_opt.onresize){
			_opt.onresize(xA + x - iniX, yA + y - iniY);
		}
			
	};
	
	var rs_onrelease = function(x, y, iniX, iniY){
		
		if(_opt.onrelease){
			_opt.onrelease(x, y, iniX, iniY);
		}					

	};

	var _mv_onstart = function(main, opt){
		
		return function(){
			_main = main;
			_opt = opt;
			sX = _main.offsetLeft;
			sY = _main.offsetTop;	
		};
	};

	var mv_restart = function(){
		sX = _main.offsetLeft;
		sY = _main.offsetTop;		
		
	};

	var mv_oncapture = function(x, y, iniX, iniY){
	
		posX = sX + (x - iniX);
		posY = sY + (y - iniY);

		if(posX <= 0){
			posX = 0;	
		}
		
		if(posY <= 0){
			posY = 0;	
		}
		
		_main.style.left = posX + "px";
		_main.style.top = posY + "px";
		
		if(_opt.onmove && _opt.onmove(posX, posY, x, y)){
			mv_restart();
		}					
	
	};

	var mv_onrelease = function(x, y, iniX, iniY){
		if(_opt.onrelease){
			_opt.onrelease(posX, posY, x, y, iniX, iniY);
		}			
	};

	var _resize = function(main, holder, opt){

		for(var x in holder){
			if(holder[x].e)
			sgDrag.init(holder[x].e, {		
				onstart: _rs_onstart(main, opt, holder[x].m),
				oncapture: rs_oncapture,
				onrelease: rs_onrelease
			});			
			
		}// next

	};	

	var _move = function(main, hand, opt){
		
		sgDrag.init(hand, {		
			onstart: _mv_onstart(main, opt),
			oncapture: mv_oncapture,
			onrelease: mv_onrelease
		});		
		
	};

	return {
		resize: _resize,
		move: _move

	};	
	
})();


sgPopup1 = (function(undefined){
	var w = [];
	var index = 0;
	/*
	var _main = document.createElement("div");//document.body;//.
	_main.style.cssText = "position:absolute;top:0;left:0;width:100%;";
	document.body.appendChild(_main);
*/
	var _main = document.body;

	var zIndex = 10000;
	

	var sgWindow = function(opt){
		index++;
		
		var ME = this;
		
		this.name = "";
		this.title = "";
		this.child = false;
		this.icon = false;
		this.class = "";
			
		this.onHide = function(){};
		this.onShow = function(){};
		
		this.draggable = true;
		this.hand = false;	
		this.autoClose = true;	
		this.resize = true;
		this.visible = true;
		this.position = "fixed"; /*absolute, fixed*/

		this.delay = 0;

		this.typePopup = true;
		this.mode = "auto"; /*auto:1, min:2, max:3, custom:4*/
		this.width = "500px";
		this.height = "450px";

		this.x = "center";
		this.y = 30;
		
		this.btnMin = true;
		this.btnMax = true;
		this.btnRestore = true;
		this.btnClose = true;			
		
		this.cssMode = true;
		this.maxMargin = 30;
		
		this.winStatus = 0;
		this.lastStatus = {};
		this.active = false;
		this._active = false;
		this._timer = false;
		this.index = index;

		var caption;
		var hand;
		
		
		for(var x in opt){
			this[x] = opt[x];
		}// next

		var main = this.main = document.createElement("div");
		if(this.class){
			main.className = this.class;	
		}
		main.style.cssText = "left:0px;top:0px;visibility:hidden;";
		
		if(this.position === "fixed"){
			main.style.position = "fixed";
		}else{
			main.style.position = "absolute";
		}
		
		main.style.zIndex = zIndex++;
		
		main.onmousedown = function(){
			this.style.zIndex = ME.getIndex();
		};
		
		if(this.typePopup === false){
			main.dataset.popupType = "window";	
			caption = this.createCaption(this.title, this.icon, true, true, true);
			main.appendChild(caption);

		}else{
			main.dataset.popupType = "popup";	
			
		}

		var body = this.body = document.createElement("div");
		body.dataset.popupType = "body";
		
		if(this.child){
			
			body.appendChild(this.child);
		}
		
		_main.appendChild(main);


		if(this.draggable){
			
			if(this.hand){
				hand = this.hand;
			}else if(caption){
				hand = caption;	
			}else{
				hand = body;
			}
			
			sgDragDrop.move(main, hand, {
				onmove: function(posX, posY, eX, eY){
			
					ME.ex = eX;
					ME.ey = eY;
					
					ME.x = posX;
					ME.y = posY;
					
					if(ME.mode == "max"){
						ME.restore();
						ME.setWinStatus("auto");
						ME.mode = "auto";
						return true;
					}
				}, 
				onrelease: function(posX, posY, x, y, iniX, iniY){
					
					var cW = document.documentElement.clientWidth;
					var cH = document.documentElement.clientHeight;
					var sT = document.documentElement.scrollTop;
					var sL = document.documentElement.scrollLeft;

					if(ME.main.style.position == "fixed"){
						
						if(posX > cW-80 || posY > cH-20){
							posX = (posX > cW-80)?cW-80: posX; 
							posY = (posY > cH-20)?cH-20: posY; 
	
							ME.move(posX, posY);
						}
					}
					
				}
			});
		}// end if(this.draggable)

		if(this.resize){

			var rsLT = document.createElement("div");
			rsLT.dataset.popupType = "rs-lt";
			main.appendChild(rsLT);

			var rsT = document.createElement("div");
			rsT.dataset.popupType = "rs-t";
			main.appendChild(rsT);

			var rsRT = document.createElement("div");
			rsRT.dataset.popupType = "rs-rt";
			main.appendChild(rsRT);

			var rsL = document.createElement("div");
			rsL.dataset.popupType = "rs-l";
			main.appendChild(rsL);

			var rsR = document.createElement("div");
			rsR.dataset.popupType = "rs-r";
			main.appendChild(rsR);

			var rsLB = document.createElement("div");
			rsLB.dataset.popupType = "rs-lb";
			main.appendChild(rsLB);

			var rsB = document.createElement("div");
			rsB.dataset.popupType = "rs-b";
			main.appendChild(rsB);

			var rsRB = document.createElement("div");
			rsRB.dataset.popupType = "rs-rb";
			main.appendChild(rsRB);
			
			sgDragDrop.resize(main, [
					{e:rsLT,m:1},
					{e:rsT,m:2},
					{e:rsRT,m:3},
					{e:rsL,m:4},
					
					{e:rsR,m:5},
					{e:rsLB,m:6},
					{e:rsB,m:7},
					{e:rsRB,m:8}
					
				], {
				onresize: function(posX, posY){
					if(ME.mode != "custom"){
						ME.restoreBody();	
						ME.setWinStatus("custom");					
						ME.mode = "custom";
						ME.main.dataset.popupMode = "custom";
					}			
				},
				onrelease: function(posX, posY, x, y, iniX, iniY){
					
					posY = ME.main.offsetTop;
					posX = ME.main.offsetLeft;
					
					var cW = document.documentElement.clientWidth;
					var cH = document.documentElement.clientHeight;
					var sT = document.documentElement.scrollTop;
					var sL = document.documentElement.scrollLeft;

					if(posX > cW-80 || posY > cH-20){
						posX = (posX > cW-80)?cW-80:posX; 
						posY = (posY > cH-20)?cH-20:posY; 

						ME.move(posX, posY);
					}
				}					
			});
			
		}// end if(this.resize)
		
		main.appendChild(body);
		
		if(this.autoClose){
			
			addEventSimple(document, "click", function(){
				if(ME._active === true || ME._active === null){
					
					if(ME._active === null){
						ME._active = false;
					}// end if
					
					return;	
				}// end if				
				
				ME.hide();			
				
			});
			
			main.onmouseover = function(event){
				ME._active = true;
				
				if(ME.delay && ME.delay > 0){
					
					ME.setTimer();
				}// end if			
			};
	
			main.onmouseout = function(event){
				ME._active = false;
				
			};
			//this._active = null;			
			
		}

		this.modeIni = {
			mode: this.mode,
			width: this.width,
			height: this.height,
			x: this.x,
			y: this.y,			
			
		};
		
		this.setMode(this.mode);

		//this.setWinStatus(1);
		this.setLastStatus();
		this.active = true;
		
		this.setVisible(this.visible);
		
	};

	sgWindow.prototype = {
		
		createCaption: function(caption, icon, btnClose, btnMax, btnMin){
			var main = this.main;
			var ME = this;
			
			var div = document.createElement("div");
			div.dataset.popupType = "caption";

			div.ondblclick=function(){
				if(ME.mode != "max"){
					ME.setMode("max");
				}else{
					ME.setMode("auto");
				}
			};

			if(this.icon){
				this.icon = document.createElement("img");
				this.icon.draggable="false";
				this.icon.src = icon;			
				this.icon.dataset.popupType = "icon";
				div.appendChild(this.icon);
			}// end if			
			
			
			
			var _caption = this.caption = document.createElement("div");
			_caption.innerHTML = caption;
			_caption.dataset.popupType = "title";
			div.appendChild(_caption);

			

			if(this.btnMin){
				this._btnMin = document.createElement("div");
				this._btnMin.dataset.popupType = "btn_min";
				this._btnMin.onclick = function(){
					ME.setMode("min");
				};					
				div.appendChild(this._btnMin);
			}// end if			

			if(this.btnRestore){

				this._btnRes = document.createElement("div");
				this._btnRes.dataset.popupType = "btn_res";
				this._btnRes.onclick = function(){
					ME.setMode("auto");
				};				
				div.appendChild(this._btnRes);
				
			}// end if			

			if(this.btnMax){

				this._btnMax = document.createElement("div");
				this._btnMax.dataset.popupType = "btn_max";
				this._btnMax.onclick = function(){
					ME.setMode("max");
				};				
				div.appendChild(this._btnMax);
				
			}// end if			

			if(this.btnClose){

				this._btnClose = document.createElement("div");
				this._btnClose.dataset.popupType = "btn_close";
				this._btnClose.onclick = function(){
					ME.hide();
				};
				div.appendChild(this._btnClose);
				
			}// end if	

			return div;
			
		},
		
		setMode: function(mode){

			switch(mode){
				case "min":
					this.setMin();
					break;	
				case "max":
					this.setMax();
					break;	
				case "custom":
					this.setCustom();
					break;	
				case "auto":
					this.setAuto();					
					break;	
			}// end switch			

			if(mode != "max"){
				this.move(this.x, this.y);	
			}
			
			this.mode = mode;
			this.setWinStatus(mode);

		},
		
		setButton: function(opt){
			
			if(this._btnMin){
				this._btnMin.style.display = opt[0];
			}
			if(this._btnMax){
				this._btnMax.style.display = opt[1];
			}
			if(this._btnRes){
				this._btnRes.style.display = opt[2];
			}
			if(this._btnAut){
				this._btnAut.style.display = opt[3];
			}
			
		},
		
		setWinStatus: function(mode){
			switch(mode){

			case "auto":
				this.setButton(["","","none","none"]);
				break;
			case "min":
				this.setButton(["none","","","none"]);
				break;
			case "max":
				this.setButton(["","none","","none"]);
				break;
			case "custom":
				this.setButton(["","","","none"]);
				break;
			}// end switch			
			
		},
		
		setLastStatus: function(){
			var b = sgFloat.getXY(this.body);
			var m = sgFloat.getXY(this.main);
			this.lastStatus.w = b.w;
			this.lastStatus.h = b.h;
			this.lastStatus.x = m.x;
			this.lastStatus.y = m.y;			
			this.lastStatus.mW = m.w;			
			this.lastStatus.mH = m.h;			
			
		},
		
		getIndex: function(){
			
			return zIndex++;
			
		},
		
		appendChild: function(ele){
		
			if (typeof(ele) == "object"){
				this.body.appendChild(ele);
			}else{
	
				this.body.innerHTML += ele;
			}// end if		
			
		},

		setBody: function(ele){
		
			if (typeof(ele) == "object"){
				this.body.innerHTML = "";
				this.body.appendChild(ele);
			}else{
				this.body.innerHTML = ele;
			}// end if		

		},
		
		setMin: function(){
			
			this.main.dataset.popupMode = "min";
			

			this.body.style.height = "0px";
			this.body.style.width = "200px";

			this.main.style.height = "auto";
			this.main.style.width = "auto";
			this.setLastStatus();
					
			
		},

		restoreBody: function(){
			this.body.style.width = "auto";
			this.body.style.height = "auto";			
		},
		
		setAuto: function(){
			this.main.dataset.popupMode = "auto";
			this.restoreBody();

			this.main.style.height = "auto";
			this.main.style.width = "auto";	
		},

		setCustom: function(w, h){

			this.main.style.width = w || this.width;								
			this.main.style.height = h || this.height;
			this.restoreBody();	
			this.main.dataset.popupMode = "custom";			

		},

		setMax: function(){

			this.main.dataset.popupMode = "max";
			this.setLastStatus();
			this.restoreBody();
			
			this.move(this.maxMargin, this.maxMargin, 0, 0);
			this.main.style.width = "calc(100% - "+this.maxMargin*2+"px)";
			this.main.style.height = "calc(100% - "+this.maxMargin*2+"px)";
			
			
			
		},
		
		restore: function(){
			
			//var w = this.lastStatus.mW;
			var w = this.main.offsetWidth;
			this.setAuto();

			var w2 = this.main.offsetWidth;	
			this.main.style.left = (this.ex-(w2*(this.ex-this.x)/w))+"px";
			this.x=(this.ex-(w2*(this.ex-this.x)/w));
			this.main.dataset.popupMode = "auto";

		},
		
		move: function(x, y, deltaX, deltaY){

			var xy = sgFloat.show(this.main, x, y, deltaX, deltaY);	
			this.x = xy.x;
			this.y = xy.y;			
			
		},
		
		
		zoneModal:function(){
			
			var div = document.createElement("div");
			
			div.style.cssText="position:fixed;top:0px;left:0px;right:0px;bottom:0px;background-color:gray;";
			div.style.zIndex = this.getIndex();
			document.body.appendChild(div);
		},
		
		show: function(x, y, deltaX, deltaY){
			
			this._active = null;
			this.main.style.zIndex = this.getIndex();
			if(x !== false){
				
				var xy = sgFloat.show(this.main, x, y, deltaX, deltaY);
				this.x = xy.x;
				this.y = xy.y;				
			}
			
			this.setVisible(true);
			
		},
		
		showMenu: function (ref, x, y, deltaX, deltaY){
			this._active = null;
			this.main.style.zIndex = this.getIndex();	
			var xy = sgFloat.showMenu(this.main, ref, x, y, deltaX, deltaY);	
			this.setVisible(true);
			this.x = xy.x;
			this.y = xy.y;			
		},

		dropDown: function (ref, x, y, deltaX, deltaY){
			this._active = null;
			this.main.style.zIndex = this.getIndex();	
			var xy = sgFloat.dropDown(this.main, ref, x, y, deltaX, deltaY);	
			this.setVisible(true);
			this.x = xy.x;
			this.y = xy.y;			
		},
		
		hide: function(remote){
			
			if(!this.visible){
				return false;	
			}
			var request = true;
			if(!remote){
				request = this.onHide();
			}
			
			if(request === true || request === undefined){
				this.setVisible(false);
			}
			
			return true;	
			
		},
		
		setVisible: function(value){
			
			this.visible = value;
			if(this.visible){
				this.main.style.visibility = "visible";
				this.main.dataset.popupMode = this.mode;
				this.main.dataset.popupVisible = "visible";
			}else{
				if(!this.cssMode){
					this.main.style.visibility = "hidden";
				}
				this.main.dataset.popupMode = "close";
				this.main.dataset.popupVisible = "hidden";
			}
			
		},
		
		setCaption: function(text){

			if(this.caption){
				this.caption.innerHTML = text;
			}
			
		},

		getCaption: function(){

			return this.caption;

		},

		setTimer: function(){
			var ME = this;
			if(this._timer){
				clearTimeout(this._timer);
			}// end if
			
			this._timer = setTimeout(function(){ME.hide();}, this.delay);		
		
		},// end function
		
	};


	return {
		create: function(opt){
			var name;
			if(opt.name){
				name = opt.name;
				
			}else{
				name = index;	
			}
					
			w[name] = new sgWindow(opt);
			
			return w[name];

		},
		win: function(name){
			
			return w[name];
			
		}
		
		
	};


	
	
}());



/*

var div2 = document.createElement("div");
div2.style.cssText = "height:50px;width:50px;border:2px white solid;background-color:purple;";

var v1 = sgPopup1.create(
	{
		name:false,
		title:'Hola',
		draggable:true,
		autoClose:false,
		class: "",
		
		barNav: true,
		closeButton:false,
		maxButton:false,
		icon: "http://localhost/_sevian/images/icon_new.png",
		delay:5000,
		onHide:function(){},
		onShow:function(){},

		
		resize: true,
		visible: true,
		position: "fixed", 

		typePopup: false,
		mode: "auto", 
		width: "100%",
		height: "100%",
		x: 100,
		y: 50,
		
		btnMin: true,
		btnMax: true,
		btnRestore: true,
		btnClose: true,
		

		
		
	}
);

var v2 = sgPopup1.create(
	{
		name:false,
		title:'Adios',
		draggable:true,
		autoClose:false,
		class: "",
		barMenu : false,
		barNav: true,
		closeButton:false,
		maxButton:false,
		icon: "http://localhost/_sevian/images/REAL OSX SYSTEM ALERT STOP.png",
		delay:5000,
		onHide:function(){},
		onShow:function(){},

		bottom:0,
		right:0,
		
		
		status:"min",
		
		
		hand : div2,
		
		resize: true,
		visible: true,
		position: "absolute", //absolute, fixed/

		typePopup: true,
		mode: "min", //auto:1, min:2, max:3, custom:4/
		width: "100%",
		height: "100%",

		x:200,
		y:50,
		
		btnMin: true,
		btnMax: true,
		btnRestore: true,
		btnClose: true,
		
	}
);

var v3 = sgPopup1.create(
	{
		name:false,
		title:'Personas',
		draggable:true,
		autoClose:false,
		class: "",
		barMenu : false,
		barNav: true,
		closeButton:false,
		maxButton:false,
		icon: "http://localhost/_sevian/images/REAL OSX CLOCK.png",
		delay:5000,
		onHide:function(){},
		onShow:function(){},

		bottom:0,
		right:0,
		status:"normal",
		
		
		resize: true,
		visible: true,
		position: "absolute", //absolute, fixed/

		typePopup: false,
		mode: "min", //auto:1, min:2, max:3, custom:4/
		width: "100%",
		height: "100%",

		x:600,
		y:250,
		
		btnMin: true,
		btnMax: true,
		btnRestore: true,
		btnClose: true,		

		
	}
);

var btn = document.createElement("input");
btn.type="button";
btn.value="Show";
btn.onclick = function(){
	v2.show("center", "top");
};
v1.appendChild(btn);

var txt = document.createElement("input");
txt.onchange=function(){
	v1.setCaption(this.value);	
	v2.setCaption(this.value);	
	
};
v1.appendChild(txt, true);



var btn2 = document.createElement("input");
btn2.type="button";
btn2.value="Show2";
btn2.onclick = function(){
	v1.show("center", "bottom", 0, -10);
	
};

v2.appendChild(btn2);
v2.appendChild(div2);

var btn3 = document.createElement("input");
btn3.type="button";
btn3.value="Mostrar 3";
btn3.onclick = function(){
	//v1.show("center", "bottom", 0, -10);
	v1.show();
	
};
v3.appendChild(btn3);

var btn4 = document.createElement("input");
btn4.type="button";
btn4.value="Mostrar 3b";
btn4.onclick = function(){
	v1.show("center", "bottom", 0, -10);
	sgPopup1.win("sg_panel_200").show("right", "bottom", -10, -10);
	
};
v3.appendChild(btn4);
*/