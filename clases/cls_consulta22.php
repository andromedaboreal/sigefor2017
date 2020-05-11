<?php 
/*****************************************************************
creado: 01/09/2007
modificado: 04/10/2007
por: Yanny Nuñez
*****************************************************************/
require_once ("cfg_consulta.php");
require_once ("cfg_campos_con.php");
require_once ("cls_navegador.php");
require_once ("cls_paginador.php");
require_once ("cls_table.php");
class cls_consulta extends cfg_consulta{
	var $panel = 5;
	var $nro_niveles = 0;
	var $nro_campos;
	var $nro_filas;
	var $no_total = array();


	var $pagina = 1;
	var $border = "0";
	var $cellspacing = "2";
	var $cellpadding = "2";
	var $pag_border = "0";
	var $pag_cellspacing = "2";
	var $pag_cellpadding = "2";
	var $pag_width = "";
	var $pag_item_width = "8px";
	var $pag_sin_graficos = false; 
	
	var $car_password = C_CONS_CAR_PASSWORD;

	var $formulario_q = C_CON_FORM_Q;
	var $q_nombre = C_FORM_Q_NOMBRE;
	var $q_width = C_FORM_Q_WIDTH;
	var $q_titulo = C_FORM_Q_TITULO;
	var $q_ex_nombre = C_FORM_Q_EX_NOMBRE;
	var $q_ex_titulo = C_FORM_Q_EX_TITULO;
	var $q_boton_titulo = C_FORM_Q_BOTON_TITULO;
	

	var $texp = array();
	var $tcampo = array();
	
	var $script = "";
	
	var $nro_clases = 2;
	
	
	var $obj_script = "Grid";
	
	var $con_eventos = false;
	
	var $clase_consulta = "";
	var $clase_caption = "";
	var $clase_grupo = "";
	var $clase_ctl_seleccion = "";
	
	var $clase_seleccion = "";
	var $clase_nuevo = "";
	var $clase_contador = "";
	var $clase_contador_over = "";

	
	var $clase_pie_consulta = "";
	var $sin_seleccion = "";
	var $sin_enumeracion = "";
	var $add_claves = "";
	var $elem_script = "";
	var $nombre_grid = "";
	var $accion = "";

	var $title = "";
	var $width = "";
	
	var $formulario_buscar = "";
	var $form_q_width = "";
	var $validaciones = "";
	var $script_load = ""; 
	
	var $clase_filas = ""; 	
	var $referenciar = ""; 	
	var $quitar_un_toque = ""; 	
	var $modo = ""; 	

