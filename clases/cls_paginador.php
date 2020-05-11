<?php
/*****************************************************************
creado: 01/09/2007
modificado: 05/10/2007
por: Yanny Nuñez
*****************************************************************/
//===========================================================
class cls_paginador{
	var $paginador = "";
	var $clase = "";
	var $pagina = 1;
	var $nro_paginas = 0;
	var $pag_bloque = 0;
	var $panel = false;
	//===========================================================
	var $border = "0";
	var $cellspacing = "0";
	var $cellpadding = "0";
	var $width = "100%";
	var $item_width = "8px";
	//===========================================================
	var $sin_graficos = false; 
	//===========================================================
	var $img_pag_ini = C_PAG_IMG_PAG_INI;
	var $img_pag_fin = C_PAG_IMG_PAG_FIN;
	var $img_pag_ant = C_PAG_IMG_PAG_ANT;
	var $img_pag_sig = C_PAG_IMG_PAG_SIG;
	var $txt_pag_ini = C_PAG_TXT_PAG_INI;
	var $txt_pag_fin = C_PAG_TXT_PAG_FIN;
	var $txt_pag_ant = C_PAG_TXT_PAG_ANT;
	var $txt_pag_sig = C_PAG_TXT_PAG_SIG;
	//===========================================================
	var $mensaje_ir_a = C_PAG_IR_A;
	//===========================================================
	var $vexp = array();
	//===========================================================
	
	public $clase_paginador = false;
	
	public $clase_itm = false;
	public $clase_itm_act = false;
	public $clase_itm_over = false;
	
	public $clase_ir_a = false;
	public $clase_regla = false;
	public $clase_combo = false;
	public $clase_cont = false;
	
	public $modo_async = true;
	
