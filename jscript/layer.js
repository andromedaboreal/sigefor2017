// JavaScript Document

function layer(){
	this.x = "0px"
	this.y = "0px"
	this.cont_id = "tcanvas"
	this.active = false
	this.visible = false
	this.drag_drop = true
	this.auto_hide = true
	var ME = this 
	addEventSimple(document, "click", this.onclick = function(){
												   
		if(ME.auto_hide){									   
		   ME.hide()
		}// end if
	})


	this.init = function(){
		this.active = true
		this.iframe = document.createElement('iframe');
		this.iframe.position = 'absolute';
		this.iframe.frameBorder = 0;
		this.iframe.scrolling = 'no';
		//this.iframe.style.backgroundColor = "red"

		document.body.appendChild(this.iframe);
	}// end if
	
	
	this.show = function(elem, cont, pos){
		if(this.active==false){
			this.elem = elem.id
			this.init()	
		}
		this.cont=cont
		cW = document.body.clientWidth
		cH = document.body.clientHeight
		sT = document.body.scrollTop
		sL = document.body.scrollLeft		
		
		eW = elem.offsetWidth
		eH = elem.offsetHeight
		eX = elem.offsetLeft
		eY = elem.offsetTop
		x=0
		y=eH
		
		if(pos==0){
			this.x = elem.style.left + "px";
			this.y = elem.style.top + "px";
		}else{
			var pluginRect = this.getElementRect(elem);
			this.x = (pluginRect.left + x) + 'px';
			this.y = (pluginRect.top + y ) + 'px';
			
		}// end if
		
		this.iframe.style.position = 'absolute';
		cont.style.position = 'absolute';
		cont.style.left = this.iframe.style.left = this.x;
		cont.style.top = this.iframe.style.top = this.y;
		
		cont.style.width = this.iframe.style.width = cont.offsetWidth + 'px';
		cont.style.height = this.iframe.style.height = cont.offsetHeight + 'px';

		
		
		//elem.style.zIndex = 10000;
		this.iframe.style.zIndex = elem.style.zIndex - 1;
		
		
		
		cont.style.visibility = 'visible';
		this.iframe.style.visibility = 'visible';
		this.iframe.style.display = '';
		this.visible = true
	}// end fucntion	
	this.hide = function(){
			
		if(this.visible == false){
			return false	
		}// end if
		//document.getElementById(this.cont_id).style.visibility = 'hidden';
		this.cont.style.visibility = 'hidden';
		this.iframe.style.visibility = 'hidden';
		this.iframe.style.display = 'none';
		this.visible = false
	}// end function
	this.getElementRect = function (element) {
		var left = element.offsetLeft
		var top = element.offsetTop
		
		var p = element.offsetParent;
		while (p && p != document.body.parentNode) {
			if (isFinite(p.offsetLeft) && isFinite(p.offsetTop)) {
				left += p.offsetLeft
				top += p.offsetTop
			}// end if
			p = p.offsetParent
		}// end while
		
		return { left: left, top: top, width: element.offsetWidth, height: element.offsetHeight}
	}// end function	
}// end fucntion

function addEventSimple(obj,evt,fn) {
	if (obj.addEventListener)
		obj.addEventListener(evt,fn,false);
	else if (obj.attachEvent)
		obj.attachEvent('on'+evt,fn);
}// end function