// JavaScript Document
var datePicker = false;
(function(){
	
	
	datePicker = function (opt){
		opt.visible = true;
		
		this._popup = new sgWindow({
			className: "alfa1",
			classImage: "clock",
			mode: "auto",
			visible: false,
			caption:"Calendario",
			autoClose: true,
			delay:0,
			id: this.id,

		});
		
		opt.target = this._popup.getBody();
		
		opt.onselectday = function(date){
			this.f[this.name+"_auX"].value = this.evalFormat(date.y, date.m, date.d, "%d/%m/%yy");
			this.f[this.name].value = this.evalFormat(date.y, date.m, date.d, "%yy-%mm-%dd");
			this.hide();
		};
		
		sgCalendar.call(this, opt);
		
		this.name = opt.name;
		
	};
	
	datePicker.prototype = new sgCalendar({visible: false});
	
	
	datePicker.prototype.show = function(opt){
		opt.left = "front";
		opt.top = "middle";
		var aux = "", _test = false;
		if(this.f[this.name].value){
			aux = this.f[this.name].value.split("-");
			
			_test = new Date(aux[0]*1, aux[1]*1, aux[2]*1);
			
			if(isNaN(_test.getDay())){

				aux[0] = (new Date()).getFullYear();
				aux[1] = (new Date()).getMonth();
				aux[2] = (new Date()).getDate();
				
			}else{
				this.setValue({y:aux[0]*1, m: aux[1]*1, d: aux[2]*1});
				
			}
				
			
			
			
			
			this.setCal(aux[0]*1, aux[1]*1, aux[2]*1);
		}
		
		this._popup.setMode("auto");
		
		this._popup.show(opt);
	};
	
	datePicker.prototype.hide = function(){
		this._popup.hide();
	};
	
	
})();