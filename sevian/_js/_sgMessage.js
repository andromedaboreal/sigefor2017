// JavaScript Document



var sgTool = {
	get: function(e){
		if(typeof(e) === "object"){
			return e;
		}else if(document.getElementById(e)){
			return document.getElementById(e);
		}// end if		
	},
	set: function(target, e){
	
		if (typeof(e) === "object"){
			target.innerHTML = "";
			target.appendChild(e);
		}else{
			target.innerHTML = e;
		}// end if		

	},	
	append: function(target, e){
		if (typeof(e) === "object"){
			target.appendChild(e);
		}else{
			target.innerHTML += e;
		}// end if		
		
	}
	
	
};


var SS = {};
SS.sgInWindow = {
	WIN_FLOAT: "float",
	WIN_ATTACH: "attach",
};

var sgInWindow = function(opt){
	this.target = false;
	this.message = "";
	this.visible = true;

	this.autoClose = true;
	this.delay = 0;

	this.icon = false;
	this.class = "";

	this.btnClose = true;
		
	this.onHide = function(){};
	this.onShow = function(){};

	
	
	for(var x in opt){
		
		this[x] = opt[x];	
	}	
	
	
	this.create();
	if(this.visible){
		this.show();
	}
};

sgInWindow.prototype = {
	create: function(){
		var main = this._main = document.createElement("div");
		main.dataset.sgType = "in_window";
		
		this._caption = this.createCaption(this.title);
		this._body = document.createElement("div");
		
		if(this.body){
			this.appendChild(this.body);	
		}
		
		main.appendChild(this._caption);
		main.appendChild(this._body);
		var ME = this;
		main.onmouseover = function(event){
			ME._active = true;
			
			if(ME.autoClose && ME.delay > 0){
				
				ME.setTimer();
			}// end if			
		};		
		
	},
	
	createCaption: function(caption){
		
		var ME = this;
		
		var div = document.createElement("div");
		div.dataset.popupType = "caption";

		

		if(this.icon){
			
			this._icon = document.createElement("img");
			this._icon.draggable = "false";
			this._icon.src = this.icon;			
			this._icon.dataset.popupType = "icon";
			div.appendChild(this._icon);
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
	
	appendChild: function(e){
		sgTool.append(this._body, e);
	},

	setBody: function(e){
		sgTool.set(this._body, e);
	},	
	
	
	show: function(){
		this.onShow();
		this.target.insertBefore(this._main, this.target.firstChild);
		
		if(this.autoClose && this.delay > 0){
			this.setTimer();	
		}
		
	},
	
	hide: function(){
		this.onHide();
		this.target.removeChild(this._main);
	},
	
	setTimer: function(){
		var ME = this;
		if(this._timer){
			clearTimeout(this._timer);
		}// end if
		
		this._timer = setTimeout(function(){ME.hide();}, this.delay);		
	
	},// end function	
	
	
};




(function(){

var msg = function(opt){
	
	this.lang = "spa";
	this._label = false;
	
	this.winClass = "";
	this.draggable = true;
	this.resize = true;
	this.autoClose = false;
	this.icon = "http://localhost/_sevian/themes/_common/images/user.png";
	this.delay = 2000;
	this.visible = true;
	this.labels = {
		eng:{
			btnOk:"Ok",
			btnCancel:"Cancel"
		},
		spa:{
			btnOk:"Aceptar",
			btnCancel:"Cancelar"
			
			
		}
		
	};
	this.class = "message";
	this.caption = false;
	this.message = '';
	this.target = false;
	this.type = 'basic';
	this.delay = false;
	this.icon = false;
	this.typeMenu = 2;
	
	this._win = false;
	
	for(var x in opt){
		this[x] = opt[x];	
	}
	
	this._label = this.labels[this.lang];
	
	if(!this.main){
		this.main = this.basic();
	}
	
	this.createWindow();
	
	this.show();
	
};


msg.prototype = {
	
	createWindow: function(){
		if(this._win){
			return false;	
		}
		
		if(this.typeWindow === "float"){
			this._win = sgPopup1.create({
				name: false,
				title: this.caption,
				draggable: this.draggable,
				autoClose: this.autoClose,
				class: this.winClass,
				icon: this.icon,
				delay: this.delay,
				onHide: function(){},
				onShow: function(){},
				resize: this.resize,
				visible: this.visible,
				position: "fixed",
				typePopup: false,
				mode: "auto",
				btnMin: false,
				btnMax: false,
				btnRestore: false,
				btnClose: true,
				child: this.main		
			});
		}else{
			
			this._win = new sgInWindow({
				target: this.target,
				title: this.caption,
				autoClose: this.autoClose,
				class: this.winClass,
				icon: this.icon,
				delay: this.delay,
				onHide: function(){},
				onShow: function(){},
				visible: this.visible,
				position: "fixed",
				typePopup: false,
				btnClose: true,
				body: this.main		
			});			
			
		}
		return true;
		
	},
	
	show: function(message){
		if(message){
			this.message = message;
		}
		
		

		
		
		this.msgDiv.innerHTML = this.message;
		this._win.show();
		
		
		return;		
		
		
	},
	
	
	callhide: function(){
		var ME = this;
		return function(){
			ME._win.hide();
			
		};
	},	
	basic: function(){
		var main = document.createElement("div");
		main.className = this.class;
		var caption = document.createElement("div");
		var body = document.createElement("div");
		var img = document.createElement("img");
		img.src = this.image;
		body.appendChild(img);
		var msg = this.msgDiv = document.createElement("div");
		
		
		
		body.appendChild(msg);
		
		main.appendChild(caption);
		main.appendChild(body);
		main.appendChild(this.menu());
		
		main.dataset.type = "message";
		body.dataset.type = "body";
		
		return main;
		
	},
	
	
	
	menu: function(){
		var main = document.createElement("div");
		
		main.dataset.type = "menu";
		
		var btnOk = document.createElement("input");
		btnOk.type = "button";
		btnOk.value = this._label.btnOk;
		btnOk.dataset.type = "menu_ok";
		this._win.onHide = function(){
			
		};
		btnOk.onclick = this.callhide();
		
		main.appendChild(btnOk);
		
		if(this.typeMenu == "2"){
			
			var btnCancel = document.createElement("input"); 
			btnCancel.type = "button";
			btnCancel.value = this._label.btnCancel;
			btnCancel.dataset.type = "menu_cancel";
			btnCancel.onclick = this.callhide();
			main.appendChild(btnCancel);
		}
		
		
		
		return main;
		
	},
		
		
	
	
	
	
};
	window.msg = msg;

}());

var h = new msg({
	target:document.getElementById("cc"),
	caption:"Importante",
	message:"El Campo Cédula es Obligatorio...!!!",
	type: "window",
	typeWindow:"attach",
	typeWindow_:"float",
	winClass: "message",
	image:"http://localhost/_sevian/themes/_common/images/REAL OSX SYSTEM ALERT NOTE.png",
	icon:"http://localhost/_sevian/themes/_common/images/user.png",
	delay:4000,
	autoClose:false,


});

