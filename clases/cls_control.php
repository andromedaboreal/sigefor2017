<?php
/*****************************************************************
creado: 20/03/2007
modificado: 11/07/2007
modificado: 08/09/2008
por: Yanny Nu�ez
*****************************************************************/
//require_once("cls_element_html.php");
//require_once("cls_table.php");
//require_once("funciones.php");





//===========================================================
class cls_control{
	var $modo="";
	var $id = "";
	var $nombre = "";
	var $tipo = "text";
	var $clase = "";
	var $valor = "";

	var $caption = "";
	var $title = "";
	var $index = "";
	var $propiedades = "";
	var $parametros = "";
	var $eventos = "";

	var $data = false;
	var $maxlength = "";
	var $max_longitud = C_MAXSIZE;
	var $longitud = "";
	var $solo_lectura = false;
	var $deshabilitado = false;
	var $rows = C_AREA_ROWS;
	var $cols = C_AREA_COLS;
	
	var $tabla_cols = C_LISTA_COLS;
	var $tabla_width = C_LISTA_WIDTH;
	var $tabla_cellspacing = C_LISTA_CELLSPACING;
	var $tabla_cellpadding = C_LISTA_CELLPADDING;
	var $tabla_border = C_LISTA_BORDER;
	var $tabla_clase = "";


	var $tipo_archivo = "";
	var $valor_si = 1;

	var $rows_min = 1;

	
	var $estilo;


	//var $scf = "frm[4]";
	public $referenciar = false;

	public $padre = false;
	public $propertys = false;
	public $tabla_estilo = false;
	
	public $type_control = array(
		C_CTRL_TEXT => "",
		C_CTRL_HIDDEN => "",
		C_CTRL_PASSWORD => "",
		C_CTRL_TEXTAREA => "",
		C_CTRL_SELECT => "",
		C_CTRL_RADIO => "",
		C_CTRL_CHECKBOX => "",
		C_CTRL_MULTIPLE => "x_multiple",
		C_CTRL_MULTIPLE2 => "x_multiple",
		C_CTRL_LABEL => "",
		C_CTRL_FILE => "",
		C_CTRL_CESTA => "x_cesta",
		C_CTRL_CALENDARIO => "x_calendario",
		C_CTRL_DATE_TEXT => "",
		C_CTRL_DATE_LIST => "",
		C_CTRL_TIME_TEXT => "",
		C_CTRL_TIME_LIST => "",
		C_CTRL_SET => "x_checkbox",
		C_CTRL_SET2 => "x_radio",
		C_CTRL_GRID => "",
		C_CTRL_COMBO_TEXT => "",
		C_CTRL_TEXT_DESCRIP => "",

		C_CTRL_SG_SELECT => "sgSelect",
		C_CTRL_SG_CALENDAR => "sgCalendar",
		C_CTRL_SG_COLOR => ""
	
	);
	
	//===========================================================
	function control(&$elem){
		global $INPUTS;
		$this->scf = $this->mele_script;
		
		if(isset($INPUTS[$elem->control])){
			
			return $this->extraInput($elem, $this->panel);
			
		}

		

		$elem->snombre = $this->scf.".campo[\"$elem->nombre\"]";


		$scf = &$this->scf;
		//===========================================================
		/*
		
		$sc_control = array(
						C_CTRL_TEXT => "",
						C_CTRL_HIDDEN => "",
						C_CTRL_PASSWORD => "",
						C_CTRL_TEXTAREA => "",
						C_CTRL_SELECT => "",
						C_CTRL_RADIO => "",
						C_CTRL_CHECKBOX => "",
						C_CTRL_MULTIPLE => "x_multiple",
						C_CTRL_MULTIPLE2 => "x_multiple",
						C_CTRL_LABEL => "",
						C_CTRL_FILE => "",
						C_CTRL_CESTA => "x_cesta",
						C_CTRL_CALENDARIO => "x_calendario",
						C_CTRL_DATE_TEXT => "",
						C_CTRL_DATE_LIST => "",
						C_CTRL_TIME_TEXT => "",
						C_CTRL_TIME_LIST => "",
						C_CTRL_SET => "x_checkbox",
						C_CTRL_SET2 => "x_radio",
						C_CTRL_GRID => "",
						C_CTRL_COMBO_TEXT => "",
						C_CTRL_TEXT_DESCRIP => "",
			
						C_CTRL_SG_SELECT => "sgSelect",
						C_CTRL_SG_CALENDAR => "sgCalendar"
		);	
		
		if($tipo = $sc_control[$elem->control])	{
			$elem->script .= "\n".$scf.".crear(\"$elem->nombre\",\"$tipo\");";
		}else{
			$elem->script .= "\n".$scf.".crear(\"$elem->nombre\");";
		}// end if
		*/
		if($tipo = $this->getTypeControl($elem->control)){
			$elem->script .= "\n".$scf.".crear(\"$elem->nombre\",\"$tipo\");";
		}else{
			$elem->script .= "\n".$scf.".crear(\"$elem->nombre\");";
		}// end if
		$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].valor = \"".addslashes(eval_salto($elem->valor))."\";";
		$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].valor_ini = \"".addslashes(eval_salto($elem->valor_ini))."\";";
		$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].titulo = \"$elem->titulo\";";
		if($elem->usar_texto == "si"){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].usar_texto = true;";
		}// end if
		if($elem->padre){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].padre = \"$elem->padre\";";
			//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].ajax = true;";

		}// end if		
		if($elem->hijos){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].hijos = true;";
		}// end if

		if($elem->validaciones){
		
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].valid = \"$elem->validaciones\";";
		}// end if		

