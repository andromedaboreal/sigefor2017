<?php
session_start();
//include("clases/funciones.php");
//require_once("constantes.php");
$ins = "";
if(isset($_GET["cfg_ins_aux"]) or isset($_POST["cfg_ins_aux"])){
	$ins = $_GET["cfg_ins_aux"];
	$aut = $_SESSION["VSES"][$ins]["SS_AUT"];
	$ses = &$_SESSION["VSES"][$ins];

}else{	
	$aut = false;

}
if(!$aut){
	echo "no tiene autorizacion";
	exit;
}// end if


?>


<title>Query 2004</title>
<link rel="stylesheet" type="text/css" href="css/sigefor.css">
<div>
  <div align="center"><img src="imagenes/sigefor.png" /></div>
</div>
<?php

/*
<input name="query" type="text" value="<?php echo $query; ?>" size="75" maxlength="255">


*/


$DB_conn = mysql_connect($ses["SS_BD_SERVIDOR"],$ses["SS_BD_USUARIO"],$ses["SS_BD_PASSWORD"]) or die ('Error: No se puede conectar a la Base de Datos');
//$DB_conn = mysql_connect("10.29.65.20","root","hackcity") or die ('Error: No se puede conectar a la Base de Datos');

if(!isset($_GET["query"])){
	$dbase = $ses["SS_BDATOS"];
	$iquery = "SELECT NOW()";}
else{
	$dbase = $_GET["lst_db"];
	$iquery = stripslashes($_GET["query"]);}


$iquery2 = leer_var($iquery,$_SESSION["VSES"][$ins],"@",true);	

$aux = explode(";",$iquery2);	
foreach($aux as $k => $v){
	if(!trim($v)){
		continue;
	}//
	$query = $v;
	mysql_select_db($dbase) or die ('Error: Acceso a Base de datos fallida');
	$result = @mysql_query($query);
	$tabla="";
	if ((substr_count (strtoupper($query), strtoupper("UPDATE "))==0 AND
		substr_count (strtoupper($query), strtoupper("INSERT "))==0 AND
		substr_count (strtoupper($query), strtoupper("DELETE "))==0 AND
		substr_count (strtoupper($query), strtoupper("BEGIN"))==0 AND
		substr_count (strtoupper($query), strtoupper("ROLLBACK"))==0 AND
		substr_count (strtoupper($query), strtoupper("COMMIT"))==0 AND
		substr_count (strtoupper($query), strtoupper("GRANT "))==0 AND
		
		substr_count (strtoupper($query), strtoupper("EXIT"))==0 AND
		substr_count (strtoupper($query), strtoupper("DROP "))==0 AND
		substr_count (strtoupper($query), strtoupper("TRUNCATE "))==0 AND
		substr_count (strtoupper($query), strtoupper("ALTER "))==0 AND
		substr_count (strtoupper($query), strtoupper("CREATE "))==0)
		OR substr_count (strtoupper($query), strtoupper("SHOW "))>0){
		$number_cols = @mysql_num_fields($result);
		echo "<table border = 0 cellspacing=3 class='query'>\n";
		echo "<tr align=center>\n";
		if(@mysql_field_table($result,0)!="COLUMNS"){
			$tabla=@mysql_field_table($result,0);
		}else{
			$tabla = $_GET["tabla"];
		}
		
		//$tabla=mysql_field_table($result,0);
		if (substr_count(strtoupper($query), strtoupper("SHOW "))>0){
			$hacer_link = true;
			}
		else{
			$hacer_link = false;
		}// end if
		for ($i=0; $i<$number_cols; $i++){
			echo "<th class='query'>" . @mysql_field_name($result, $i). "</th>\n";
			echo ($hacer_link)?"<th class='query'>Descripción</th>":"";
	
		}// next
		echo "</tr>\n";
		while ($row = @mysql_fetch_row($result)){
			echo "<tr align=left>\n";
			for ($i=0; $i<$number_cols; $i++){
				echo "<td class='query'>";
				if ($row[$i]==""){
					echo "&nbsp;";
					}
				else{
					echo (!$hacer_link)?($row[$i]):("<a class='query' href='?query=SELECT * FROM ".$row[$i]."&tabla=".$row[$i]."&lst_db=$dbase&cfg_ins_aux=$ins'>".$row[$i]."</a>
					<td class='query'><a class='query' href='?query=DESCRIBE ".$row[$i]."&tabla=".$row[$i]."&lst_db=$dbase&cfg_ins_aux=$ins'>Detalle</a></td>");
				}// end if
				echo "</td>\n";
			}// next
			echo "</tr>\n";
		}// end while
		echo "</table>";
		$tabla = ($tabla=="" or $tabla==null)?$_GET["tabla"]:$tabla;
	}
	else {
		$tabla = $_GET["tabla"];
		echo("ok");
	}
	if(mysql_error() ){
		echo "<b><img src=\"imagenes/error.png\" width=\"160\" height=\"160\" /><br>".mysql_error()."<b><br><br>";
	}
}// next
?>

<form action="query.php" method="get">
  <select name="lst_db">

<?php
	$lista_db = mysql_list_dbs();
    $i = 0;
    $cnt = mysql_num_rows($lista_db);
    while ($i < $cnt) {

		$db_name = mysql_db_name($lista_db, $i);
		if ($db_name ==$dbase){
			$db_nameIndex = $db_name." selected ";}
		else {
			$db_nameIndex = $db_name;}
    	echo ("<option value=$db_nameIndex>$db_name</option>");
		$i++;}
		 mysql_free_result($result);
	mysql_close($DB_conn);
?>

  </select>
  <br>

<textarea name="query" cols="100" rows="12" style="width:96%"><?php echo $iquery; ?></textarea>
<br>
<input type="submit">
<input type="submit" value = "Mostrar Tablas" onclick="query.value='show tables'">
<hr><input type="button" value = "Insert" onclick="query.value='INSERT INTO '+tabla.value+' VALUES ()'">
<input type="Submit" value = "Select" onclick="query.value='SELECT * FROM '+tabla.value">
<input type="Submit" value = "Describe" onclick="query.value='DESCRIBE '+tabla.value">
<input name="tabla" type="text" id="tabla" value="<?php echo $tabla;?>">
<input type="hidden" name="cfg_ins_aux" id="cfg_ins_aux"  value="<?php echo $ins;?>"/>
<a href="query.php?cfg_ins_aux=<?php echo $ins;?>" target="_blank">Query</a>
<hr>
</form>