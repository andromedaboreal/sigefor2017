// JavaScript Document



var ajax = (function(){


	var active = [];
	var index = 0;


	var HttpRequest = function(){
		if(window.XMLHttpRequest){
			return new XMLHttpRequest();
		}else if(window.ActiveXObject){
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		return false;
	};	
	
	var onReady = function(onSucess, onError, layerWait, index){
		
		return function(){
			
			if (this.readyState === 4){
				
				delete active[index];
				
				if(layerWait){
					layerWait.hide();
					layerWait = null;
				}
				if(this.status === 200){
					onSucess(this);
					return true;
				}
				onError(this, this.status);
				return false;				
				
			}
			
		};

		
	};	
	
	var createLayerWait = function(opt){
		return _sgWaitLayer.create(opt);
	};
	
	var req = function(opt){
		var params = {};
		params.url = "";
		params.method = "GET";
		params.charset = "";
		params.form = "";
		params.layerWait = "";
		params.async = false;
		params.contentType = "";
		params.onSucess = function(){};
		params.onError = function(){};
		params.tiemout = 0;
		params.layerWait = false;
		params.trigger = false;
		params.priority = 0;//0:
		
		
		
		for(var x in opt){
			if(opt.hasOwnProperty(x)){
				params[x] = opt[x];
			}
		}
		
		/*acciones en caso de reenvío desde un mismo desencadenador*/
		if(params.trigger){
			var found = false;
			for(x in active){
				if(active[x].trigger === params.trigger){
					found = active[x];
					break;
				}
			}
			
			if(found !== false){
				if(params.priority === 1){
					return found.XHR;
				}else if(params.priority === 2){
					found.XHR.abort();
				}else{
					if(found.layerWait){

					}
				}
			}
		}
		
		
		
		var 
			XHR = HttpRequest(),
			date = new Date(),
			rnd =  date.getTime() + (Math.random() * 100).toFixed(0),
			layerWait = false;
		
		
		if(params.layerWait){
			layerWait = createLayerWait(params.layerWait);
		}	


		active[index] = {XHR: XHR, trigger: params.trigger, layerWait: layerWait};
		
		XHR.onreadystatechange = onReady(params.onSucess, params.onError, layerWait, index);

		//ajax.onload = onReady(params.onSucess, params.onError, params.layerWait, index);


		//ajax.timeout = params.timeout;
	
		if(params.method.toUpperCase() === "GET"){
			XHR.open("GET", params.url + "?" + "rnd=" + rnd + params.params, params.async);
			XHR.setRequestHeader("Content-Type", "text/plain;charset=utf-8");
			XHR.send(null);
			
		
		}else{
			XHR.open("POST", params.url, params.async);
			XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
			XHR.send("&rnd=" + rnd + params.params);
		
		}// end if
		
		index++;
		return XHR;
		
		
	};
	
	
	
	return {
		send: function(opt){
			return req(opt);
			
		},
		
		formData: function(){
			
		},	
		
	};
	
	
}());
	
	
	


function x1(e){
	ajax.send({
		priority:2,
	trigger:e,
	url:"ajax.php",
	_method:"",
	_charset:"",
	form:"",
	layerWait:{
		class:"wait",
		target:"cc",
		type:"",
		modal:false,
		text:"Espere un segundo",
		icon:""},
	async:true,
	contentType:"",
	onSucess:function(XHR){
		JSON.parse(XHR.responseText);
		
		//alert(ajax.onerror);
		//alert(r.Q);
		
	},
	onError:function(XHR, status){
		db("error"+XHR+ status);	
	},
	timeout:0,
		
	
	
});	
	
}

function x2(e){
	ajax.send({
		priority:1,
	trigger:e,
	url:"ajax.php",
	_method:"",
	_charset:"",
	form:"",
	layerWait:{
		class:"wait",
		target:"cc",
		type:"",
		modal:false,
		text:"Espere un segundo",
		icon:""},
	async:true,
	contentType:"",
	onSucess:function(XHR){
		JSON.parse(XHR.responseText);
		
		//alert(ajax.onerror);
		//alert(r.Q);
		
	},
	onError:function(XHR, status){
		alert("error"+XHR+ status);	
	},
	timeout:0,
		
	
	
});	
	
}


function x3(e){
ajax.send({
	trigger:e,
	url:"ajax.php",
	_method:"",
	_charset:"",
	form:"",
	layerWait:{
		class:"wait",
		target:"cc",
		type:"",
		modal:false,
		text:"Espere un segundo",
		icon:""},
	async:true,
	contentType:"",
	onSucess:function(XHR){
		JSON.parse(XHR.responseText);
		
		//alert(ajax.onerror);
		//alert(r.Q);
		
	},
	onError:function(XHR, status){
		alert("error"+XHR+ status);	
	},
	timeout:0,
		
	
	
});	
	
}
/*
var e=false;
ajax.send({
	trigger:e,
	url:"ajax.php",
	_method:"",
	_charset:"",
	form:"",
	layerWait:{
		class:"wait",
		target:"cc",
		type:"",
		modal:false,
		text:"Espere un segundo",
		icon:""},
	async:true,
	contentType:"",
	onSucess:function(XHR){
		JSON.parse(XHR.responseText);
		
		//alert(ajax.onerror);
		//alert(r.Q);
		
	},
	onError:function(XHR, status){
		alert("error"+XHR+ status);	
	},
	timeout:0,
		
	
	
});

*/