//$elem->script .= $elem->script2;
		if($this->referenciar or $elem->referenciar > 0){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].referenciar = true;";
			
			return $this->crear_ref($elem);
		}else{			
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].referenciar = false;";
			return $this->crear_ele($elem);
		}// end if



		if($elem->subformulario){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].valid = \"$elem->validaciones\";";
			
		}// end if		

		if($elem->data_script){
			$elem->script .= "\ndt_dgx = [];";
			$elem->script .= $elem->data_script;
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].data = dt_dgx;";			
		}// end if

		
		
	}// end function
	//===========================================================
	function crear_ref(&$elem){

		$scf = &$this->scf;
		//===========================================================
        if($configuracion = extraer_para($elem->configuracion)){
			foreach($configuracion as $para => $v){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].$para = $v;";
			}// next
		}// end if
		//===========================================================
        if($propiedades = extraer_para($elem->propiedades)){
			foreach($propiedades as $para => $v){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].prop.$para = \"$v\";";
			}// next
		}// end if
		//===========================================================
        if($eventos = extraer_para($elem->eventos)){
			foreach($eventos as $para => $v){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.$para = \"$v\";";
			}// next
		}// end if
		//===========================================================
		if($elem->clase_control!=""){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].clase = \"$elem->clase_control\";";
		}// end if
		if($elem->deshabilitado=="si"){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].deshabili = true;";
			//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].deshabilitado(true);";
		}// end if
		if($elem->solo_lectura=="si"){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].solo_lect = true;";
			//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].solo_lectura(true);";
		}// end if
		//===========================================================
		if(trim($elem->estilo)!=""){
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].estilo = \"$elem->estilo\";";
		}// enf if
		if($elem->hijos=="si"){
			//$ele_x->onchange = "eval_css(this);".$ele_x->onchange;
			//$ele_x->inner_html = $this->crear_opciones($this->valor,$this->data);
		}// end if
		
		switch($elem->control){
		//===========================================================
		case C_CTRL_TEXT:
		case C_CTRL_PASSWORD:
		case C_CTRL_TEXT_DESCRIP:
			if($elem->control==C_CTRL_PASSWORD){
				$elem->valor = C_PASSWORD_INVALIDO;
			}//
			if($elem->longitud>0){
				$max_longitud = ($elem->max_longitud != "")?$elem->max_longitud:$this->max_longitud;
				if($elem->size != ""){
					$size = $elem->size;
					
				}else{
					$size = ($elem->longitud > $max_longitud)?$max_longitud:$elem->longitud;
				}// end if
			
			
			
				$size = ($elem->longitud > $this->max_longitud)?$this->max_longitud:$elem->longitud;
				$maxlength = $max_longitud;
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].prop.size = \"$size\";";	
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].prop.maxlength = \"$maxlength\";";	
			}// end if
			if($elem->hijos=="si"){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"eval_css(this)\";";	
			}// end if
			break;
		//===========================================================
		case C_CTRL_HIDDEN:
			break;
		//===========================================================
		case C_CTRL_TEXTAREA:
		case C_TIPO_X:
		
			$row = ($elem->rows)?$elem->rows:$this->rows;
			$cols = ($elem->cols)?$elem->cols:$this->cols;
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].prop.rows = \"$row\";";	
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].prop.cols = \"$cols\";";	
			if($elem->hijos=="si"){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"eval_css(this)\";";	
			}// end if
			break;
		//===========================================================
		case C_CTRL_CHECKBOX:
			$valor_checked = ($elem->valor == $elem->valor_si)?true:false;
			if($elem->hijos=="si"){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onclick = \"eval_css(this)\";";	
			}// end if
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].valor_checked = \"$valor_checked\";";	
			break;
		//===========================================================
		case C_CTRL_SELECT:
		case C_CTRL_TEXT_DESCRIP:
			if($elem->hijos=="si"){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"eval_css(this)\";";	
			}// end if
			break;
		//===========================================================
		case C_CTRL_MULTIPLE:
			if($elem->hijos=="si"){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"this.form.$elem->nombre.value = select_multiple(this);;eval_css(this.form.$elem->nombre)\";";	
			}// end if
			break;			
		//===========================================================
		case C_CTRL_CESTA:
			if($elem->hijos=="si"){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"eval_css(this)\";";
			}// end if
			break;			
		case C_CTRL_SET:
		case C_CTRL_SET2:

			$onclick_x = "this.form.$elem->nombre.value=seleccionar_set(this);kd4.ejecutar(this);";
			if($elem->control == C_CTRL_SET){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].multiple = true;";
					
				
				if($elem->hijos=="si"){
					$onclick_x .= "eval_css(this.form.$elem->nombre);";
					//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onclick = \"this.form.$elem->nombre.value=seleccionar_set(this);eval_css(this.form.$elem->nombre);\";";
				}// end if
			}else{
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].multiple = false;";
				if($elem->hijos=="si"){
					$onclick_x .= "eval_css(this.form.$elem->nombre);";
					//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onclick = \"this.form.$elem->nombre.value=seleccionar_set(this);eval_css(this.form.$elem->nombre);\";";
				}// end if
			}// end if
			
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onclick = \"$onclick_x\";";
			
			break;
		case C_CTRL_COMBO_TEXT:
		
			$elem->script .= "\nalert(1);";
		
			break;
		//===========================================================
		case C_CTRL_CALENDARIO:

			$nombre_aux = $elem->nombre."_auX";
			$elem->script .= "\n".$scf.".crear(\"$nombre_aux\");";
			$elem->script .= "\n".$scf.".campo[\"$nombre_aux\"].valor = \"".formato_fecha($elem->valor)."\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_aux\"].valor_ini = \"".formato_fecha($elem->valor_ini)."\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_aux\"].titulo = \"$elem->titulo\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_aux\"].even.onchange = \"$scf.campo['$elem->nombre'].ele.value=val.$elem->nombre.mostrar_fecha(this.value);\";";
			//$elem->script .= "\n".$scf.".campo[\"$nombre_aux\"].clase = \"$elem->clase_boton\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_aux\"].referenciar = true;";
			//$elem->script .= "\n".$scf.".campo[\"$nombre_aux\"].remp_even = true;";




			$nombre_btn = $elem->nombre."_btnX";
			$elem->script .= "\n".$scf.".crear(\"$nombre_btn\");";
			$elem->script .= "\n".$scf.".campo[\"$nombre_btn\"].valor = \"Ver\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_btn\"].valor_ini = \"Ver\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_btn\"].titulo = \"Ver\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_btn\"].clase = \"$elem->clase_boton\";";
			$elem->script .= "\n".$scf.".campo[\"$nombre_btn\"].referenciar = true;";


			$size = ($elem->longitud > $elem->max_longitud)?$elem->max_longitud:$elem->longitud;
			$maxlength = $elem->longitud;
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].prop.size = \"$size\";";	
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].prop.maxlength = \"$maxlength\";";	
			$elem->script .= "\n".$scf.".campo[\"$nombre_btn\"].even.onclick = \"$scf.campo['$elem->nombre'].cal.calendario(this,$scf.campo['$elem->nombre"."_auX'].ele)\";";
			if($elem->hijos=="si"){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"eval_css(this)\";";	
			}// end if
			break;
		//===========================================================
		}// end switch
		
		if($elem->data_script){
			$elem->script .= "\ndt_dgx = new Array();";
			$elem->script .= $elem->data_script;
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].data = dt_dgx;";			
		}// end if
		return "";	
	}// end function
	//===========================================================
	function crear_ele(&$elem){
		$scf = &$this->scf;
		$ele_x = new cls_element_html();	
		$aux = "";
		$aux2 = "";
		//===========================================================
        if($propiedades = extraer_para($elem->propiedades)){
			foreach($propiedades as $para => $v){
				$ele_x->$para = $v;
			}// next
		}// end if
		//===========================================================
        if($eventos = extraer_para($elem->eventos)){
			foreach($eventos as $para => $v){
				$ele_x->$para = $v;
			}// next
		}// end if
		//===========================================================
		
        if($configuracion = extraer_para($elem->configuracion)){
			foreach($configuracion as $para => $v){
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].$para = $v;";
			}// next
		}// end if

		
		if($elem->clase!=""){
			$ele_x->class = $elem->clase;
		}else{
			$elem->clase = $this->clase;
		}// end if
		if(trim($elem->estilo)!=""){
			$ele_x->style = trim($elem->estilo);
		}// enf if
		if($elem->deshabilitado=="si"){
			$ele_x->disabled = "disabled";
		}// end if
		if($elem->solo_lectura=="si"){
			$ele_x->readonly = "readonly";
			if($elem->control==C_CTRL_SELECT){
				$elem->control = C_CTRL_TEXT_DESCRIP;
			}// end if
		}// end if
		
		
		
		//===========================================================
		if($elem->data_script){
			$elem->script .= "\ndt_dgx = new Array();";
			$elem->script .= $elem->data_script;
			$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].data = dt_dgx;";			
		}// end if
		

	
		switch($elem->control){
		
			case C_CTRL_TEXT:
			case C_CTRL_PASSWORD:

				if($elem->control==C_CTRL_PASSWORD and C_AUT_MD5 and $elem->valor != ""){
					$elem->valor = C_PASSWORD_INVALIDO;
				}//


				$ele_x->type = ($elem->control == C_CTRL_PASSWORD)?"password":"text";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$max_longitud = ($elem->max_longitud != "")?$elem->max_longitud:$this->max_longitud;
				if($elem->size != ""){
					$ele_x->size = $elem->size;

				}else{

					$ele_x->size = ($elem->longitud > $max_longitud)?$max_longitud:$elem->longitud;
				}// end if
				$ele_x->maxlength = $elem->longitud;
				$ele_x->value = $elem->valor;
				if($elem->hijos=="si"){
					$ele_x->onchange = "eval_css(this);$ele_x->onchange;";
				}// end if

				if($elem->ajax=="si"){
					$ele_x->onchange = "ax.x_set_form('4','ajax',this)";

				}

				$elem->objeto = $ele_x->control();
				break;
			case C_CTRL_TEXT_DESCRIP:
				$ele_x->type = "text";
				$ele_x->name = $elem->nombre."_DesX";
				$ele_x->id = $ele_x->name;
				if($elem->size != ""){
					$ele_x->size = $elem->size;
				}else{
					$ele_x->size = ($elem->longitud > $elem->max_longitud)?$elem->max_longitud:$elem->longitud;
				}// end if
				$ele_x->maxlength = "";//$elem->longitud;
				$ele_x->readonly = true;
				if($elem->data){
					foreach($elem->data as $k => $v){
						if(trim($v["valor"])==trim($elem->valor)){
							$ele_x->value = $v["texto"];
						}// end if
					}// end if
				}else{
					$ele_x->value = $elem->valor;
				}// end if
				$ele_y = new cls_element_html("hidden");
				$ele_y->name = $elem->nombre;
				$ele_y->id = $elem->nombre;
				$ele_y->value = $elem->valor;
				$elem->objeto = $ele_y->control().$ele_x->control();
				break;
			//===========================================================
			case C_CTRL_HIDDEN:
				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->value = $elem->valor;



				$elem->objeto = $ele_x->control();
				break;
			case C_CTRL_SG_COLOR:
				$ele_x->type = "color";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->value = $elem->valor;



				$elem->objeto = $ele_x->control();
				break;
			//===========================================================
			case C_CTRL_TEXTAREA:
				$ele_x->type = "textarea";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->rows = ($elem->rows)?$elem->rows:$this->rows;

				$ele_x->cols = ($elem->cols)?$elem->cols:$this->cols;
				$ele_x->value = $elem->valor;
				if($elem->hijos=="si"){
					$ele_x->onchange = "eval_css(this);".$ele_x->onchange;
				}// end if
				if(!$elem->expandir == "si" or $elem->contraer == "si"){
					$ele_t = new cls_element_html("span");


					$ele_t->id = $elem->nombre."_exp_aux".$this->panel;
					$ele_t->inner_html = C_AREA_EXPANDIR;

					$rows_min = ($elem->rows_min)?$elem->rows_min:$this->rows_min;
					if(!$elem->expandir=="si"){
						$ele_t->onclick = "f=det_form('$this->panel');expandir_aux = document.getElementById('$ele_t->id');expandir_aux.innerHTML = alternar_texto(expandir_aux.innerHTML,'".C_AREA_EXPANDIR."','".C_AREA_CONTRAER."');expandir_area(f.$elem->nombre,$rows_min,$ele_x->rows);f.$elem->nombre.style.height='';";
						$ele_x->rows = $rows_min;
					}else{
						$ele_t->onclick = "this.form.$elem->nombre.rows=$rows_min;";
					}// end if
					if($elem->clase_expandir){
						$clase_expandir = $elem->clase_expandir;
					}else{
						$clase_expandir = $elem->clase."_area_exp";
					}// end if
					$ele_t->class = $clase_expandir;
					$aux = $ele_t->control();
				}// end if
				$elem->objeto = $ele_x->control().$aux;
				break;
			//===========================================================
			case C_CTRL_CHECKBOX:
				$ele_x->type = "checkbox";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$valor_si = ($elem->valor_si)?$elem->valor_si:$this->valor_si;
				$ele_x->value = $valor_si;
				$ele_x->checked = ($elem->valor == $valor_si)?true:false;
				if($elem->hijos == "si"){
					$ele_x->onclick = "eval_css(this);".$ele_x->onclick;
				}// end if
				$elem->objeto = $ele_x->control();
				break;
			//===========================================================
			case C_CTRL_TEXT_DESCRIP:
				$ele_x->type = "text";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->value = $elem->valor;

				$ele_y = new cls_element_html();
				$ele_y->type = "text";

				foreach($elem->data as $k => $v){
					if(trim($v["valor"])==trim($elem->valor)){
						$ele_y->value = $v["texto"];
						break;
					}// end if			

				}// next
				$ele_y->name = $elem->nombre."_lblX";
				$ele_y->id = $ele_y->name;
				$aux = $ele_y->control();

				$elem->objeto = $ele_x->control().$aux;
				break;

			case C_CTRL_SELECT:

				$ele_x->type = "select";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				if($elem->padre == ""){
					$ele_x->inner_html = $this->crear_opciones($elem->valor,$elem->data);
				}// end if
				if($elem->hijos == "si"){
					$ele_x->onchange = "eval_css(this);".$ele_x->onchange;
				}// end if

				if($ele_x->readonly){
					$ele_x->disabled = true;
					$ele_x->name =  "";
					$ele_x->id = "";	
					$ele_y = new cls_element_html();
					$ele_y->type = "hidden";
					$ele_y->value = $elem->valor;
					$ele_y->name = $elem->nombre;
					$ele_y->id = $elem->nombre;

					if($elem->hijos == "si"){
						$ele_y->onchange = "eval_css(this);".$ele_x->onchange;
					}// end if
					$aux = $ele_y->control();
				}// end if
				$elem->objeto = $ele_x->control().$aux;
				break;
			//===========================================================
			case C_CTRL_COMBO_TEXT:
				$ele_x = new cls_element_html("div");
				$ele_x->id = $elem->nombre."_cmb_div";
				//$ele_x->inner_html="hola";
				$cmb_text = $elem->nombre."_cmb";
				$elem->script .= "\n\tvar $cmb_text = new cmb_text('$ele_x->id','$elem->nombre');";

				$elem->script.="\n\t$cmb_text.data=dt_dgx; $cmb_text.init();";
				$elem->objeto = $ele_x->control();
				if($elem->hijos=="si"){
					//$elem->script.="\n\t$cmb_text.ele_text.onchange = function(){eval_css(this.form.$elem->nombre.name);$ele_x->onchange};";
					//$ele_x->onchange = "eval_css(this);$ele_x->onchange;";
				}// end if

				break;
				/*

				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->value = $elem->valor;

				$ele_y = new cls_element_html();
				$ele_y->type = "text";


				$ele_y->size=$ele_x->size;
				$ele_y->class=$ele_x->class;
				$ele_y->style=$ele_x->style;


				$ele_y->name = $elem->nombre."_txt_aux";
				$ele_y->id = $ele_y->name;
				if($elem->solo_lectura=="si"){			
					$ele_y->readonly = true;
				}// end if
				if($elem->deshabilitado=="si"){			
					$ele_y->disabled = true;
				}// end if


				foreach($elem->data as $k => $v){
					if(trim($v["valor"])==trim($elem->valor)){
						$ele_y->value = $v["texto"];
						break;
					}// end if			

				}// next



				if($elem->padre == ""){
					//$ele_x->inner_html = $this->crear_opciones($elem->valor,$elem->data);
				}// end if
				if($elem->hijos == "si"){
					$ele_y->onchange = "eval_css(this.form.".$elem->nombre.");".$ele_x->onchange;
				}// end if
				$elem->objeto = $ele_x->control().$ele_y->control();*/
				break;
			//===========================================================
			case C_CTRL_MULTIPLE:
				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->value = $elem->valor;


				$ele_y = new cls_element_html();
				$ele_y->type = "select";
				$ele_y->value = $elem->valor;
				$ele_y->name = $elem->nombre."_mulX";
				$ele_y->id = $elem->nombre."_mulX";

				$ele_y->multiple = "multiple";
				$ele_y->size = ($elem->size)?$elem->size:6;
				if($elem->padre==""){
					$ele_y->inner_html = $this->crear_opciones($elem->valor,$elem->data);

				}// end if
				if($elem->hijos=="si"){
					$ele_x->onchange = "eval_css(this);".$ele_x->onchange.$elem->onchange;
				}// end if
				$ele_y->onchange ="this.form.".$elem->nombre.".value = select_multiple(this);";

				if($ele_x->onchange!=""){
					$ele_y->onchange .= "this.form.$elem->nombre.onchange();";

				}// end if

				if($ele_x->readonly){
					$ele_y->disabled = true;
				}// end if
				$elem->objeto = $ele_x->control().$ele_y->control();
				break;
			//===========================================================
			case C_CTRL_LABEL:
				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->value = $elem->valor;
				$elem->objeto = $ele_x->control();

				$ele_y = new cls_element_html();

				$ele_y->type = "span";
				$ele_y->class = $ele_x->class;

				$ele_y->style = $ele_x->style;
				$ele_y->id = $elem->nombre;
				$ele_y->inner_html = $elem->valor;					
				$elem->objeto = $ele_x->control().$ele_y->control();
				break;	
			//===========================================================
			case C_CTRL_FILE:
				/*
				$ele_y = new cls_element_html("hidden");
				$ele_y->name = "MAX_FILE_SIZE";
				$ele_y->id = $ele_y->name;
				$ele_y->value = "2000000";
				//$aux2 .= "\n".$ele_y->control();			
				*/
				$ele_y = new cls_element_html("hidden");
				$ele_y->name = $elem->nombre;
				$ele_y->id = $ele_y->name;
				$ele_y->value = $elem->valor;
				$aux = "\n".$ele_y->control();			
				$img_x = "";
				if($elem->solo_lectura!="si"){

							$ele_x->type = "file";
							$ele_x->name = $elem->nombre."_FILE_auX";
							$ele_x->value = "";
							$ele_x->id = $ele_x->id;
							$input_file = $ele_x->control();
							//$ele_x->onclick = "alert(this.value)";
				}else{			
					$input_file = "";	
				}// end if	

				if($elem->valor != ""){

					$archivo_x = (($elem->path_archivos!="")?$elem->path_archivos:C_PATH_ARCHIVOS).$elem->valor;
					$ele_y = new cls_element_html("button");
					$ele_y->name = "";
					$ele_y->id = $ele_y->name;
					$ele_y->class = (isset($this->file_button_class))?$this->file_button_class:$this->clase;
					$ele_y->onclick = "window.open($elem->nombre.value,'_blank')";
					$ele_y->value = (isset($this->file_button_value))?$this->file_button_value:"Ver";
					//$aux .= "\n".$ele_y->control();			
					$tiempo = date("Ymmddhhss");
					
					if($elem->tipo_archivo=='' or $elem->tipo_archivo=='2'){
						$img =new cls_element_html("a");
						$img->href = $archivo_x."?aux=$tiempo";
						$img->target = "_blank";
						$img->inner_html = "Mostrar";
						//$img->width = (isset($elem->img_ancho))?$elem->img_ancho:"";
						//$img->height = (isset($elem->img_alto))?$elem->img_alto:"";
						//$img->onclick = "window.open('".$archivo_x."','_blank')";
						$img_x  = $img->control()."<hr>";
					
					}else{
						$img =new cls_element_html("img");
						$img->src = $archivo_x."?aux=$tiempo";
						$img->width = (isset($elem->img_ancho))?$elem->img_ancho:"";
						$img->height = (isset($elem->img_alto))?$elem->img_alto:"";
						$img->onclick = "window.open('".$archivo_x."','_blank')";
						$img_x  = $img->control()."<hr>";
						
						
					}	
					



				}// end if
				$elem->objeto = $img_x.$input_file.$aux;
				break;
			//===========================================================
			case C_CTRL_CESTA:
				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->value = $elem->valor;

				$ele_o = new cls_element_html("select");
				$ele_o->name = $elem->nombre."_org";
				$ele_o->id = $elem->nombre."_org";
				$ele_o->value = $this->valor;

				$ele_o->multiple = "multiple";
				$ele_o->size = ($elem->size)?$elem->size:C_CESTA_FILA;	
				if($elem->padre==""){
					$ele_o->inner_html = $this->crear_opciones($elem->valor,$elem->data);

				}// end if					

				$ele_d = new cls_element_html("select");
				$ele_d->name = $elem->nombre."_des";
				$ele_d->id = $elem->nombre."_des";
				$ele_d->value = $this->valor;

				$ele_d->multiple = "multiple";
				$ele_d->size = ($elem->size)?$elem->size:C_CESTA_FILA;			


				$ele_y = new cls_element_html("button");
				$ele_y->name = $elem->nombre."_agr";
				$ele_y->id = $elem->nombre."_agr";
				$ele_y->value = "+";
				$aux1 = $ele_y->control();

				$ele_y = new cls_element_html("button");
				$ele_y->name = $elem->nombre."_qui";
				$ele_y->id = $elem->nombre."_qui";
				$ele_y->value = "-";
				$aux2 = $ele_y->control();
				$ele_y = new cls_element_html("button");
				$ele_y->name = $elem->nombre."_sub";
				$ele_y->id = $elem->nombre."_sub";
				$ele_y->value = "Subir";
				$aux2 .= $ele_y->control();
				$ele_y = new cls_element_html("button");
				$ele_y->name = $elem->nombre."_baj";
				$ele_y->id = $elem->nombre."_baj";
				$ele_y->value = "Bajar";
				$aux2 .= $ele_y->control();

				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].referenciar = true;";

				if($elem->hijos=="si"){
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"alert(99);eval_css(this)\";frm['4'].campo['$elem->nombre'].sf.ejecutar(this)\"";
				}else{
					if($elem->subformulario){
						$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onchange = \"frm['$this->panel'].campo['$elem->nombre'].sf.ejecutar(this)\"";
					}// end if
				}// end if
				//select_multiple(this);kd4.ejecutar(this)
				$elem->objeto = $ele_x->control()."<br>".$ele_o->control().$aux1."<hr>".$ele_d->control().$aux2;		
				break;
			//===========================================================
			case C_CTRL_SET:
			case C_CTRL_SET2:
				///// =========== campo oculto para el SET ====================
				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->id;
				$ele_x->value = $elem->valor;

				$n_ele = count($elem->data);
				$cols =(isset($elem->tabla_cols))?$elem->tabla_cols:$this->tabla_cols;
				$fils = ceil($n_ele/$cols);

				$t = new cls_table($fils,$cols);
				$t->width = (isset($elem->tabla_width))?$elem->tabla_width:$this->tabla_width;
				$t->style = (isset($elem->tabla_estilo))?$elem->tabla_estilo:$this->tabla_estilo;
				$t->class = (isset($elem->tabla_clase))?$elem->tabla_clase:(($this->tabla_clase!="")?$this->tabla_clase:$elem->clase);
				$t->border = (isset($elem->tabla_border))?$elem->tabla_border:$this->tabla_border;
				$t->cellspacing = $this->tabla_cellspacing;
				$t->cellpadding = $this->tabla_cellpadding;
				$t->mode_text=C_MODO_VECTOR;

				for($i=0;$i<$cols;$i++){
					$t->col[$i]->class = $t->clase;
				}// next

				$ele_y = new cls_element_html();
				if($elem->control == C_CTRL_SET){
					$ele_y->type = "checkbox";			
				}else{
					$ele_y->type = "radio";
				}// end if			

				if($elem->solo_lectura=="si"){
					$ele_y->disabled=true;

				}// end if

				$onclick_x = "this.form.$elem->nombre.value=seleccionar_set(this);";

				if($elem->hijos=="si"){
					$onclick_x .= "eval_css(this.form.$elem->nombre);";
				}// end if

				if($elem->subformulario){
					$onclick_x .= "$scf.campo['$elem->nombre'].sf.ejecutar(this);";
				}// end if
				$ele_y->onclick = $onclick_x.$elem->onclick;
				$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onclick = \"$onclick_x;$elem->onclick;\"";
				//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].referenciar = false;";
				$porc_x = floor(100/$cols)."%";

				$sel = explode(C_SEP_L,strtoupper($elem->valor));
				//$onclick = "seleccionar_set(this,'$elem->nombre');frm[4].campo['set1'].eval_data_reg(this)";
				//$ele_y->onclick = $this->onclick;
				//$onclick = $elem->onclick;

				if(count($elem->data)){

					$i=0;
					foreach ($elem->data as $k => $v){
						//$t->col[$i]->width = $porc_x;
						$ele_y->name = $elem->nombre."_".($i+1)."_auX";
						$ele_y->name = $elem->nombre."_chkX";
						$ele_y->value = $v["valor"];
						if($ele_y->value == $elem->parameters["todos"]){
							$ele_y->onclick = "seleccionar_todo(this,'$elem->nombre');".$onclick_x;
						}else{
							$ele_y->onclick = $onclick_x;
						}// end if

						$ele_y->checked = (in_array(trim(strtoupper($ele_y->value)),$sel)?true:false);
						$t->text[$i] = $ele_y->control().C_TEXT_DEFAULT.$v["texto"];	

						$i++;
					}//next			
				}// end if

				$div = new cls_element_html("div");
				$div->id = $elem->nombre."_DivX";
				$div->inner_html = $t->control();
				$aux .= $div->control();

				$elem->objeto =  $ele_x->control().$aux;

				break;
			//===========================================================
			case C_CTRL_DATE_TEXT:
			case C_CTRL_CALENDARIO:
				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->size = ($elem->size>C_MAXSIZE)?C_MAXSIZE:$elem->size;
				//$ele_x->maxlength = $this->maxlength;
				$ele_x->value = $elem->valor;
				$ele_y = new cls_element_html();
				$ele_y->type = "text";
				$ele_y->name = $elem->nombre."_auX";
				$ele_y->id = $ele_y->name;
				$ele_y->readonly = $ele_x->readonly;
				$ele_y->disabled = $ele_x->disabled;


				$max_longitud = ($elem->max_longitud != "")?$elem->max_longitud:$this->max_longitud;
				if($elem->size != ""){
					$ele_y->size = $elem->size;

				}else{

					$ele_y->size = ($elem->longitud > $max_longitud)?$max_longitud:$elem->longitud;
				}// end if
				$ele_y->maxlength = $elem->longitud;




				$ele_y->class = $ele_x->class;
				//$ele_y->size = ($elem->size>C_MAXSIZE)?C_MAXSIZE:$elem->size;
				//$ele_y->maxlength = $elem->maxlength;
				$ele_y->onchange = "$elem->nombre.value=val.fecha.mostrar_fecha(this.value);".$ele_x->onchange;
				$ele_y->value = formato_fecha($elem->valor);


				if($elem->solo_lectura!="si" and ($elem->control == C_CTRL_CALENDARIO or $elem->control == C_TIPO_D)){

					$ele_z = new cls_element_html();
					$ele_z->type = "button";

					if($elem->disabled or $elem->readonly or $elem->solo_lectura=="si"){
						$ele_z->disabled = true or true;
					}// end if

					$ele_z->class = $elem->clase."_cal_icono btn-calendar";
					$ele_z->name = $elem->nombre."_cal_auX";
					$ele_z->id = $ele_z->name;
					$ele_z->value = "";
					$ele_z->onclick = "$this->mele_script.campo['$elem->nombre'].cal.show({ref:this, output: $elem->nombre"."_auX});";//.$elem->nombre."_auX.onchange();";
					//$ele_z->onclick = "$this->mele_script.campo['$elem->nombre'].cal.calendario(this,$elem->nombre"."_auX);";//.$elem->nombre."_auX.onchange();";

					if($this->propertys!=""){
						foreach ($this->propertys as $prop => $val){
							$this->script .="\n$elem->nombre"."_Cal_auX".".$prop = $val";
						}// next
					}// end if	
					$this->propertys = null;			
					$aux = "&nbsp;".$ele_z->control();
					$aux =  $ele_x->control().$ele_y->control().$aux;
				}else{
					$aux = $ele_x->control().$ele_y->control();
				}// end if




				$elem->objeto = $aux;
				break;


			case C_CTRL_SG_CALENDAR:

				$ele_x->type = "hidden";
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				$ele_x->size = ($elem->size>C_MAXSIZE)?C_MAXSIZE:$elem->size;
				//$ele_x->maxlength = $this->maxlength;
				$ele_x->value = $elem->valor;
				$ele_y = new cls_element_html();
				$ele_y->type = "text";
				$ele_y->name = $elem->nombre."_auX";
				$ele_y->id = $ele_y->name;
				$ele_y->readonly = $ele_x->readonly;
				$ele_y->disabled = $ele_x->disabled;


				$max_longitud = ($elem->max_longitud != "")?$elem->max_longitud:$this->max_longitud;
				if($elem->size != ""){
					$ele_y->size = $elem->size;

				}else{

					$ele_y->size = ($elem->longitud > $max_longitud)?$max_longitud:$elem->longitud;
				}// end if
				$ele_y->maxlength = $elem->longitud;




				$ele_y->class = $ele_x->class;
				//$ele_y->size = ($elem->size>C_MAXSIZE)?C_MAXSIZE:$elem->size;
				//$ele_y->maxlength = $elem->maxlength;
				$ele_y->onchange = "$elem->nombre.value=val.fecha.mostrar_fecha(this.value);".$ele_x->onchange;
				$ele_y->value = formato_fecha($elem->valor);


				if($elem->solo_lectura!="si" and ($elem->control == C_CTRL_SG_CALENDAR or $elem->control == C_TIPO_D)){

					$ele_z = new cls_element_html();
					$ele_z->type = "button";

					if($elem->disabled or $elem->readonly or $elem->solo_lectura=="si"){
						$ele_z->disabled = true;
					}// end if
					$ele_z->class = $elem->clase."_cal_icono btn-calendar";
					$ele_z->name = $elem->nombre."_cal_auX";
					$ele_z->id = $ele_z->name;
					$ele_z->value = "";

					$ele_z->onclick = "$this->mele_script.campo['$elem->nombre'].cal.show({ref:this, output: $elem->nombre"."_auX});";//.$elem->nombre."_auX.onchange();";

					if($this->propertys!=""){
						foreach ($this->propertys as $prop => $val){
							$this->script .="\n$elem->nombre"."_Cal_auX".".$prop = $val";
						}// next
					}// end if	
					$this->propertys = null;			
					$aux = "&nbsp;".$ele_z->control();
					$aux =  $ele_x->control().$ele_y->control().$aux;
				}else{
					$aux = $ele_x->control().$ele_y->control();
				}// end if




				$elem->objeto = $aux;

				break;

			case C_CTRL_SG_SELECT:

					break;
			case C_CTRL_GRID:

				//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].nombre = \"$elem->vista\";";
				//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].div_nombre = \"$elem->vista"."_divX_".$this->panel."\";";

				//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].pk = \"\";";


				$ele_x->type = "textarea";
				$ele_x->rows=6;
				$ele_x->cols=50;
				$ele_x->name = $elem->nombre;
				$ele_x->id = $elem->nombre;
				//$ele_x->size = ($elem->size>C_MAXSIZE)?C_MAXSIZE:$elem->size;
				//$ele_x->maxlength = $this->maxlength;
				$ele_x->value = $elem->valor;




				require_once ("cls_consulta.php");
				$vist = new cls_consulta();

				$vist->nombre = $elem->nombre;

				$vist->panel = $this->panel;
				$vist->referenciar = true;

				$vist->tipo_seleccion = 1;
				$vist->paginador = false;
				$vist->pie_consulta = false;

				$vist->mele_script = $this->mele_script;
				$vist->ele_script = $this->ele_script;			
				$vist->nombre = $elem->nombre;

				$elem->objeto .= $ele_x->control()."<hr>".$vist->control($elem->vista);
				$elem->script .= $vist->script;

				break;	
				
			default:
				echo 33333;
				global $INPUTS;
				
				if(isset($INPUTS[$elem->control])){
					$obj = new $INPUTS[$elem->control]();
					
					foreach($elem as $k => $v){
						$obj->$k = $v;
					}
					
					$scf = $this->scf.".campo[\"$elem->nombre\"]";
					
					$elem->script = "\n".$scf.".crear(\"$elem->nombre\",\"sgInput\", {});";
					
					$elem->objeto = $obj->control();
					$elem->script .= $obj->script;
				}else{
					$elem->objeto = "$elem->control indefinido";
				}
				
				
				
				break;
				
		}// end switch
		
		if($elem->subformulario or $elem->vista){
			
			$ele_z = new cls_element_html();
			$ele_z->type = "hidden";
			//$ele_z->cols = "60";
			//$ele_z->rows = "8";


			$ele_z->name = $elem->nombre."_sfX";
			$ele_z->id = $ele_z->name;
			$elem->objeto .= $ele_z->control();


			if(!$elem->vista){

					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf = new lista_reg($this->panel);";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.padre = \"$elem->padre\";";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.detalle = \"$elem->nombre\";";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.salida = \"$ele_z->name\";";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.cpadre = \"".((isset($ele_z->cpadre))?$ele_z->cpadre:"")."\";";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.cdetalle = \"$elem->detalle\";";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.relacion = \"$elem->relacion\";";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.valor = \"$this->valor\";";

					if($elem->orden){
						$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.orden = true;";
						$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.corden = \"$elem->orden\";";
					}// end if			
					$elem->script .= "\ndt_dgx = new Array();\n".$elem->sf->cfg->data_script;
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.data_reg = dt_dgx;";
					$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].sf.init();";
					//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].deshabilitado(true);";
			}
			


			


			
