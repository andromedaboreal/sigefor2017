var LAYER_CMB_TEXT = new layer()
function cmb_text(canvas, id){
	
	var ME = this
	this.name = id
	this.mode = 1
	
	this.canvas = document.getElementById(canvas)
	this.class_name = false
	this.index_over = -1
	
	this.last_action = ""
	this.changed = false

	this.ele_text = document.createElement("input");
	this.ele_text.type="text"
	this.ele_text.id=id+"_cmb_text"
	this.ele_text.setAttribute("autocomplete","off");
	this.canvas.appendChild(this.ele_text)

	this.ele_value = document.createElement("input");
	this.ele_value.type="hidden"//hidden	
	this.ele_value.id=id
	this.canvas.appendChild(this.ele_value)
	this.click=false
	this.size = false
	
	this.height = 20
	this.rows = 20
	this.ini = false
	this.index=-1
	this.filter = false
	this.cont = LAYER_CMB_TEXT//new layer()
	this.cont.cont_id = id+"_div"
	this.cont.drag_drop = false
	//this.cont.init()
	this.last_scroll = 0

	this.ele_cont = document.createElement("div");
	
	this.ele_cont.id=id+"_div"
	this.ele_cont.style.backgroundColor = "white"
	this.ele_cont.style.fontFamily = "verdana"
	//this.ele_cont.style.borderStyle = "solid"
	//this.ele_cont.style.borderWidth = "1px"
	this.ele_cont.style.position = "absolute"
	

	this.ele_cont.style.overflow="auto"
	
	this.ele_cont.style.height = (this.height * this.rows +2)+"px"
	
	document.body.appendChild(this.ele_cont)
	
	addEventSimple(this.ele_text, "click", function(e){
		if (!e) e = window.event;
		e.cancelBubble = true;			
		if(ME.cont.visible ){
			//
		}else{												
			ME.show()
		}// end if										 
	})// end event		
	
	addEventSimple(this.ele_text, "focus", function(){
		if(ME.cont.visible ){
			ME.cont.hide()	
		}else{
			ME.show()	
		}// end if
	})// end event

	addEventSimple(this.ele_text, "blur", function(){
	   if(!ME.pointer &&	this.value!=""  && ME.filter){
			ME.get_value()
	   }// end if
	})// end event


	addEventSimple(this.ele_text, "keyup", function(e){
		if (arguments[1] != null){
			event = arguments[1];
		}// end if
		if (!e) e = window.event;
		var keyCode = e.keyCode;
		switch (keyCode){
		case 13:
			e.returnValue = false;
			e.cancelBubble = true;
			return false;		
			break;
		case 9:
		case 16:// Shift
		case 17:// Control
		case 40:
		case 38:
		case 37:
		case 39:
		case 27:
			break;
		default:
			ME.show()
			break;
		}// end switch
	
	})// end event

	addEventSimple(this.ele_text, "keydown", function(e){
		if (arguments[1] != null){
			event = arguments[1];
		}// end if
		if (!e) e = window.event;
		var keyCode = e.keyCode;
		switch (keyCode){
		case 13://enter
			ME.get_value()
			e.returnValue = false;
			e.cancelBubble = true;
			return false;			
			break;
		case 9://tab
			ME.cont.hide()
			break;
		case 27://escape
			e.returnValue = false;
			e.cancelBubble = true;
			break;
		case 38://up arrow 
			e.returnValue = false;
			e.cancelBubble = true;
			if(!ME.cont.visible ){
				ME.show()	
			}else{
				ME.move(-1)
			}// end if
			break;
		case 40://down arrow
			e.returnValue = false;
			e.cancelBubble = true;
			if(!ME.cont.visible ){
				ME.show()	
			}else{
				ME.move(1)
			}// end if
			break;
		default:
			ME.filter = true
			break;
		}// end switch
	})// end event
	
	this.init = function(){
		this.ele_text.className = this.class_name
		if(this.size>0){
			this.ele_text.size = this.size
		}// end if
		this.set_value(this.value)
	}// end if

	this.show = function(){
		var ME = this
		var width = 0
		var items = null
		var index_y=false
		
		this.ele_cont.innerHTML = ""
		this.items = new Array()
		this.text = this.ele_text.value
		this.value = this.ele_value.value //|| this.value
		this.value_ini = this.value

		j=0
		this.ele_cont.scrollTop=0
		
		this.index=-1
		
		if(this.parent){
			this.parent_value = this.parent.value
		}else{
			this.parent_value = ""
		}// end if
		
		for(var i in this.data){
			if(this.parent && this.data[i][2]!=this.parent_value){
				continue
				
			}// end if
			
			if(!this.filter || this.text=="" || this.text!="" && this.data[i][1].toLowerCase().indexOf(this.text.toLowerCase()) >= 0){
				if(this.value==this.data[i][0]){
					index_y = j
					
				}// end if
				
				items = document.createElement("div");
				items.className = this.class_item
				items.innerHTML = this.data[i][1];
				items.index = j;
				items.idata = i;
				items.style.height = this.height+"px";
				items.style.whiteSpace = "nowrap";
				
				items.onmouseover = function(e){
					ME.pointer = true
					if(ME.last_action=="mouse"){
						if(ME.class_item){
							if(ME.index>=0){
								ME.items[ME.index].className = ME.class_item
							}// end if					
							if(ME.index_over>=0 && this.index != ME.index_over){
								ME.items[ME.index_over].className = ME.class_item
							}// end if
							this.className = ME.class_item+"_selected"
						}else{
							if(ME.index>=0){
								ME.items[ME.index].style.color=""
								ME.items[ME.index].style.backgroundColor=""
							}// end if					
							if(ME.index_over>=0 && this.index != ME.index_over){
								ME.items[ME.index_over].style.color=""
								ME.items[ME.index_over].style.backgroundColor=""
							}// end if
							this.style.backgroundColor = "blue"
							this.style.cursor = "pointer"
							this.style.color = "white"
						}// end if
					}// end if
					ME.index_over = this.index
				}// end function
				items.onmouseout = function(e){
					ME.pointer = false
				}// end function
				
				items.onclick = function(e){
					ME.click = true	
					ME.last_scroll = ME.ele_cont.scrollTop
					ME.select(this.index)
				}// end function

				items.onmousemove = function(e){
					ME.last_action = "mouse"
					//this.style.cssText = "white-space:nowrap;"
				}// end function

				this.ele_cont.appendChild(items);
				if(items.offsetWidth>width && !this.ini){
					width=items.offsetWidth
				}// end if
				this.items[j]=items
				j++
			}// end if
		}// next
		this.index_over= this.index
		this.length = this.items.length
		
		if(!this.ini)
			this.ele_cont.style.width=width+45+"px"
		if(this.length<this.rows){
			this.ele_cont.style.height = this.length*this.height+"px"
		}else{
			this.ele_cont.style.height = this.rows*this.height+"px"
		}// end if
		if(this.ele_cont.offsetWidth<this.ele_text.offsetWidth){
			this.ele_cont.style.width=this.ele_text.offsetWidth+"px"
		}// end if

		this.cont.show(this.ele_text,this.ele_cont, 1)	
		if(!this.filter && index_y!==false && index_y>=0){
			this.set_pos(index_y)
		}else if(this.index>=0){
			this.set_pos(this.index)
		}// end if
		this.ini=true
	}// end function

	this.move = function(value){
		if(this.index_over>0 && this.last_action=="mouse"){
			this.index = this.index_over
			this.index_over=-1
		}// end if
		this.last_action=""
		var index = this.index + value
		
		if(index<0 || index>this.length-1){
			return false
		}// end if
		var scroll = this.ele_cont.scrollTop

		if(index*this.height < scroll){
			this.ele_cont.scrollTop	= index*this.height
		}else if(index*this.height>(scroll+(this.rows-1)*this.height)){
			if(value>0){
				this.ele_cont.scrollTop	= Math.floor((scroll+this.height)/this.height)*(this.height)//scroll+this.height	
			}else{
				this.ele_cont.scrollTop	= ww=(index*this.height)-(this.rows-1)*this.height
			}// end if
			this.ele_cont.scrollTop	= ww=(index*this.height)-(this.rows-1)*this.height
		}// end if
		

		this.last_scroll=this.ele_cont.scrollTop
		this.select(index)
		return true
	}// end function
	
	this.set_pos =function(index){
		if(index * this.height>=this.last_scroll && index * this.height<=(this.last_scroll*1+this.rows*this.height)){
			this.ele_cont.scrollTop=this.last_scroll
		}else{
			this.ele_cont.scrollTop=index * this.height
		}// end if
		this.select(index)
		return true
	}// end function
	
	this.select = function(index){
		if(this.class_item){
			if(this.index>=0){
				this.items[this.index].className=this.class_item
			}// end if
			this.items[index].className = this.class_item+"_selected"
		}else{
			if(this.index>=0){
				this.items[this.index].style.color=""
				this.items[this.index].style.backgroundColor=""
			}// end if
			this.items[index].style.color="white"
			this.items[index].style.backgroundColor="blue"
		}// end if
		this.set_index(index, this.items[index].idata)
		if(this.value_ini!=this.value){
			this.changed=true
			
		}// end if
	}// end function
	
	this.get_value=function(){
		var text=this.ele_text.value
		var value = false
		for(var i in this.data){
			if(this.data[i][1].toLowerCase()==text.toLowerCase()){
				value = this.data[i][0]
				text = this.data[i][1]
				break;
			}// end if
		}// next
		if(value===false){
			this.ele_value.value=""	
			this.ele_text.value=""	
		}else{
			this.ele_value.value=value	
			this.ele_text.value=text
		}// end if
		this.filter = false
		this.cont.hide()
	}// end function
	
	this.set_index=function(index, idata){
		
		if(this.mode==1){
			this.ele_value.value = this.data[idata][0]	
			this.ele_text.value = this.data[idata][1]	
		}else{
			this.ele_value.value = this.data[idata][1]	
			this.ele_text.value = this.data[idata][1]
		}// end if
		this.filter = false
		this.index = index

	}// end fucntion
	
	this.set_value=function(value){
		for(var i in this.data){
			if(this.mode==0){
				if(this.data[i][0] == value){
					this.ele_value.value = this.data[i][0]	
					this.ele_text.value = this.data[i][1]	
					return true
				}// end if
			}else{
				if(this.data[i][1] == value){
					this.ele_value.value = this.data[i][1]	
					this.ele_text.value = this.data[i][1]	
					return true
				}// end if
			}// end if
		}// next

	}//end function
	
}// end class
/*

cmb = new cmb_text("uncombo","x12")
cmb.class_name="combo1"
cmb.class_item="combo1_item"

cmb.value=17
cmb.size=false
cmb.data = new Array()
cmb.data.push(["1","Uno"])
cmb.data.push(["2","Dos"])
cmb.data.push(["3","Tres"])
cmb.data.push(["4","Cuatro"])
cmb.data.push(["5","Cinco"])
cmb.data.push(["6","Seis"])
cmb.data.push(["7","Siete"])
cmb.data.push(["8","Ocho"])
cmb.data.push(["9","yanny esteban"])
cmb.data.push(["10","yanny Nuñez"])
cmb.data.push(["11","cuatro cientos"])
cmb.data.push(["12","unico"])
cmb.data.push(["13","unidad"])
cmb.data.push(["14","el unico"])
cmb.data.push(["15","otro único"])

cmb.data.push(["16","Cristobal Colon"])
cmb.data.push(["17","hereo de la patria"])
cmb.data.push(["18","colonia"])
cmb.data.push(["19","esteban jose"])
cmb.data.push(["20","tres y cuatro"])
cmb.data.push(["21","cinco y seis"])
cmb.data.push(["22","Linea super larga para prueba"])

cmb.init()

cmb2 = new cmb_text("uncombo2","x10")
cmb2.class_name="combo1"
cmb2.value = 20
cmb2.rows=4

cmb2.data = new Array()
cmb2.data.push(["1","Uno"])
cmb2.data.push(["2","Dos"])
cmb2.data.push(["3","Tres"])
cmb2.data.push(["4","Cuatro"])
cmb2.data.push(["5","Cinco"])
cmb2.data.push(["6","Seis"])
cmb2.data.push(["7","Siete"])
cmb2.data.push(["8","Ocho"])
cmb2.data.push(["9","yanny esteban"])
cmb2.data.push(["10","yanny Nuñez"])
cmb2.data.push(["11","cuatro cientos"])
cmb2.data.push(["12","unico"])
cmb2.data.push(["13","unidad"])
cmb2.data.push(["14","el unico"])
cmb2.data.push(["15","otro único"])

cmb2.data.push(["16","Cristobal Colon"])
cmb2.data.push(["17","hereo de la patria"])
cmb2.data.push(["18","colonia"])
cmb2.data.push(["19","esteban jose"])
cmb2.data.push(["20","tres y cuatro"])
cmb2.data.push(["21","cinco y seis"])

cmb2.init()

	*/