<?php 
function pg_migrar($tabla, $archivo, $eliminar, $sep=";", $decode){
	
	$fila = 0;
	$cn = new cls_conexion;
	$query="";

	
	if ($eliminar=="1"){
		$query = "TRUNCATE $tabla";
		$cn->ejecutar($query);
		
	}else if($eliminar=="2"){
		$query = "DELETE FROM $tabla";
		$cn->ejecutar($query);
		
	}// end if
	
	$query = "SELECT * FROM $tabla LIMIT 0";
	$result=$cn->ejecutar($query);
	$cn->descrip_campos($result);
	$cfg = $cn->campo[$tabla];
	//print_r($cfg["padre"] );
	$t=array();
	if (($gestor = fopen($archivo, "r")) !== FALSE) {
		
		while (($datos = fgetcsv($gestor, 0, $sep, '"')) !== FALSE) {
			
			$fila++;
			if($fila==1){
				$t=$datos;

				$titulo = implode(",",$datos);
				$queryx = "INSERT INTO $tabla ($titulo) VALUES ";
				continue;
				
			}// end if
			$aux=array();
			foreach($datos as $k => $v){
				$t[$k]=trim($t[$k]);
				if($decode){
					$v = utf8_decode($v);	
				}// end if
//$gestor=utf8_decode ($gestor);
				if(trim($v)=="" and ($cfg[$t[$k]]->meta=="N" or $cfg[$t[$k]]->meta=="D" or $cfg[$t[$k]]->meta=="I")){
					$aux[] = "null";
				}else if(($cfg[$t[$k]]->meta=="C" or $cfg[$t[$k]]->meta=="X") and trim($v)=="" and $cfg[$t[$k]]->null){
					$aux[] = "null";
				}else{
					$aux[] = "'".mysql_escape_string($v)."'";
				}// end if
				$query = $queryx. "(".implode(",",$aux).")";
			}// next

			$cn->ejecutar($query);
			if ($cn->errno>0){
				hr($query);
				hr($cn->errmsg_o,"red");
				fclose($gestor);
				exit;	
				
			}// end if
		}// end while
		fclose($gestor);
	}
	$fila--;
	hr("registros= $fila, insertados correctamente");
	exit;
}// end if
?>