// JavaScript Document

var sgInput = false;
(function($){
	sgInput = function(opt){
		for(var x in opt){
			this[x] = opt[x];
		}


	}
	sgInput.prototype = {
		init: function(){

		},

		get_valor: function(){

		},
		set_valor: function(value){

		},
		foco: function(){

		},
		set_deshabilitado: function(){

		}

	}
	
	
	
	
})(_sgQuery);
var sgTextSelect = false;




(function($){
	sgTextSelect = function(opt){
		
		selectText.call(this, opt);
		
		
	};
	sgTextSelect.prototype = new selectText({});
	
	sgTextSelect.prototype.init = function(){
		this.ele = this.get();

		if(this.deshabilitado=="si"){
			this.ele.disabled = true;
		}
		
	};
	
	sgTextSelect.prototype.get_valor = function(){
		
		return this.getValue();
	};
	sgTextSelect.prototype.set_valor = function(value){
		return this.setValue(value);
	};
	sgTextSelect.prototype.get_deshabilitado = function(){
		return this.disabled();
		
	};
	sgTextSelect.prototype.foco = function(){
		this.focus();
		
	};
	
	sgTextSelect.prototype.set_clase = function(){
		
		
	};
	
})(_sgQuery);