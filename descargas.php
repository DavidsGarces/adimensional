<?php
include_once('conexion.php');
$id=$_REQUEST['id'];
if(is_numeric($id) and $id>0){
	$sql = "SELECT * from entrevistas where id='".$id."'";
	$result=mysqli_query($con,$sql);		
	while($datos=mysqli_fetch_assoc($result)){
		$rutaArchivo = __DIR__ . "/programas/".$datos['id'].".mp3";	
		$nombreArchivo = basename($rutaArchivo);		
		# Algunos encabezados que son justamente los que fuerzan la descarga
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary");
		header("Content-disposition: attachment; filename=$nombreArchivo");
		# Leer el archivo y sacarlo al navegador
		readfile($rutaArchivo);
	}
}		