	function control(){
		if($this->nro_paginas <= 0){
			return false;
		}// end if
		//===========================================================
		if($this->clase != ""){
			if($this->clase_paginador == ""){
				$this->clase_paginador = $this->clase."_paginador";
			}//end if
			if($this->clase_itm == ""){
				$this->clase_itm = $this->clase."_pag_itm";
			}//end if
			if($this->clase_itm_act == ""){
				$this->clase_itm_act = $this->clase."_pag_itm_act";
			}//end if
			if($this->clase_itm_over == ""){
				$this->clase_itm_over = $this->clase."_pag_itm_over";
			}//end if
			if($this->clase_ir_a == ""){
				$this->clase_ir_a = $this->clase."_pag_ir_a";
			}//end if
			if($this->clase_regla == ""){
				$this->clase_regla = $this->clase."_pag_regla";
			}//end if
			if($this->clase_combo == ""){
				$this->clase_combo = $this->clase."_pag_combo";
			}//end if
			if($this->clase_cont == ""){
				$this->clase_cont = $this->clase."_pag_cont";
			}//end if
		}// end if
		if($this->clase_regla != ""){
			$regla = "<hr class=\"$this->clase_regla\">";
		}else{
			$regla = "<hr>";
		}// end if
		//===========================================================
		if($this->pagina <= 0){
			$this->pagina = 1;
		}else if($this->pagina > $this->nro_paginas){
			$this->pagina = $this->nro_paginas;
		}// end if
		if($this->pagina == 1){
			$pag_ant = 1;
		}else{
			$pag_ant = $this->pagina - 1;
		}// end if
		if($this->pagina == $this->nro_paginas){
			$pag_sig = $this->nro_paginas;
		}else{
			$pag_sig = $this->pagina + 1;
		}// end if
		$pag_ini = 1;
		$aux = "";
		$pag_fin = $this->nro_paginas;
		//===========================================================
		$ini = $this->pag_bloque * floor((($this->pagina - 1) / $this->pag_bloque)) + 1;
		$fin = $ini + $this->pag_bloque - 1;
		if ($fin > $pag_fin){
			$fin = $pag_fin;
		}// end if			
		$nro_items = $fin - $ini + 1 + 4;
		//===========================================================
		$t = new cls_table(1,$nro_items);
		$t->class = $this->clase_paginador;
		$t->width = $this->width;
		$t->border = $this->border;
		$t->cellspacing = $this->cellspacing;
		$t->cellpadding = $this->cellpadding;
		$ancho = $this->item_width;
		//===========================================================
		for($i=0;$i<$nro_items;$i++){
			$t->cell[0][$i]->width = $ancho;
			//$t->cell[0][$i]->onmouseover = "this.className='$this->clase_itm_over'";
			//$t->cell[0][$i]->onmouseout = "this.className='$this->clase_itm'";
			$t->cell[0][$i]->class = $this->clase_itm;
		}// next		
		//===========================================================
		for($i=$ini;$i<=$fin;$i++){
			if($i == $this->pagina){
				$t->cell[0][$i+2-$ini]->text = $i;
				$t->cell[0][$i+2-$ini]->onmouseover = "";
				$t->cell[0][$i+2-$ini]->onmouseout = "";
				$t->cell[0][$i+2-$ini]->class = $this->clase_itm_act;
			}else{
				$t->cell[0][$i+2-$ini]->text = $i;
				$t->cell[0][$i+2-$ini]->onclick = $this->_onclick($i);//"enviar(false,$this->panel,'panel:$this->panel;pagina:$i;',0,'');";
			}// end if
		}// next
		//===========================================================
		if(!$this->sin_graficos){
			//
					$t->cell[0][0]->class = $this->clase_itm."_ini";
					$t->cell[0][1]->class = $this->clase_itm."_ant";
					$t->cell[0][$fin+3-$ini]->class = $this->clase_itm."_sig";
					$t->cell[0][$fin+4-$ini]->class = $this->clase_itm."_fin";
					$t->cell[0][0]->text = "&nbsp;";
					$t->cell[0][1]->text = "&nbsp;";
					$t->cell[0][$fin+3-$ini]->text = "&nbsp;";
					$t->cell[0][$fin+4-$ini]->text = "&nbsp;";
		
			/*
			$t->cell[0][0]->text = $this->obj_imagen($this->img_pag_ini);
			$t->cell[0][1]->text = $this->obj_imagen($this->img_pag_ant);
			$t->cell[0][$fin+3-$ini]->text = $this->obj_imagen($this->img_pag_sig);
			$t->cell[0][$fin+4-$ini]->text = $this->obj_imagen($this->img_pag_fin);
			*/
		}else{
			$t->cell[0][0]->text = $this->txt_pag_ini;
			$t->cell[0][1]->text = $this->txt_pag_ant;
			$t->cell[0][$fin+3-$ini]->text = $this->txt_pag_sig;
			$t->cell[0][$fin+4-$ini]->text = $this->txt_pag_fin;
		}// end if
		//===========================================================
		$t->cell[0][0]->onclick = $this->_onclick($pag_ini);//"enviar(false,$this->panel,'panel:$this->panel;pagina:$pag_ini;',0,'');";
		$t->cell[0][1]->onclick = $this->_onclick($pag_ant);//"enviar(false,$this->panel,'panel:$this->panel;pagina:$pag_ant;',0,'');";
		$t->cell[0][$fin+3-$ini]->onclick = $this->_onclick($pag_sig);//"enviar(false,$this->panel,'panel:$this->panel;pagina:$pag_sig;',0,'');";
		$t->cell[0][$fin+4-$ini]->onclick = $this->_onclick($pag_fin);//"enviar(false,$this->panel,'panel:$this->panel;pagina:$pag_fin;',0,'');";
		//===========================================================
		if ($this->pag_bloque < $this->nro_paginas){
			$ele = new cls_element_html("select");
			$ele->class = $this->clase_combo;
			$ele->onchange = $this->_onchange();
			$opt = new cls_element_html("option");
			for ($i=1;$i<=$pag_fin;$i=$i+$this->pag_bloque){
				$opt->value = $i;
				$opt->inner_html = $i;
				if ($i >= $ini and $i <= $fin){
					$opt->selected = true;
				}else{
					$opt->selected = false;
				}// end if
				$ele->inner_html .= $opt->control();
			}// next
			$span = new cls_element_html("span");
			$span->class = $this->clase_ir_a;
			$span->inner_html = $this->mensaje_ir_a;
			$aux = $regla.$span->control().$ele->control();
		}// end if
		//===========================================================
		$this->pag_ini = $pag_ini;
		$this->pag_fin = $pag_fin;
		$this->bloque_ini = $ini;
		$this->bloque_fin = $fin;
		$this->nro_elem = $nro_items;
		$this->vexp["PAG_ACT"] = $this->pagina;
		$this->vexp["PAG_INI"] = $this->pag_ini;
		$this->vexp["PAG_FIN"] = $this->pag_fin;
		$this->vexp["BLOQUE_INI"] = $this->bloque_ini;
		$this->vexp["BLOQUE_FIN"] = $this->bloque_fin;
		$this->vexp["NRO_ELEM"] = $this->nro_elem;
		//===========================================================
		$pag = new cls_element_html("div");
		$pag->width = "100%";
		$pag->class = $this->clase_cont;
		$pag->inner_html = $t->control().$aux.$regla;
		return $pag->control() ;
	}// end function
	//===========================================================
	function obj_imagen($img_x){
		$ele = new cls_element_html("img");
		$ele->src = $img_x;
		return $ele->control();
	}// end function
	//===========================================================
	
	private function _onclick($pagina){
		
		if($this->modo_async){
			
			$params = array();
			$params["panel"] = $this->panel;
			$params["pagina"] = $pagina;
			
			$opt = new stdClass;
			$opt->async = $this->modo_async;
			$opt->panel = $this->panel;
			$opt->params = $params;

			$json = json_encode($opt);
			
			return htmlentities("sgPanel.send(this,$json)");
			
			
		}else{
			return "enviar(false, $this->panel,'panel:$this->panel;pagina:$pagina;',0,'');";
		}
	}

	private function _onchange(){
		
		if($this->modo_async){
			return htmlentities("sgPanel.sendPage($this->panel, this.value)");
		}else{
			return "enviar(false,$this->panel,'panel:$this->panel;pagina:'+this.value+';',0,'');";
		}
	}
}// end class
?>