//			$elem->script .= $elem->script2;
			//$elem->script .= "\n".$scf.".campo[\"$elem->nombre\"].even.onclick = \"this.form.$elem->nombre.value=seleccionar_set(this);eval_css(this.form.$elem->nombre);\"";
			
			
		}// end if		
		return; 
		if($ele_x->type!="hidden" and ($ele_x->title or $elem->comentario)){
			if($elem->comentario){
				$comentario = $elem->comentario;
			}else{
				$comentario = $ele_x->title;
			}// endif
		
		
			$ele_y = new cls_element_html();
			$ele_y->type = "button";
			$ele_y->name = $elem->nombre."_auX";
			$ele_y->value = "?";
			$ele_y->id = $elem->nombre."_p".$this->panel."_ayuX";
			$ele_y->readonly = $ele_x->readonly;
			$ele_y->disabled = $ele_x->disabled;
			$ele_y->class = $ele_x->class."_ayu_boton";
			$ele_y->onclick = "document.getElementById('".$elem->nombre."_p".$this->panel."_comX"."').style.display=(document.getElementById('".$elem->nombre."_p".$this->panel."_comX"."').style.display=='none')?'block':'none'";
			//$ele_y->title = $ele_x->title;
			//$ele_y->style = $ele_x->style;

			$div_y = new cls_element_html("div");
			//$div_y->type = "div";
			
			$salir = "<div class='".$ele_x->class."_ayu_divs"."'><input type='button' class='".$ele_x->class."_ayu_bots"."' value='x' onclick=\"document.getElementById('".$elem->nombre."_p".$this->panel."_comX"."').style.display='none'\"></div>";
			
			$div_y->id = $elem->nombre."_p".$this->panel."_comX";
			$div_y->inner_html = $salir.$comentario;
			$div_y->class = $ele_x->class."_ayu_com";
			$div_y->style ="display:none";


			$elem->objeto .= $ele_y->control().$div_y->control();

		
		
		}// end if
		
	}// end fucntion
	//==========================================================================
	function crear_script($elem,&$campo_x=false,$obj_script=""){
		return false;
		$nombre = $elem->objeto_nombre;
		$obj_script = $this->felem_script;
		
		$elem->script = "";

		switch($elem->control){
		case C_CTRL_RADIO:
			$elem->script .= "\n$obj_script.crear('$nombre','x_radio');";
			break;
		case C_TIPO_D:
				
			$elem->script .= "\n$obj_script.crear('$nombre','x_calendario');";
			break;
		case C_CTRL_SET:
			$elem->script .= "\n$obj_script.crear('$nombre','x_checkbox');";
			
			if($elem->sformulario!=""){
				$elem->script .= "\n$obj_script.campo['$nombre'].sub_form=true;";
			}// end if			
			
			break;
		case C_CTRL_MULTIPLE:
		case C_CTRL_MULTIPLE2:

			$elem->script .= "\n$obj_script.crear('$nombre','x_multiple');";
			if($elem->sformulario!=""){
				$elem->script .= "\n$obj_script.campo['$nombre'].sub_form=true;";
			}// end if			
			break;
		case C_CTRL_CESTA:
			$elem->script .= "\n$obj_script.crear('$nombre','x_cesta');";
			break;
		case C_CTRL_GRID:
			$elem->script .= "\n$obj_script.crear('$nombre');";
		//	$elem->script .= "\n$obj_script.crear('$nombre','x_grid');";
			break;
		case C_CTRL_TIME_TEXT:
			$elem->script .= "\n$obj_script.crear('$nombre','x_hora');";
			break;
		default:
			$elem->script .= "\n$obj_script.crear('$nombre');";
			break;
		
		}// end switch

		
		
		$elem->script .= "\n$obj_script.campo['$nombre'].valor = \"".addslashes(eval_salto($elem->valor))."\";";
		if($elem->valor_ini!=""){
			$elem->script .= "\n$obj_script.campo['$nombre'].valor_ini = \"$elem->valor_ini\";";
		}// end if
		$elem->script .= "\n$obj_script.campo['$nombre'].titulo = \"$elem->titulo\";";
		$elem->script .= "\n$obj_script.campo['$nombre'].pag = \"$elem->pag\";";
		if($elem->hijos=="si"){
			$elem->script .= "\n$obj_script.campo['$nombre'].hijos = true;";
			//$elem->script .= "\n$obj_script.campo['$nombre'].llenar_opciones();";
			
			
		}// end if
		if($elem->padre!=""){
			$elem->script .= "\n$obj_script.campo['$nombre'].padre = \"$elem->padre\";";
		}// end if
		if($elem->referenciar=="si" or $this->referenciar=="si"){
			$elem->script .= "\n$obj_script.campo['$nombre'].referenciar = true;";
		}// end if


		if($elem->validaciones != ""){
			$elem->script .= "\n$obj_script.campo['$nombre'].valid = \"$elem->validaciones\";";
		}// end if
		if($param = extraer_para($elem->eventos)){
			foreach($param as $k => $v){
				$elem->script .= "\n$obj_script.campo['$nombre'].$k = \"$v\";";
			}// next
		}// end if
		if($this->data_nro>0){

			$elem->script .= "\ndt_dgx = new Array();";
			$elem->script .= $elem->data_script;
			$elem->script .= "\n$obj_script.campo[\"$nombre\"].data = dt_dgx;";
		}// end if
		if($elem->data_reg_script!=""){
			$elem->script .= "\ndt_dgx = new Array();";
			$elem->script .= $elem->data_reg_script;
			$elem->script .= "\n$obj_script.campo[\"$nombre\"].data_reg = dt_dgx;";
		}// end if
		
		
	}// end function
	//===========================================================
	function control_consulta(){
		$ele_x = new cls_element_html("hidden");	
		
		$aux = "";
		$aux2 = "";
		$this->multiple = "";
		
		
        if($this->property = $this->explode_para($this->propertys)){
			foreach($this->property as $para => $v){
				eval("\$this->$para=\"$v\";");
			}// next
		}// end if
        if($this->parameter = $this->explode_para($this->parametros)){
			foreach($this->parameter as $para => $v){
				eval("\$this->$para=\"$v\";");
			}// next
		}// end if

        if($this->event = $this->explode_para($this->events)){
			foreach($this->event as $para => $v){
				eval("\$this->$para=\"$v\";");
			}// next
		}// end if

		
		//===========================================================
		$ele_x->class = $this->class;
        $ele_x->disabled = $this->disabled;
        $ele_x->readonly = $this->readonly;	

        $ele_x->onblur = $this->onblur;
		$ele_x->onchange = $this->onchange;	

		$ele_x->onclick = $this->onclick;	
		$ele_x->ondblclick = $this->ondblclick;	
		$ele_x->onfocus = $this->onfocus;	
		$ele_x->onkeydown = $this->onkeydown;	
		$ele_x->onkeypress = $this->onkeypress;	
		$ele_x->onkeyup = $this->onkeyup;	
		$ele_x->onmousedown = $this->onmousedown;	

		$ele_x->onmousemove = $this->onmousemove;	
		$ele_x->onmouseout = $this->onmouseout;	
		$ele_x->onmouseover = $this->onmouseover;	
		$ele_x->onmouseup = $this->onmouseup;	

		$ele_x->onselect = $this->onselect;	
		//===========================================================
		if(trim($this->estilo)!=""){
			$ele_x->property = "style=\"".trim($this->estilo)."\"";
		}// enf if

		$ele_x->class = $this->class;
		$span_x = new cls_element_html("span");

		switch($this->tipo){
		//===========================================================
		case C_CTRL_TEXT:
		case C_CTRL_PASSWORD:
		case C_CTRL_TEXTAREA:
			$ele_x->name = $elem->nombre;
			$ele_x->id = $this->id;
			$ele_x->value = ($this->tipo == C_CTRL_PASSWORD)?"*******":$this->valor;
			
			$span_x->inner_html = $this->valor;
			$aux = $span_x->control();
			break;
		//===========================================================
		case C_CTRL_HIDDEN:
			$ele_x->name = $elem->nombre;
			$ele_x->id = $this->id;
			$ele_x->value = $this->valor;
			break;
		//===========================================================
		case C_CTRL_CHECKBOX:
			$ele_x->name = $elem->nombre;
			$ele_x->id = $this->id;
			
			
			$ele_x->value = $this->valor;
			$span_x->inner_html = ($this->valor == $this->valor_si)?$this->texto_si:$this->texto_no;
			$aux = $span_x->control();
			
			break;
		//===========================================================
		case C_CTRL_SELECT:

			$ele_x->name = $elem->nombre;
			$ele_x->id = $this->id;
			$ele_x->value = $this->valor;

			$span_x->inner_html = $this->data[0][$this->valor];
			$aux = $span_x->control();
			break;
		//===========================================================
		case C_CTRL_LABEL:
			$ele_x->type = "span";
			$ele_x->name = $elem->nombre;
			$ele_x->id = $elem->nombre;
			$ele_x->inner_html = $this->valor;					
			break;	
		//===========================================================
		case C_CTRL_FILE:
			/*
			$ele_y = new cls_element_html("hidden");
			$ele_y->name = "MAX_FILE_SIZE";
			$ele_y->id = $ele_y->name;
			$ele_y->value = "2000000";
			//$aux2 .= "\n".$ele_y->control();			
			*/
			$ele_y = new cls_element_html("hidden");
			$ele_y->name = $elem->nombre;
			$ele_y->id = $ele_y->name;
			$ele_y->value = $this->valor;
			$aux = "\n".$ele_y->control();			

			$ele_x->type = "file";
			$ele_x->name = $elem->nombre."_FILE_auX";
			$ele_x->value = "";
			$ele_x->id = $ele_x->id;
			
			if($this->valor!=""){
				$ele_y = new cls_element_html("button");
				$ele_y->name = "";
				$ele_y->id = $ele_y->name;
				$ele_y->class = ($this->file_button_class!="")?$this->file_button_class:$this->clase;
				$ele_y->onclick = "abrir_vinculo($elem->nombre.value,'_blank')";
				$ele_y->value = ($this->file_button_value!="")?$this->file_button_value:"Ver";
				$aux .= "\n".$ele_y->control();			
			}// end if
			break;
		//===========================================================
		case C_CTRL_RADIO:
			$ele_x->type = "radio";
			$ele_x->name = $elem->nombre;
			$ele_x->id = $this->id;
			$ele_x->class = "";
			$n_ele = count($this->data);
			$cols = $this->tabla_cols;
			$fils = ceil($n_ele/$cols);
			$t = new cls_table($fils,$cols);
			$t->width = $this->tabla_width;
			$t->style = $this->style;
			$t->class = ($this->tabla_clase!="")?$this->tabla_clase:$this->class;
			$t->border = $this->tabla_border;
			$t->cellspacing = $this->tabla_cellspacing;
			$t->cellpadding = $this->tabla_cellpadding;
			
			$t->mode_text=C_MODO_VECTOR;
			for($i=0;$i<$cols;$i++){
				$t->col[$i]->class = $t->class;
			}// next
			$porc_x = floor(100/$cols)."%";
			$i=0;
			foreach ($this->data as $k => $v){
				$reg = explode(C_SEP_V,$v);
				$t->col[$i]->width = $porc_x;
				$ele_x->value = $reg[0];
				$ele_x->checked = (strtoupper($ele_x->value) == strtoupper($this->valor)?true:false);
				$t->text[$i] = $ele_x->control().C_TEXT_DEFAULT.$reg[1];			
				$i++;
			}//next
			return $t->control();
			break;
		//===========================================================
		case C_CTRL_SET:
		case C_CTRL_SET2:
			///// =========== campo oculto para el SET ====================
			$ele_x->type = "hidden";
			$ele_x->value = $this->valor;
			$ele_x->name = $elem->nombre;
			$ele_x->id = $this->id;
			$ele_x->class = "";


			$n_ele = count($this->data);
			$cols = $this->tabla_cols;
			$fils = ceil($n_ele/$cols);
			$t = new cls_table($fils,$cols);
			$t->width = $this->tabla_width;
			$t->style = $this->style;
			$t->class = ($this->tabla_clase!="")?$this->tabla_clase:$this->class;
			
			$t->border = $this->tabla_border;
			$t->cellspacing = $this->tabla_cellspacing;
			$t->cellpadding = $this->tabla_cellpadding;
			$t->mode_text=C_MODO_VECTOR;
			
			for($i=0;$i<$cols;$i++){
				$t->cols[$i]->class = $t->class;
			}// next
			$ele_y = new cls_element_html();
			$ele_y->type = "checkbox";

			$porc_x = floor(100/$cols)."%";
			$sel = explode(C_SEP_L,strtoupper($this->valor));
			//$onclick = "seleccionar_set(this,'$elem->nombre')";
			$ele_y->onclick = $onclick;
			
			if(count($this->data)){
				$i=0;
				foreach ($this->data as $fila => $f){
					$reg = explode(C_SEP_V,$f);
					$t->col[$i]->width = $porc_x;
					$ele_y->name = $elem->nombre."_".($i+1)."_auX";
					$ele_y->value = $reg[0];
					if($ele_y->value == $this->parameters["todos"]){
						$ele_y->onclick = "seleccionar_todo(this,'$elem->nombre')";
					}else{
						$ele_y->onclick = $onclick;
					}// end if
					$ele_y->checked = (in_array(trim(strtoupper($ele_y->value)),$sel)?true:false);
					$t->text[$i] = $ele_y->control().C_TEXT_DEFAULT.$reg[1];			
					$i++;
				}//next			
			}// end if
			$aux .= $t->control();
			break;
		//===========================================================
		case C_TIPO_D:
		case C_CTRL_DATE_TEXT:
		case C_CTRL_CALENDARIO:
			$ele_x->type = "hidden";
			$ele_x->name = $elem->nombre;
			$ele_x->id = $elem->nombre;
			$ele_x->size = ($elem->size>C_MAXSIZE)?C_MAXSIZE:$elem->size;
			//$ele_x->maxlength = $this->maxlength;
			$ele_x->value = $elem->valor;
			$ele_y = new cls_element_html();
			
			$ele_y->type = "text";
			$ele_y->name = $elem->nombre."_auX";
			$ele_y->id = $elem->id;
			$ele_y->readonly = $elem->readonly;
			$ele_y->disabled = $elem->disabled;

			$ele_y->class = $ele_x->class;
			$ele_y->size = ($elem->size>C_MAXSIZE)?C_MAXSIZE:$elem->size;
			$ele_y->maxlength = $elem->maxlength;
			$ele_y->onchange = "$elem->nombre.value=val.fecha.mostrar_fecha(this.value)";
			$ele_y->value = formato_fecha($elem->valor);
			$aux = $ele_y->control();
			if($elem->tipo == C_CTRL_CALENDARIO and (!$elem->disabled and !$elem->readonly)){
				$ele_z = new cls_element_html();
				$ele_z->type = "button";

				if($elem->disabled or $elem->readonly){
					$ele_z->disabled = true or true;
				}// end if

				$ele_z->class = "calendario_logo";
				$ele_z->name = "";
				$ele_z->id = "";
				$ele_z->value = "Ok";
				$ele_z->onclick = "frm[4].campo['$elem->nombre'].cal.calendario(this,$elem->nombre"."_auX);";//.$elem->nombre."_auX.onchange();";
				//$this->script .= "\nvar ".$elem->nombre."_Cal_auX = new calendario(capa)";
				//$this->script .="\n$elem->nombre"."_Cal_auX".".crear_capas()";
				//$this->doc_onclick .= "\n$elem->nombre"."_Cal_auX".".ocultar_calendario()";
				//$this->script .= "\n$elem->nombre"."_Cal_auX".".estilo = \"$this->class\"";
				
				if($this->propertys!=""){
					foreach ($this->propertys as $prop => $val){
						$this->script .="\n$elem->nombre"."_Cal_auX".".$prop = $val";
					}// next
				}// end if	
				$this->propertys = null;			
				$aux .= $ele_z->control();
			}// end if
			break;
			
			
		}// end switch
		return $ele_x->control().$aux;
	}// end fucntion
	//===========================================================
	function crear_opciones($value_x,$data_x=""){
		if($data_x==""){
			return "";
		}// end if
		
		
		
		$opt = new cls_element_html();
		$opt->type="option"; 
		$sel = explode(C_SEP_L,strtoupper($value_x));
		
		$aux="";
		if($this->padre!=""){
			//$data = $data_x[$this->valor_padre];
		}else{
			
		}// end if
		if($data = $data_x){
			foreach ($data as $k => $v){
				$opt->value = $v["valor"];
				$opt->inner_html = $v["texto"];
				if(in_array(trim(strtoupper($opt->value)),$sel)){
					$opt->selected = true;
				}else{
					$opt->selected = false;
				}// end if
				$aux .= "\n\t".$opt->control();
			}//next
		}// enf
		return $aux;
    }//end function
	//===========================================================
	function explode_para($para_x){
		if ($para_x=="" or $para_x == null){
			return false;
		}// end if
		$aux = preg_split("|(?<!\\\)".C_SEP_Q."|",$para_x);
		foreach($aux as $id => $q){
			$aux2 = preg_split("|(?<!\\\)".C_SEP_V."|",$q);
			if($aux2[1]!=null){
				$para[trim($aux2[0])]=preg_replace("|(?<!\\\)\\\|", "",trim($aux2[1]));
			}else{
				$para[C_VAR_QUERY] = $q;
			}// end if
		}// next
		return $para;
	}//end function
	//===========================================================

	public function getTypeControl($control){
		
		if(isset($this->type_control[$control])){
			return $this->type_control[$control];
		}

		
	}
	
	
	public function extraInput(&$elem, $panel){
		global $INPUTS;
				
		if(isset($INPUTS[$elem->control])){
			$obj = new $INPUTS[$elem->control]();
			$obj->panel = $this->panel;
			
			
			
			
			$obj->ref = $this->scf;
			foreach($elem as $k => $v){
				$obj->$k = $v;
			}

			$scf = $this->scf.".campo[\"$elem->nombre\"]";

			//$elem->script = "\n".$this->scf.".crear(\"$elem->nombre\",\"sgTextSelect\", {});";

			
			
			$elem->objeto = $obj->control();
			$elem->script = $obj->script;
		}else{
			$elem->objeto = "$elem->control indefinido";
		}
	}
	
}// end class



