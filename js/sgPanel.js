// JavaScript Document

var sgPanel = false;
(function($){
	
	var divHidden = false;
	$(window).on("load", function(){
		divHidden = $.create("div").style({"display":"none"});
		
		
		$("").append(divHidden);
	});
	
	var getFormData = function(form){
		
		
		
		
		var f = $(form).get();
		
		var str = "";
		var n = f.elements.length;
		
		for(var x = 0; x < n; x++){
			if(f.elements[x].name){
				str += f.elements[x].name + "=" + encodeURIComponent(f.elements[x].value) + "&";
			}
		}
		return str;
	};
	
	sgPanel = {
		w: [],
		mainPanel: 4,
		getForm: function(obj, _panel){
			var panel = VG_PANEL_DEFAULT;
			if(typeof(obj) == "object"){
				if(obj.type == "form"){
					panel = obj.cfg_panel_aux.value;
				}else if(obj.form){
					panel = obj.form.cfg_panel_aux.value;
				}else{
					panel = _panel;
				}
			}else if(obj != "0" && obj !== 0 || !obj){
				panel = _panel;
			}
			return panel;			
			
		},
		
		send: function(obj, opt, win){
			
			var panel = this.getForm(obj, opt.panel || false);

			if(!frm[panel]){
				win = this.newPanel(panel);
			}
			var f = frm[panel].f;
			
			if(opt.target){
				
				
				//getFormData(f);
				window.open(opt.target + "?" + "cfg_tgt_aux=2&cfg_ins_aux=" + f.cfg_ins_aux.value + "&" + (opt.vparams || ""));
				return false;
				
			}
			
			
			
			
			if(this.w[panel]){
				
				win = this.w[panel];
				this.w[panel].show({left:"center", top: "top"});
			}
			
			var ME = this;
			var ajax = new sgAjax({
				url: "index.php",
				method: "post",
				form: f,
				onSucess:function(xhr){
					var p = JSON.parse(xhr.responseText);
					//var win = false;
					for(var x in p){
						//win = false;
						if(p[x].targetId){
							if(ME.newPanel(x)){
								win = ME.w[x];
							}
						}

						if(p[x].debug && sgDebug){

							sgDebug.console(p[x].debug);
							continue;
						}

						if(p[x].titulo && x == ME.mainPanel){

							document.title = p[x].titulo;
						}
						//$(p[x].targetId).style().display = "inline";
						sgFragment.evalJson(p[x]);

						if(win){
							win.setCaption(p[x].titulo);
							win.show({left:"center", top: "top"});
						}

					}

				},

				onError: function(xhr){

				},
				waitLayer:{
					class:"wait",
					target: f,

					message:false,
					icon:""},

			});				
			
			if(frm[panel] && opt.valid >= 1 && !frm[panel].validar(opt.valid)){

				return false;
			}
			
			if(opt.confirm && !confirm(opt.confirm)){
				return false;
			}
			
			if(f.cfg_sw_aux.value == f.cfg_sw2_aux.value){
				if (f.cfg_sw_aux.value != 1){
					f.cfg_sw_aux.value = 1;
				}else{
					f.cfg_sw_aux.value = 0;
				}
			}
			
			if(opt.params){
				if(typeof(opt.params) == "object"){
					f.cfg_param_aux.value = JSON.stringify(opt.params);
				}else{
					f.cfg_param_aux.value = opt.params;
				}
			}
			
			f.cfg_async_aux.value = (opt.async)?1:0;
			
			
			
			if(opt.async){
				ajax.send();
				return false;
			}else{
				f.submit();
				return false;
			}
			
		},
		
		sendPage: function(panel, page){
			
			this.send(false, {
				async: true,
				panel: panel,
				params:{
					panel: panel,
					pagina: page
				}
			});
			return false;
		},
		
		setWindow: function(panel){
			
			if(this.w[panel]){
				return this.w[panel]; 
			}
			
			$("sg_panel_" + panel).style().display = "inline";
			
			this.w[panel] = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "auto",
				visible: false,
				caption:"",
				autoClose: false,
				x:"center",
				y:"top",
				left:"center",
				top:"top",
				delay:0,
				_id: this.id,
				child: $("sg_panel_" + panel),

			});
			
			return this.w[panel];
		},
		
		winPanel: function(opt){
			
			var win = this.setWindow(opt.panel);
			
			//win.show({left: "center",top: "top"});
			//win.show({left:true, top:true});
			win.show({});
			opt.isWin = true;
			this.send(false, opt, win);
		},
		
		
		setRecord: function(obj, record){
			
			
			var panel = this.getForm(false, obj);
			
			var f = frm[panel].f;

			f.cfg_reg_aux.value = record;
		},
		
		newPanel: function(panel){
			
			if(document.getElementById("sg_panel_" + panel)){
				return true;
			}
			
			var span = divHidden.create("span");
			span.prop({id:"sg_panel_" + panel});
			
			var _f = span.create("form").prop({"name":"frm_" + panel,"method":"post", "action":"index.php"});
			
			_f.create("input").prop({type:"hidden", name:"cfg_sw_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_sw2_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_param_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_async_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_est_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_reg_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_formulario_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_vista_aux"});
			_f.create("input").prop({type:"hidden", name:"cfg_ins_aux", value:document.forms.frm_4.cfg_ins_aux.value});
			_f.create("input").prop({type:"hidden", name:"cfg_tgt_aux", value:document.forms.frm_4.cfg_tgt_aux.value});
			
			frm[panel] = {
				f: _f.get()
			};
			return this.setWindow(panel);
			//this.setWindow(panel).show({left:"center",top:"top"});
			//this.show({});
			
			
			
		},
	};
})(_sgQuery);