	var $form_script = ""; 	
	var $estilo_nuevo = ""; 	
	var $title_nuevo = ""; 	
	var $tipo_grid = "";
	var $campos_busqueda  = "";
	//===========================================================
	function control($consulta_x){
		if($consulta_x!=""){
			$this->consulta = $consulta_x;
		}// end if
		if(!$this->nombre){
			$this->nombre = $this->consulta;
		}// end if
		
		
		$this->ejecutar($this->consulta);
		if(isset($this->vform[$this->q_nombre])){
			$this->q = $this->vform[$this->q_nombre];
			if(isset($this->vform[$this->q_ex_nombre])){
				$this->qex = $this->vform[$this->q_ex_nombre];
			}
			
		}

		$this->query = $this->eval_query($this->query);

		if($this->vses["DEBUG"] == "1"){
			$this->deb->dbg($this->panel,$this->nombre,$this->titulo,"consulta=$this->nombre","c","<br><b>Q:</b> ".$this->query);
		}// end if


		




		$cn = &$this->conexion;
		$cn->add_claves = $this->add_claves;
		
		if(!$this->pagina){
			$this->pagina = 1;
		}// end if

		if($this->paginacion=="si"){
			$cn->paginacion = true;
			$cn->reg_pag = $this->reg_pagina;
			$cn->pagina = $this->pagina;
		}// end if

		$this->result = $cn->ejecutar($this->query);
		$this->nro_campos = $cn->nro_campos;
		$this->nro_filas = $cn->nro_filas;
		$this->reg_total = $cn->reg_total;
		$this->nro_paginas = $cn->nro_paginas;
		$this->pagina = $cn->pagina;
		$cn->descrip_campos($this->result);

		$this->cfg = new cfg_campos_con;
		$this->cfg->conexion = &$this->conexion;
		
		$this->cfg->mele_script = $this->mele_script;
		$this->cfg->ele_script = $this->ele_script;
		
		
		$this->cfg->elem = &$cn->campo;
		$this->cfg->vses = &$this->vses;
		$this->cfg->vform = &$this->vform;
		
		$this->cfg->ejecutar($this->consulta);
		$this->elem = &$cn->campo;
		$this->campo = &$this->cfg->campo;


		
		
		switch ($this->tipo){
		case "C_CONSULTA_NORMAL":
			$ele_form = $this->cons_normal();
			break;
		case "C_CONSULTA_PATRON":
			$ele_form = $this->cons_rapido();
			break;
		case "C_CONSULTA_DISENO":
			$ele_form = $this->cons_rapido();
			break;
		case "C_CONSULTA_ARCHIVO":
			$ele_form = $this->cons_rapido();
			break;
		case "C_CONSULTA_RAPIDO":	
		default:
			$ele_form = $this->cons_normal();
			break;		
		}// end switch
		return $ele_form;
	}// end function
	//===========================================================
	function cons_normal(){
		$cn = &$this->conexion;
		$delta = 0;
		//===========================================================
		if($this->enumeracion == "si"){
			$delta++;
		}// end if
		//===========================================================
		if($this->tipo_seleccion != "0"){
			$delta++;
		}// end if
		$alfa=0;

		if($this->grupos != ""){
			$this->grupo = extraer_para($this->grupos);
			$alfa = 1;
		
		}// end if
		if($this->elem_script==""){
			$this->elem_script = "$this->mele_script.campo[\"$this->nombre\"]";
		}// end if

		$ele_script = "$this->mele_script.campo['$this->nombre']";

		if($this->nombre_grid==""){
			$this->nombre_grid = "t".$this->elem_script;
		
		}// en dif
		
		
		if($this->accion != ""){
			$acc = new cfg_accion;
			$acc->ejecutar($this->accion);
			$itm = new cfg_item;
			$itm->panel_default = $this->panel;
			$itm->panel_submit = $this->panel;
			$itm->ejecutar($acc->param);
			$this->accion_evento = stripslashes($itm->eventos);
		
		}// end if
		$script_campo = "";
		
		$this->div_panel = $this->nombre."_divX_".$this->panel;

		$t = new cls_table($alfa,$this->nro_campos+$delta);
		$t->class = $this->clase_consulta;
		$t->width = "100%";
		$t->id = $this->nombre."_tblX_".$this->panel;
		$t->border = $this->border;		
		$t->cellspacing = $this->cellspacing;		
		$t->cellpadding = $this->cellpadding;		
		$f=0;
		
		$t->create_row();
		$t->header_row(-1);
		$clave_aux = "";
		$variables = "";

		

		$q=0;
		$pos_grupos = array();
		$script_data = "";
		$script_x = "";
		$script_linea = "\"cfg_registro_aux\",\"cfg_modo_aux\",\"cfg_id_aux\"";
		$ct = 0;

		for($c=0;$c<$this->nro_campos;$c++){
			if(isset($this->grupo[$cn->campo[$c]->nombre])){
				$span[$cn->campo[$c]->nombre]=$cn->campo[$c]->num;
				$pos_grupos[$cn->campo[$c]->nombre]=$c;
				//$t->merge_row(0,$c ,$cn->campo[$c]->nombre);
			}// end if
			
			
			if($cn->campo[$c]->clave or $cn->campo[$c]->param->primary_key){
				$this->claves[] = $cn->campo[$c]->nombre;
				$clave_aux .= (($clave_aux!="")?"|":"").$cn->campo[$c]->nombre;
			}// end if
			
			if($this->elem[$c]->subformulario){

				require_once("cfg_formulario.php");
				$sf = new cfg_formulario;
				$sf->vform = &$this->vform;
				$sf->vses = &$this->vses;
				$sf->vexp = &$this->vexp;
				$sf->deb = &$this->deb;
				$sf->ejecutar($this->elem[$c]->subformulario);
				$sf->cfg->reg = $this->reg;
				$sf->cfg->detalle = $this->elem[$c]->detalle;
				$sf->cfg->referencia = $this->elem[$c]->referencia;
				
				
				
				/*
				if($this->elem[$c]->relacion){
					$ref_x = extraer_sub_para($this->evaluar_todo($this->elem[$c]->relacion));
					foreach($ref_x as $k => $v){
						$sf->cfg->ref[$k] = $this->valores[$v];
					}// end if
				}// end if
				?*/
				$sf->cfg->orden = $this->elem[$c]->orden;
				//$sf->cfg->crear_data();
				//$this->valores[$this->elem[$c]->nombre] = implode(C_SEP_L,$sf->cfg->data_detalle);
				$this->elem[$c]->sf = &$sf;
				

			
			}// end if
			
			//$variables .= "\n".$this->crear_var($cn->campo[$c]->nombre,"");
			
			$this->cfg->evaluar_data($this->elem[$c]);
			$this->cfg->crear_script($this->elem[$c], $this->elem_script);
			$script_x .= $this->elem[$c]->script;
			
			if($this->elem[$c]->objeto != C_CON_OBJ_OCULTO and $this->elem[$c]->objeto != C_CON_TOBJ_OCULTO){
				$this->elem[$c]->estilo_titulo = $this->estilo_titulo.$this->elem[$c]->estilo_titulo;
				$this->elem[$c]->propiedades_titulo = $this->propiedades_titulo.$this->elem[$c]->propiedades_titulo;
				if($this->elem[$c]->clase_titulo != ""){
					$t->cell[$f+$alfa][$ct+$delta]->class = $this->elem[$c]->clase_titulo;
				}else{
					$t->cell[$f+$alfa][$ct+$delta]->class = $this->clase_titulo;
				}// end if
				if($this->elem[$c]->estilo_titulo != ""){
					$t->cell[$f+$alfa][$ct+$delta]->style = $this->elem[$c]->estilo_titulo;
				}// end if
				if($prop = extraer_para($this->elem[$c]->propiedades_titulo)){
					foreach($prop as $para => $valor){
						eval("\$t->cell[$f+$alfa][$ct+$delta]->$para = \"$valor\";");
					}// next
				}// end if
	
				$t->cell[$f+$alfa][$ct+$delta]->text = $this->elem[$c]->titulo;
				if($this->vses["DEBUG"]=="1"){
					$t->cell[$f+$alfa][$ct+$delta]->onclick = $this->deb->dbcc($this->panel,$this->consulta,$this->elem[$c]->consulta,$this->elem[$c]->tabla,$this->elem[$c]->campo,$this->elem[$c]->configurado_con);
				}// end if
				$t->cell[$f+$alfa][$ct+$delta]->nowrap = "nowrap";
				$ct++;
			}// end if
			
			
			$script_linea .= C_SEP_L."\"".$this->elem[$c]->nombre."\"";
		}// next

		if($alfa>0){
			sort($pos_grupos);
			$t->cell[0][0]->class = $this->clase_grupo;
			for($i=0;$i<count($pos_grupos);$i++){
				if(($i<count($pos_grupos)-1)){
					$t->merge_row(0,$pos_grupos[$i]+$delta,$pos_grupos[$i+1]-$pos_grupos[$i]);
				}else{
					$t->merge_row(0,$pos_grupos[$i]+$delta);
				}// end if
				$t->cell[0][$pos_grupos[$i]+$delta]->text = $this->grupo[$cn->campo[$pos_grupos[$i]]->nombre];
				$t->cell[0][$pos_grupos[$i]+$delta]->class = $this->clase_grupo;
			}// next
			$t->merge_row(0,0,$delta);
		}// end if

		
		$script_data .= "\n$this->elem_script.data[0]=[".$script_linea."];";
		
		if($this->enumeracion == "si"){
			$t->cell[0+$alfa][0]->class = $this->clase_titulo;
			if($this->estilo_titulo!=""){
				$t->cell[0+$alfa][0]->style = $this->estilo_titulo;
			}// end if
			if($prop = extraer_para($this->propiedades_titulo)){
				foreach($prop as $para => $valor){
					eval("\$t->cell[0+$alfa][0]->$para = \"$valor\";");
				}// next
			}// end if
			$t->cell[0+$alfa][0]->text = $this->titulo_enumeracion;
		}// end if
		if($this->tipo_seleccion >0){
			$t->cell[0+$alfa][$delta-1]->class = $this->clase_titulo;
			if($this->estilo_titulo!=""){
				$t->cell[0+$alfa][$delta-1]->style = $this->estilo_titulo;
			}// end if
			if($prop = extraer_para($this->propiedades_titulo)){
				foreach($prop as $para => $valor){
					eval("\$t->cell[0+$alfa][$delta-1]->$para = \"$valor\";");
				}// next
			}// end if
			//$t->cell[0+$alfa][$delta-1]->class = $this->clase_seleccion;
			if($this->tipo_seleccion == "1" or $this->tipo_seleccion == "2"){
				$ele = $this->control_seleccion(0);
				if($this->con_eventos or true){
					if($this->tipo_seleccion == "2"){
						$ele->onclick = "seleccionar_todos($ele_script.iele,this.checked);seleccionar_reg(this,$ele_script.seleccionar_reg());";//"vaciar_form($this->panel)";
					}else{	
						//$ele->checked = true;
						
						
						
						//$ele->onclick = "seleccionar_reg(this,$ele_script.seleccionar_reg());";//"vaciar_form($this->panel)";
						$ele->onclick = "$this->mele_script.f.cfg_modo_aux.value=1;$this->mele_script.f.cfg_registro_aux.value='';$this->mele_script.f.cfg_reg_aux.value='';$this->mele_script.ini_form();";//.set_valor($this->mele_script.campo['$this->nombre'].get_reg());$this->mele_script.f.cfg_registro_aux.value=$this->mele_script.f.cfg_reg_aux.value;$this->mele_script.f.cfg_modo_aux.value=2";
					}// end if
				}// end if
				

				$t->cell[0+$alfa][$delta-1]->text = $ele->control();
			}else{
				$t->cell[0+$alfa][$delta-1]->text = "&nbsp;";
			}// end if
		}// end if
		$f++;
		$reg_actual = 0;
		$this->vexp["PAG_ACTUAL"] = $this->pagina;
		$this->vexp["NRO_FILAS"] = $this->nro_filas;
		$this->vexp["REG_TOTAL"] = $this->reg_total;
		$this->vexp["REG_ACTUALES"] = ($this->pagina-1)*$this->reg_pagina+$this->nro_filas;
		$this->vexp["NRO_PAGINAS"] = $this->nro_paginas;
		
		

		
		if($this->clase_filas != ""){
			$clase_filas = explode(",",$this->clase_filas);
			$n_clase_filas = count($clase_filas);
			$clase_filas_sel = $clase_filas;
			$clase_filas_con = $clase_filas;
		}else if($this->nro_clases > 1){
			$n_clase_filas = $this->nro_clases;
			for($i=0;$i<$this->nro_clases;$i++){
				$clase_filas[$i] = $this->clase_detalle.$i;
				$clase_filas_sel[$i] = $this->clase_seleccion.$i;
				$clase_filas_con[$i] = $this->clase_contador.$i;
			}// next
		}else{
			$n_clase_filas = 1;
			$clase_filas[0] = $this->clase_detalle;
			$clase_filas_sel[0] = $this->clase_seleccion;
			$clase_filas_con[0] = $this->clase_contador;
		}// end if


		while ($rs = $cn->consultar($this->result)){
		
		
			$clase_fila = $clase_filas[($reg_actual) % $n_clase_filas];
			$clase_fila_con = $clase_filas_con[($reg_actual) % $n_clase_filas];
			$clase_fila_sel = $clase_filas_sel[($reg_actual) % $n_clase_filas];
		
			@$f_editable = $rs[$this->fila_editable];
			@$f_checked = $rs[$this->fila_checked];
			@$f_enumerable = $rs[$this->fila_enumerable];
			@$f_check_visible = $rs[$this->fila_check_visible];

			$script_linea="";
			$reg_actual++;
			$this->vreg = $rs;
			$script_linea.="\"".$this->crear_clave($rs,$reg_actual)."\",";
			$script_linea.="\"0\",";
			$script_linea.="$reg_actual";
			
			$this->vexp["REG_ACTUAL"] = $reg_actual;
			
			//===========================================================
			if($this->expresiones_det!=""){
				$this->vexp = array_merge($this->vexp, extraer_para($this->evaluar_exp($this->expresiones_det)));
			}// end if
			
			
			
			if(!$this->referenciar){
				$t->create_row();
			}// end if
			
			
			
			
			$ct = 0;
			for($c=0;$c<$this->nro_campos;$c++){
				$texto = $rs[$c];
				
				if($prop = extraer_para($this->evaluar_exp($this->elem[$c]->parametros))){
					foreach($prop as $para => $valor){
						eval("\$this->elem[$c]->$para = \"$valor\";");
					}// next
				}// end if


				if($this->elem[$c]->subformulario){
				

					
					$elem = $this->elem[$c];
					if($elem->relacion){
		
						$ref_x = extraer_sub_para($this->evaluar_todo($elem->relacion));
		
						foreach($ref_x as $k => $v){
		
							$sf->cfg->ref[$k] = $rs[$v];
						
						}// end if
						
						
					}// end if
					$this->elem[$c]->objeto=10;
					
					
					//$this->valores[$this->elem[$c]->nombre] = implode(C_SEP_L,$sf->cfg->data_detalle);
					$this->elem[$c]->sf->cfg->data_script = "";
					$this->elem[$c]->sf->cfg->crear_data();
				
					$texto = implode(C_SEP_L,$this->elem[$c]->sf->cfg->data_detalle);
				
					$script_campo .= $this->elem[$c]->sf->cfg->data_script;
					$script_campo .= $this->cfg->script_data_reg($this->elem[$c],$this->elem_script,$reg_actual);
				
					//$texto = $this->cfg->eval_detalle($this->elem[$c]->q_detalle);
				
				}// end if
				
				
				$script_linea .= (($script_linea!="")?",":"")."\"".addslashes($texto)."\"";
				if($this->elem[$c]->subformulario == ""){
					if($this->elem[$c]->padre!="" and $this->elem[$c]->data[$rs[$this->elem[$c]->padre]][$texto]!=""){
						$texto = $this->elem[$c]->data[$rs[$this->elem[$c]->padre]][$texto];
					}else if(isset($this->elem[$c]->data[0][$texto])){
						$texto = $this->elem[$c]->data[0][$texto];
					}// end if
				}// end if

				
				$this->vexp["CAMPO_NRO"] = $c;
				$this->vexp["CAMPO_NOMBRE"] = $this->elem[$c]->nombre;
				$this->vexp["CAMPO_TABLA"] = $this->elem[$c]->tabla;
				$this->vexp["CAMPO_VALOR"] = $texto;
				

				if($this->elem[$c]->valor!=""){
					$texto = $this->vexp[$this->elem[$c]->valor];
				}// end if
				
				$texto = $this->evaluar_campo($texto,$c);
				if($this->elem[$c]->sufijo!=""){
					$texto = $texto.$this->elem[$c]->sufijo;
				}// end if
				if($this->elem[$c]->prefijo!=""){
					$texto = $this->elem[$c]->prefijo.$texto;
				}// end if
				if($this->elem[$c]->q_detalle != ""){
				
					$texto = $this->cfg->eval_detalle($this->elem[$c]->q_detalle);
				}// end if

				if($this->elem[$c]->objeto != C_CON_OBJ_OCULTO and $this->elem[$c]->objeto != C_CON_TOBJ_OCULTO){
	
					if($this->elem[$c]->objeto > 1){
						$texto = $this->eval_objeto($this->elem[$c],$texto);
					}// end if			
					$estilo_det = $this->evaluar_exp($this->estilo_det.$this->elem[$c]->estilo_det);
					$propiedades_det = $this->evaluar_exp($this->propiedades_det.$this->elem[$c]->propiedades_det);
	
					if($this->elem[$c]->clase_det!=""){
						$t->cell[$f+$alfa][$ct+$delta]->class = $this->elem[$c]->clase_det;
					}else{
						$t->cell[$f+$alfa][$ct+$delta]->class = $clase_fila;
	
					}// end if
	
					if($estilo_det!=""){
						$t->cell[$f+$alfa][$ct+$delta]->style = $estilo_det;
					}// end if
	
					if($prop = extraer_para($propiedades_det)){
						foreach($prop as $para => $valor){
							eval("\$t->cell[$f+$alfa][$ct+$delta]->$para = \"$valor\";");
						}// next
					}// end if
	
					if($texto==""){
						$texto = "&nbsp;";
					}// end if
					if($this->elem[$c]->objeto == C_CON_OBJ_PASSWORD or $this->elem[$c]->objeto == C_CON_TOBJ_PASSWORD){
						$texto = $this->car_password;
					}
					$t->cell[$f+$alfa][$ct+$delta]->text = $texto;
					$ct++;
				}// end if
			}// next
			if($this->enumeracion == "si"){

				$estilo_det = $this->evaluar_exp($this->estilo_det);
				$propiedades_det = $this->evaluar_exp($this->propiedades_det);
				$t->cell[$f+$alfa][0]->class = $clase_fila_con;
				if($estilo_det!=""){
					$t->cell[$f+$alfa][0]->style = $estilo_det;
				}// end if
				if($prop = extraer_para($propiedades_det)){
					foreach($prop as $para => $valor){
						eval("\$t->cell[$f+$alfa][0]->$para = \"$valor\";");
					}// next
				}// end if
			

				if($f_enumerable=="" or $f_enumerable=="1"){
					$t->cell[$f+$alfa][0]->text = $f;
				}// end if
				
				
				
				if($this->quitar_un_toque!="si" and $this->editable == "si" and ($f_editable=="" or $f_editable == "1")){
					$t->cell[$f+$alfa][0]->onclick = "seleccionar_reg('$this->panel',$ele_script.registro($f));enviar('$this->panel','$this->panel','panel:$this->panel;elemento:formulario;modo:2;','','')";
					$t->cell[$f+$alfa][0]->onmouseover = "this.className='$this->clase_contador_over'";
					$t->cell[$f+$alfa][0]->onmouseout = "this.className='$clase_fila_con'";
				}elseif($this->accion != "" and ($f_editable=="" or $f_editable == "1")){
					//$t->cell[$f+$alfa][0]->onclick = "seleccionar_reg('$this->panel',$ele_script.registro($f));$this->accion_evento";
					
					$t->cell[$f+$alfa][0]->onclick = "		seleccionar_reg($this->panel,	$ele_script.registro($f));$this->mele_script.set_valor($this->mele_script.campo['$this->nombre'].get_reg($f));$this->mele_script.f.cfg_registro_aux.value=$this->mele_script.f.cfg_reg_aux.value;$this->mele_script.f.cfg_modo_aux.value=2;$this->accion_evento";
					$t->cell[$f+$alfa][0]->onmouseover = "this.className='$this->clase_contador_over'";
					$t->cell[$f+$alfa][0]->onmouseout = "this.className='$clase_fila_con'";
				}// end if				

			}// end if
			
			
			
			if($this->tipo_seleccion > 0){
				$ele = $this->control_seleccion($f);
				if($this->modo  >0){
					//$ele->onclick = "$this->elem_script.sel_reg(this,'$f');";//habilitar_form(this);eval_onchange(this)
					$ele->onclick = "seleccionar_reg(this,$ele_script.seleccionar_reg());";//habilitar_form(this);eval_onchange(this)

				}else{
					$ele->onclick = "seleccionar_reg(this,$ele_script.seleccionar_reg());$this->mele_script.set_valor($this->mele_script.campo['$this->nombre'].get_reg());$this->mele_script.f.cfg_registro_aux.value=$this->mele_script.f.cfg_reg_aux.value;$this->mele_script.f.cfg_modo_aux.value=2";
					//$ele->onclick = "seleccionar_reg(this,$ele_script.seleccionar_reg());";//habilitar_form(this);eval_onchange(this)
				}// end if



				$estilo_det = $this->evaluar_exp($this->estilo_det);
				$propiedades_det = $this->evaluar_exp($this->propiedades_det);
				
				if($estilo_det!=""){
					$t->cell[$f+$alfa][$delta-1]->style = $estilo_det;
				}// end if
				if($prop = extraer_para($propiedades_det)){
					foreach($prop as $para => $valor){
						eval("\$t->cell[$f+$alfa][$delta-1]->$para = \"$valor\";");
					}// next
				}// end if
				
				//$t->cell[$f+$alfa][$delta-2]->style = "position:absolute;background:white;width:20";

				if($f_checked == "1"){
					$ele->checked = true;
				
				}// end if
				
				if($f_editable != "" and $f_editable != "1"){
					$ele->disabled = true;
				
				}// end if

				$t->cell[$f+$alfa][$delta-1]->class = $clase_fila_sel;//$this->clase_seleccion;
				if($f_check_visible == "" or $f_check_visible == "1"){
					$t->cell[$f+$alfa][$delta-1]->text = $ele->control();
				}else{
					$t->cell[$f+$alfa][$delta-1]->text = "&nbsp;";
				}// end if
				
				
			}// end if
			$f++;


			$script_data .= "\n$this->elem_script.data[$reg_actual]=[".eval_salto($script_linea)."];";

		}// end while

		//$clase_fila = $clase_filas[($f+1) % $n_clase_filas];
		
		$clase_fila = $clase_filas[($reg_actual) % $n_clase_filas];
		$clase_fila_con = $clase_filas_con[($reg_actual) % $n_clase_filas];
		$clase_fila_sel = $clase_filas_sel[($reg_actual) % $n_clase_filas];

	
		$variables = "";
		$co = 0;
		if($this->formulario != "" and ($this->tipo_grid == "" or $this->tipo_grid == C_CON_GRID_UPDATE)){
			require_once("cfg_formulario.php");
			$t->create_row();

			if($this->enumeracion == "si"){
				$t->cell[$f+$alfa][0]->class = $clase_fila_con;
			}// end if


			$obj = new cfg_formulario;
			$obj->vform = &$this->vform;
			$obj->vses = &$this->vses;
			$obj->vexp = &$this->vexp;
			$obj->deb = &$this->deb;
			$obj->panel = $this->panel;
			
			$obj->ejecutar($this->formulario);
			
			
			
			$cfg = &$obj->cfg;

			require_once("cls_control.php");
			$ctl = new cls_control();
			$ctl->clase = $this->clase;	
			$ctl->vform = &$this->vform;
			$ctl->vses = &$this->vses;
			$ctl->vexp = &$this->vexp;
			$ctl->form_script	= $this->form_script;
			$ctl->mele_script	= $this->mele_script;
			$ctl->panel = $this->panel;	
			$ctl->referenciar = false;
			

			$btn_nuevo = new cls_element_html("button","");
			$btn_nuevo->value = $this->titulo_nuevo;
			$btn_nuevo->class = $this->clase_nuevo;
			$btn_nuevo->style = $this->estilo_nuevo;
			$btn_nuevo->title = $this->title_nuevo;
			$btn_nuevo->onclick = "this.form.cfg_modo_aux.value=1";
			
			$t->cell[$f+$alfa][-1+$delta]->text = $btn_nuevo->control();
			$t->cell[$f+$alfa][-1+$delta]->class = $clase_fila_sel;
			$t->cell[$f+$alfa][-1+$delta]->valign = "top";
			
			$ct = 0;
			for($c=0;$c<$this->nro_campos;$c++){

				if($cfg->elem[$c]->nombre == ""){
					$cfg->crear_elem($this->elem[$c]->nombre);
					$cfg->elem[$c] = &$cfg->elem[$cfg->taux][$this->elem[$c]->nombre];
					$cfg->elem[$c]->control = C_CTRL_HIDDEN;
					//hr($cfg->elem[$c]->nombre);
					/*
					$t->cell[$f+$alfa][$ct+$delta]->text = "&nbsp;";
					$t->cell[$f+$alfa][$ct+$delta]->class = $clase_fila;
					$t->cell[$f+$alfa][$ct+$delta]->valign = "top";
					$t->cell[$f+$alfa][$ct+$delta]->nowrap = "nowrap";
					$ct++;
					*/
					//continue;
				}// end if

				
				if($this->elem[$c]->objeto != C_CON_OBJ_OCULTO and $this->elem[$c]->objeto != C_CON_TOBJ_OCULTO){//if($this->cfg->elem[$c]->oculto != "si"){//
					$cfg->config_ele($cfg->elem[$c]);
					$ctl->control($cfg->elem[$c]);	
					$this->script .= $cfg->elem[$c]->script;	
					$t->cell[$f+$alfa][$ct+$delta]->text = $cfg->elem[$c]->objeto;
					$t->cell[$f+$alfa][$ct+$delta]->class = $clase_fila;
					$t->cell[$f+$alfa][$ct+$delta]->valign = "top";
					$t->cell[$f+$alfa][$ct+$delta]->nowrap = "nowrap";
					//hr($cfg->elem[$c]->nombre.$cfg->elem[$c]->objeto,"red");
					if($cfg->elem[$c]->subformulario){
	
						$this->script .= "\n".$cfg->elem[$c]->snombre.".reg_data = ".$this->mele_script.".campo[\"".$cfg->elem[$c]->nombre."\"].reg_data";
					
					}// end if
					$ct++;
				}else{
					$cfg->elem[$c]->control = C_CTRL_HIDDEN;
					$cfg->config_ele($cfg->elem[$c]);
					$ctl->control($cfg->elem[$c]);	
					$this->script .= $cfg->elem[$c]->script;	
					$variables .=  $cfg->elem[$c]->objeto;
					$co++;
					//hr($cfg->elem[$c]->nombre,"purple");
				}// end if
				
				
				
				
			}// next
		}else if($this->formulario != "" and !$this->referenciar){

			require_once("cfg_formulario.php");

			$obj = new cfg_formulario;
			$obj->vform = &$this->vform;
			$obj->vses = &$this->vses;
			$obj->vexp = &$this->vexp;
			$obj->deb = &$this->deb;
			$obj->panel = $this->panel;
			
			$obj->ejecutar($this->formulario);
		
			$cfg = &$obj->cfg;

			require_once("cls_control.php");
			$ctl = new cls_control();
			$ctl->clase = $this->clase;	
			$ctl->vform = &$this->vform;
			$ctl->vses = &$this->vses;
			$ctl->vexp = &$this->vexp;
			$ctl->form_script	= $this->form_script;
			$ctl->mele_script	= $this->mele_script;
			$ctl->panel = $this->panel;	
			$ctl->referenciar = false;
			
			for($c=0;$c<$this->nro_campos;$c++){
				
				if($this->elem[$c]->objeto == C_CON_OBJ_OCULTO){
					$co++;
				}// end if
				if($cfg->elem[$c]->nombre == ""){
					continue;
				}// end if
				$cfg->elem[$c]->control = C_CTRL_HIDDEN;
				
				$cfg->elem[$c]->validaciones = "";
				$cfg->config_ele($cfg->elem[$c]);
				$ctl->control($cfg->elem[$c]);
				$this->script .= $cfg->elem[$c]->script;	
				$variables .=  $cfg->elem[$c]->objeto;
				
			}// next
		}else{
		
		
		require_once("cfg_campo.php");
			$config = new cfg_campo;
	
			$config->panel = $this->panel;
			$config->vses = &$this->vses;
			$config->vform = &$this->vform;
			$config->vexp = &$this->vexp;
			
			$config->deb = &$this->deb;
	
			$config->clase = $this->clase;
			$config->registro = $this->registro;
			$config->modo = $this->modo;
			$config->ejecutar(($this->formulario!="")?$this->formulario:$this->consulta, $this->query);
			$this->nro_campos = $config->nro_campos;
			
			$cfg = &$config;
	
			require_once("cls_control.php");
			$ctl = new cls_control();
			$ctl->clase = $this->clase;	
			$ctl->vform = &$this->vform;
			$ctl->vses = &$this->vses;
			$ctl->vexp = &$this->vexp;
			$ctl->form_script	= $this->form_script;
			$ctl->mele_script	= $this->mele_script;
			$ctl->panel = $this->panel;	
			$ctl->referenciar = false;
			
			for($c=0;$c<$this->nro_campos;$c++){

				$cfg->elem[$c]->control = C_CTRL_HIDDEN;
				
				$cfg->elem[$c]->validaciones = "";
				$cfg->config_ele($cfg->elem[$c]);
				$ctl->control($cfg->elem[$c]);
				$this->script .= $cfg->elem[$c]->script;	
				$variables .=  $cfg->elem[$c]->objeto;
				if($this->elem[$c]->objeto == C_CON_OBJ_OCULTO){
					$co++;
				}// end if

			}// next
		
		
		
		
			/*$ele_h = new cls_element_html("text");
			for($c=0;$c<$this->nro_campos;$c++){
				$ele_h->name = $this->elem[$c]->nombre;
				$ele_h->value = "";
				$variables .=  $ele_h->control();
			}// next*/
		}// end if


		//===========================================================

		
		$div = new cls_element_html("div");
		
		$div->property = "ajustable=si";
		$div->style = "overflow:auto;overflow-x:auto";
		$div->id=$this->div_panel;
		$t->cols = $t->cols - $co++;;//$this->cfg->campos_ocultos;
		$div->inner_html = $t->control();
		
		$this->script .= $this->gen_script(); 
		$this->script .= $script_x; 
		$this->script .= $script_data; 
		$this->script .= $this->gen_script_fin(); 
		//$this->script="";
		$this->script .= $script_campo;		
		//===========================================================
		$div_caption = "";
		if($this->titulo!=""){
			$div_x = new cls_element_html("div");
			$div_x->width = $this->width;
			$div_x->class = $this->clase_caption;
			$div_x->inner_html = $this->titulo;
			if($this->title!=""){
				$div_x->title = $this->title;
			}// end if
			
			$div_caption = $div_x->control();
		}// end if
		//===========================================================
		$div_pie_consulta = "";
		$pie_consulta = $this->evaluar_exp($this->pie_consulta);
		if($pie_consulta!=""){
			$div_x = new cls_element_html("div");
			$div_x->width = $this->width;
			$div_x->class = $this->clase_pie_consulta;
			$div_x->inner_html = $pie_consulta;
			$div_pie_consulta = $div_x->control();
		}// end if
		//===========================================================
		$nav = $this->crear_navegador();
		$pag = $this->crear_paginador();
		$buscador = $this->crear_busqueda();
		//===========================================================
		$ele_con = new cls_element_html("hidden",$this->consulta);
		
		
		return $div_caption.$buscador.$div->control().$div_pie_consulta.$pag.$nav.$variables.$ele_con->control();
	}// end fucntion
	//===========================================================
	function crear_busqueda(){
		$buscador = "";
		
		if($this->campos_busqueda !="" and $this->busqueda == C_SI){

			if($this->formulario_buscar==""){	

				
				$this->vexp["FORM_Q_NOMBRE"] = $this->q_nombre;
				$this->vexp["FORM_Q_WIDTH"] = ($this->form_q_width)?$this->form_q_width:"100%";
				$this->vexp["FORM_Q_CLASE"] = $this->clase."_form_q";
				$this->vexp["FORM_Q_TITULO"] = $this->q_nombre;
				$this->vexp["FORM_Q_VALOR"] = $this->q;
				$this->vexp["FORM_Q_EX_NOMBRE"] = $this->q_ex_nombre;
				$this->vexp["FORM_Q_EX_TITULO"] = $this->q_ex_titulo;
				$this->vexp["FORM_Q_EX_CLASE"] = $this->clase."_form_q_ex";
				$this->vexp["FORM_Q_EX_CHECKED"] = ($this->qex)?"checked":"";
				$this->vexp["FORM_Q_BOTON_TITULO"] = $this->q_boton_titulo;
				
				$this->vexp["FORM_Q_BOTON_CLASE"] = $this->clase."_form_q_boton";
				$this->vexp["FORM_Q_BOTON_CLICK"] = "enviar(false,$this->panel,'panel:$this->panel;pagina:1;',0,'');";
				$buscador = $this->evaluar_exp($this->formulario_q);





				
				/*
				$t2 = new cls_table(1,2);
				$t2->width = ($this->form_busq_width)?$this->form_busq_width:"100%";
				//$t2->align=($this->form_busq_align)?$this->form_busq_align:"left";
			
				$t2->class = $this->clase."_form_buscar";
				$t2->header_col(0);
				$ele_x = new cls_element_html("text","q");
				$ele_x->value = $this->q;
	
				$t2->cell[0][0]->text = "Buscar";//C_CONS_BUSQUEDA;
				$t2->cell[0][0]->width = "200";//C_CONS_BUSQUEDA;
				$t2->cell[0][1]->text = $ele_x->control();
	

				$ele_x = new cls_element_html("submit","qwqwqw");
				$ele_x->value = "Buscar";
				$ele_x->class = "green3";
				$ele_x->onclick="$this->mele_script.f.cfg_pagina_aux.value=1";
				$t2->cell[0][1]->text .= $ele_x->control();
	
				$ele_x = new cls_element_html("checkbox","q_ex");
				$ele_x->checked = $this->qex;
				$ele_x->value = "1";

	
				$t2->cell[0][1]->nowrap = "nowrap";
				$t2->cell[0][1]->text .= $ele_x->control();
				$t2->cell[0][1]->text .= "Busqueda Exacta";//C_BUSQ_EXACTA;
	
				$t2->cell[0][0]->class = $this->clase."_form_buscar";
				$t2->cell[0][1]->class = $this->clase."_form_buscar";
				$t2->cell[0][2]->class = $this->clase."_form_buscar";
				$t2->cell[0][3]->class = $this->clase."_form_buscar";
				$t2->cell[0][4]->class = $this->clase."_form_buscar";
	

*/
			
			
			
	/*			$t2 = new cls_table(2,2);
				$t2->width = ($this->form_busq_width)?$this->form_busq_width:"40%px";
				$t2->align=($this->form_busq_align)?$this->form_busq_align:"left";
			
				$t2->class = $this->clase."_form_buscar";
				$t2->header_col(0);
				$ele_x = new cls_element_html("text","q");
				$ele_x->value = $this->q;
	
				$t2->cell[0][0]->text = "Buscar";//C_CONS_BUSQUEDA;
				$t2->cell[0][1]->text = $ele_x->control();
				$ele_x = new cls_element_html("checkbox","q_ex");
				$ele_x->checked = $this->qex;
				$ele_x->value = "1";
	
				$t2->cell[1][0]->text = "Busqueda Exacta";//C_BUSQ_EXACTA;
				$t2->cell[1][0]->nowrap = "nowrap";
				$t2->cell[1][1]->text = $ele_x->control();
	
				$t2->cell[0][0]->class = $this->clase."_form_buscar";
				$t2->cell[0][1]->class = $this->clase."_form_buscar";
				$t2->cell[1][0]->class = $this->clase."_form_buscar";
				$t2->cell[1][1]->class = $this->clase."_form_buscar";
	
				$ele_x = new cls_element_html("submit","qwqwqw");
				$ele_x->value = "Buscar";
				$ele_x->class = "green3";
				$ele_x->onclick="$this->mele_script.f.cfg_pagina_aux.value=1";
				$t2->cell[1][1]->text .= $ele_x->control();
*/
			
			
				
				//$buscador = $t2->control()."<br>";
			}else{
			
				require_once("cls_formulario.php");
				$obj = new cls_formulario();
				$obj->mele_script = $this->mele_script;
				$obj->ele_script = $this->ele_script;
				$obj->vform = &$this->vform;
				$obj->vses = &$this->vses;
				$obj->vexp = &$this->vexp;
				$obj->panel = $this->panel;
				
				$obj->de_sesion = true;
				$obj->con_valores = true;
				$obj->valores  = $this->vses["VSFORM"];

				
				$obj->con_form = false;
				$obj->pag =  1;
				$obj->modo = C_MODO_INSERT;
				$this->script .= $obj->script;
				$this->script .= $obj->script_load;
				$buscador = $obj->control($this->formulario_buscar);
			}// end if	


	
		}// end if		
	
	
		return $buscador;	
	}// end function

