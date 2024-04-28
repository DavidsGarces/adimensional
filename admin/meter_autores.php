<?php
include_once('../cabecera.php');
$con = new mysqli("localhost","root","","sql804591_1");
$sql = "SELECT id, nombre, autores from entrevistas";
$result=mysqli_query($con,$sql);
while($datos=mysqli_fetch_assoc($result)){
	
	$mystring = $datos['nombre'];
	
	$sql2 = "SELECT id, nombre from autores";
	$result2=mysqli_query($con,$sql2);
	while($datos2=mysqli_fetch_assoc($result2)){

		$findme   = $datos2['nombre'];
		$pos = strpos($mystring, $findme);

		// Nótese el uso de ===. Puesto que == simple no funcionará como se espera
		// porque la posición de 'a' está en el 1° (primer) caracter.
		if ($pos === false) {
			//echo "La cadena '$findme' no fue encontrada en la cadena '$mystring'";
		} else {
			echo "La cadena '$findme' fue encontrada en la cadena '$mystring'";
			echo " y existe en la posición $pos<br>";
			if($datos['autores']==""){
				$sql3 = "UPDATE entrevistas SET autores='".$datos2['id']."' where id='".$datos['id']."'";
				echo "sql3 $sql3<br>".$datos['autores']."";
				$result3=mysqli_query($con,$sql3);				
			}
			else{
				$pos = strpos($datos['autores'], $datos2['id']);	
				if ($pos === false) {
					echo "Se ha encontrado y hay un autor anterior pero este no existe el autor por lo tanto hay que meterlo<br>";
					$sql4 = "UPDATE entrevistas SET autores = CONCAT(autores, ',".$datos2['id']."') where id='".$datos['id']."'";
					$result4=mysqli_query($con,$sql4);						
					echo "sql4 $sql4<br>";					
				} else {
					echo "Ya esta creado el autor y por lo tanto no se hace nada, no se duplica<br>";
				}			
				
			}
			echo "<hr>";			
		}
	}
	echo "".$datos['nombre']."<br>";

}
?>