$INPUTS = array();

function loadClsInput($clsInput){
	global $INPUTS;
	foreach($clsInput as $k => $v){
		require_once($v["file"]);
		$INPUTS[$k] = $v["class"];
		//$this->_actions[$k] = $v["class"]::listActions();
	}// next 

}// end function

if(isset($clsInput)){
	
	loadClsInput($clsInput);
}
function getInput($type){
	
	
	if(isset($INPUTS[$type])){
		return new $INPUTS[$type]();
	}else{
		return new cls_control();
	}
	
	
}	
//===========================================================

/*
$control = new cls_control();
$control->name="uno";
$control->type=C_CTRL_SET;
$control->propertys = "size:3;rows:20;cols:50;check_value:yanny;tabla_border:2;tabla_cellpadding:2;tabla_cellspacing:2;tabla_width:250px";//
//$control->style = "position:absolute;left:100;top:200;";//
$control->value = "7,1";
$control->data[0] = "1:Yanny";
$control->data[1] = "2:Esteban";
$control->data[2] = "3:Nu�ez";
$control->data[3] = "4:Jimenez";
$control->data[4] = "5:Valencia";
$control->data[5] = "6:Caracas";
$control->data[6] = "7:Venezuela";

//$control->multiple = true;
//$control->onclick="alert(1)";
echo $control->control();


*/
?>