	//===========================================================
	function crear_formulario($formulario_x){
		//require_once("cfg_formulario.php");
		

		return $obj;
		$obj->script_form = "frm_$this->panel";
		$obj->felem_script = "frm[$this->panel]";
		$obj->elem_formulario = $obj->script_form;
		$obj->tipo = C_FORMULARIO_DINAMICO;
		$obj->con_form = false;
		$obj->titulo = $this->nombre;
		$obj->pag =  $st->pag_form;
		if ($obj->pag<=0){
			$obj->pag=1;
		}// end if
		//$obj->registro = $this->vform["cfg_registro_aux"];
		$obj->modo = $this->modo;
		$this->script .= $obj->script;
		$this->script .= $obj->script_load;
		return $obj;
	}// end fucntion
	//===========================================================
	function crear_var($nombre_x,$valor_x){
		$ele = new cls_element_html("text",$nombre_x);
		$ele->value = $valor_x;
		return $ele->control();

	}// end function
	//===========================================================
	function eval_objeto(&$ele_x,$valor){
		switch ($ele_x->objeto){
		case C_CON_OBJ_IMAGEN:
		case C_CON_TOBJ_IMAGEN:
			$ele = new cls_element_html("img");
			$ele->src = $ele_x->path_imagen.$valor.(($ele_x->nocache)?"?nocache=".date("YmdHis"):"");//."?".date("YmdHis");
			if($prop = extraer_para($ele_x->para_objeto)){
				foreach($prop as $para => $valor){
					eval("\$ele->$para = \"$valor\";");
				}// next
			}// end if
			return  $ele->control();
			break;
		case C_CON_OBJ_VINCULO:
		case C_CON_TOBJ_VINCULO:
			$ele = new cls_element_html("a");
			$ele->inner_html = $valor;
			$ele->href = $valor;
			if($prop = extraer_para($ele_x->para_objeto)){
				foreach($prop as $para => $valor){
					eval("\$ele->$para = \"$valor\";");
				}// next
			}// end if
			return $ele->control();
			break;
		case 9:
			

			
			$data = explode(",",$valor);

$cadena = "";
foreach($data as $k => $v){
	$cadena .= "<li>$v</li>" ;

}
return "<ol>$cadena</ol>";

			break;
		case 10:
			$data = explode(",",$valor);
			$n_ele = count($data);
			$cols = 2;
			$fils = ceil($n_ele/$cols);
			$t = new cls_table($fils,$cols);
			$t->width="100%";
			$t->style = "background-color: transparent;";
			$t->mode_text = C_MODO_VECTOR;
			$porc_x = floor(100/$cols)."%";
			$i=0;
		
			for($f=0;$f<$fils;$f++){
				for ($c=0;$c<$cols;$c++){
					$t->cell[$f][$c]->style = "background-color: transparent;";
					$t->cell[$f][$c]->width = $porc_x;					
					if($data[$i]!=""){
						$t->text[$i] = "<li>".$ele_x->data[0][$data[$i]]."</li>";
					}// end if
					$i++;
				}// next
			
			}// next
			return $t->control();
			break;
			
		case C_CON_OBJ_OCULTO:
		case C_CON_TOBJ_OCULTO:
			
		}// end switch
	}// end fucntion
	//===========================================================
	function control_seleccion($fila=""){
		switch ($this->tipo_seleccion){
		case "1":
		case "sencillo":
		
			$ele = new cls_element_html("radio",$this->nombre."_chkX");
			$ele->id = $this->nombre."_chkX";
			$ele->class = $this->clase_ctl_seleccion;
			$ele->value = $fila;
			
			return $ele;
			break;
		case "2":
		case "multiple":
			$ele = new cls_element_html("checkbox",$this->nombre."_chkX");
			$ele->id = $this->nombre."_chkX";
			$ele->class = $this->clase_ctl_seleccion;
			$ele->value = $fila;
			return $ele;
			break;
		case "3":
		case "vinculo":
			$ele = new cls_element_html("a");
			$ele->inner_html = $this->titulo_editar;
			$ele->href = "#".$fila;
			$ele->class = $this->clase_ctl_seleccion;
			return $ele;
			break;
		}// end switch
	}// end function
	//===========================================================
	function crear_navegador($nav_x=""){
		if($nav_x!=""){
			$this->navegador = $nav_x;
		
		}// end if
		
		$nav = new cls_navegador();
		$nav->vses = &$this->vses;
		$nav->vform = &$this->vform;
		$nav->vexp = &$this->vexp;
		$nav->deb = &$this->deb;
		$nav->clase = $this->clase;
		$nav->panel_default = $this->panel_default;
		$nav->panel = $this->panel;
		return $nav->control($this->navegador);

	}// end function
	//===========================================================
	function crear_paginador(){
		if($this->paginador == ""){
			return "";
		}// end if

		$pag = new cls_paginador();
		$pag->panel = $this->panel;
		$pag->nro_paginas = $this->nro_paginas;
		$pag->pag_bloque =  $this->pag_bloque;
		
		$pag->pagina = $this->pagina;
		$pag->clase = $this->clase;
		$pag->vexp = &$this->vexp;
		$pag->border = $this->pag_border;
		$pag->cellspacing = $this->pag_cellspacing;
		$pag->cellpadding = $this->pag_cellpadding;
		$pag->width = $this->pag_width;
		$pag->item_width = $this->pag_item_width;
		$pag->sin_graficos = $this->pag_sin_graficos;
		return $pag->control($this->navegador);
	}// end function
	//===========================================================
	function evaluar_prop($propiedades,$estilo){
		$propiedades_x = "";	
		if($estilo != ""){
			$propiedades_x .= "style=\"".reparar_sep($estilo)."\" ";
		}// end if
		if($prop = extraer_para($propiedades)){
			foreach($prop as $para => $val){
				if ($val==""){
					continue;
				}// end if
				$propiedades_x .= "$para=\"$val\" ";
			}// next
		}// end if
		return $propiedades_x;
	}// end function
	//===========================================================
	function evaluar_campo($valor,$j){
		if($this->elem[$j]->formato != ""){
			$valor = sprintf($this->elem[$j]->formato,$valor);
		}else{
			if ($this->elem[$j]->sin_formato != "si"){
				switch ($this->elem[$j]->meta){
				case C_TIPO_D:
					$valor = formato_fecha($valor);
					break;
				case C_TIPO_T:
					if($valor !=""){
							if($this->elem[$j]->hora24=="si"){
								$valor = date("H:i:s",strtotime($valor));
							}else{
								$valor = date("h:i A",strtotime($valor));
						}// end if
					}// end if
					break;
				case C_TIPO_N:
				case $this->elem[$j]->numerico == "si";
					if(is_numeric($valor)){
						if($this->elem[$j]->decimales != ""){
							$decimales = $this->elem[$j]->decimales;
						}else{
							$decimales = $this->decimales;
						}//end if
						$valor = number_format($valor,$decimales,$this->sep_decimal,$this->sep_mil);
					}// end if
					break;
				case C_TIPO_I:
				case $this->elem[$j]->entero == "si";
					if(is_numeric($valor)){
						if($this->elem[$j]->decimales != ""){
							$decimales = $this->elem[$j]->decimales;
						}else{
							$decimales = 0;
						}//end if
						$valor = number_format($valor,$decimales,$this->sep_decimal,$this->sep_mil);
					}// end if
					break;
				default:
					break;
				}// end switch
			}// end if				
		}// end if				
		if(!$this->elem[$j]->html){
			$valor = htmlentities($valor);
		}// end if
		if($this->elem[$j]->ancho != ""){
			$valor = substr($valor,0,$this->elem[$j]->ancho);
		}// end if
		return $valor;
	}// end function
	//===========================================================
	function crear_clave($rs,$n){
		$aux="";
		for($i=0;$i<count($this->claves);$i++){
		
			$aux .= (($i>0)?C_SEP_L:"").$this->claves[$i].C_SEP_E.$rs[$this->claves[$i]];
		}// next
		return $aux;
	}// end function
	//===========================================================
	function evaluar_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas,true);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
}// end class
?>