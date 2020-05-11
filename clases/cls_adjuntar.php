<?php
class cls_adjuntar{
	var $nombre = "";
	var $path = C_PATH_ARCHIVOS;
	var $extension = "";

	var $nombre_ele = "";
	var $archivo_anterior = "";
	function guardar(){


		if($_FILES[$this->nombre_ele]["name"] == ""){
			return $this->nombre;
		}//

		$this->archivo_name = $_FILES[$this->nombre_ele]["name"];
		$this->ext = "";

		if(preg_match("/(.\w+)$/", $this->archivo_name, $c)){
			$this->ext = $c[1];
		}// end if


		if($this->nombre==""){
			$rand = substr((rand (0,9999)*10000),0,4);
			$aux = date("YmdHis")."_".$rand;	
			$this->nombre = $aux.$this->ext;
		}else{
			$archivo_x = pathinfo($this->nombre);
			$this->nombre = $archivo_x["dirname"]."/".$archivo_x["filename"].$this->ext;

		}// end if
	
		if ($this->archivo_name == "" or move_uploaded_file($_FILES[$this->nombre_ele]["tmp_name"],$this->path.$this->nombre)){
	
			return $this->nombre;
		}else{
			
			$this->msg_error = C_ERROR_UPLOAD;
			return false;
		}// end if
		
	
	}// end function





}// end class

?>