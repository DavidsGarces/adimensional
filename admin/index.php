<?php
include_once('../cabecera.php');
?>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link href="../css/fontawesome/css/all.css" rel="stylesheet">
	<script src="../js/bootstrap.min.js"></script>
<?
include_once('../conexion.php');
include_once('../cargas.php');
$action=$_REQUEST['action'];
$pagina=$_REQUEST['pagina'];
$search=$_REQUEST['search'];
$tema=$_REQUEST['tema'];
$id=$_REQUEST['id'];
$nombre=$_REQUEST['nombre'];
$date=$_REQUEST['date'];
$autores=$_REQUEST['autores'];
$texto_portada=$_REQUEST['texto_portada'];
$buscar=$_REQUEST['buscar'];
$catm=$_REQUEST['catm'];
$del_autor=$_REQUEST['del_autor'];
$completo=$_REQUEST['completo'];
if($action=="add_programa"){
	$sql_add_programa = "INSERT INTO entrevistas SET texto_portada=''";	
	$result_autores=mysqli_query($con,$sql_add_programa);		
	$new_id=mysqli_insert_id($con);
	header("Location: index.php?action=edit&id=$new_id");
}
if($action=="add_autor"){
	echo "es para añadir $catm al id $id<br>";
		$sql = "SELECT autores from entrevistas where id='".$id."'";		
		$result=mysqli_query($con,$sql);	
		while($datos=mysqli_fetch_assoc($result)){ 
			if($datos['autor']==""){
				$sql_autores = "UPDATE entrevistas SET autores='".$catm.",' where id='".$id."'";	
				echo "sql_autores $sql_autores<br>";
				$result_autores=mysqli_query($con,$sql_autores);				
			}
			else{
				if(substr($datos['autor'], -1)==","){
					$sql_autores = "UPDATE entrevistas SET autores=CONCAT(autores, '$catm') where id='".$id."'";	
					echo "sql_autores $sql_autores<br>";
					$result_autores=mysqli_query($con,$sql_autores);					
				}
				else{
					$sql_autores = "UPDATE entrevistas SET autores=CONCAT(autores, ',$catm') where id='".$id."'";	
					echo "sql_autores $sql_autores<br>";
					$result_autores=mysqli_query($con,$sql_autores);				
				}
			}
		}		
	header("Location: index.php?action=edit&id=$id");
}
if($action=="del_autor"){
	$ar = explode(",", $completo);
	$clave = array_search($del_autor, $ar);
	echo "la clave es $clave para $del_autor<br>";
	if (is_numeric($clave)) {
		unset($ar[$clave]);
		$arr=implode(",",$ar);
		echo "arr $arr<br>";
		$sql_autores = "UPDATE entrevistas SET autores='".$arr."' where id='".$id."'";	
		$result_autores=mysqli_query($con,$sql_autores);			
		echo "sql_autores $sql_autores<br>";		
		//print_r($ar);
	}
	else{
		echo "no hay clave<br>";
	}		
	//echo "es para borrar $del_autor al id $id<br>";
	//$sql_autores = "UPDATE entrevistas SET autores=CONCAT(autores, ',$del_autor') where id='".$id."'";	
	//echo "sql_autores $sql_autores<br>";
	//$result_autores=mysqli_query($con,$sql_autores);		
	header("Location: index.php?action=edit&id=$id");
	//exit();
}
if($action=="edit"){
	?>
	<table class="table table-striped" style="margin: 10px; width: 99%;">	
		<?
		$sql = "SELECT * from entrevistas where id='".$id."'";		
		$result=mysqli_query($con,$sql);	
		while($datos=mysqli_fetch_assoc($result)){ 	
			$array = explode(",", $datos['autores']);
			$numero_autores=count($array);
			?>
				<tr>
					<td>
						<div class="form-group" style="display: inline;">
							<label>Autores</label>
							<p>
							<?
							$autoress="";
							$porciones = explode(",", $datos['autores']);
							$numeros=count($porciones);
							for ($i = 0; $i < $numeros; $i++) {
								$sql2 = "SELECT * from autores where id='".$porciones[$i]."'";	
								$result2=mysqli_query($con,$sql2);	
								while($datos2=mysqli_fetch_assoc($result2)){ 
									?>
									<form action="index.php" method="post">	
										<input type="hidden" id="action" name="action" value="del_autor">	
										<input type="hidden" id="id" name="id" value="<?=$id;?>">
										<input type="hidden" id="del_autor" name="del_autor" value="<?=$porciones[$i];?>">
										<input type="hidden" id="completo" name="completo" value="<?=$datos['autores'];?>">										
										<?
										echo "<button type=\"submit\" class=\"btn btn-danger\" style='margin-right: 5px;'><i class=\"far fa-trash-alt\" style='margin-right: 5px;'></i>".$datos2['nombre']."</button>";
										?>
									</form>
									<?
								}
							}
							?>
							</p>
							<form action="index.php" method="post">
								<input type="hidden" id="action" name="action" value="add_autor">	
								<input type="hidden" id="id" name="id" value="<?=$id;?>">										
										<input type="hidden" name="catm" size="15" style="width:200px; text-align: left;" value="<? echo $catm; ?>" id="catms_hidden">
										<input name="catms" type="text" id="catms" style="width:350px; text-align: left;" onKeyUp="ajax_showOptions(this,'getCatsByLetters',event)" autocomplete="off" value="<?=$catm;?>">	
										<button type="submit" class="btn btn-primary">Add Autor</button>	
							</form>
						</div>			
					</td>
				</tr>			
			<form action="index.php" method="post">
				<input type="hidden" id="action" name="action" value="guardar">	
				<input type="hidden" id="id" name="id" value="<?=$id;?>">				
				<tr>
					<td>
					  <div class="form-group" style="display: inline;">
						Id <input type="text" class="form-control" id="ids" name="ids" value="<?=$datos['id'];?>">
					  </div>		
					  <div class="form-group" style="display: inline;">
						Temporada <input type="text" class="form-control" id="ids" name="temporada" value="<?=$datos['temporada'];?>">
					  </div>					  
					  <div class="form-group" style="display: inline;">
						Tema <input type="text" class="form-control" id="tema" name="tema" value="<?=$datos['tema'];?>">
					  </div>		
					  <div class="form-group" style="display: inline;">
						Nombre <input type="text" class="form-control" id="nombre" name="nombre" value="<?=$datos['nombre'];?>">
					  </div>	
					  <div class="form-group" style="display: inline;">
						Fecha <input type="date" class="form-control" id="date" name="date" value="<?=$datos['date'];?>">
					  </div>	
					  <div class="form-group">
						Texto_portada<textarea class="form-control" id="texto_portada" name="texto_portada" rows="3"><?=$datos['texto_portada'];?></textarea>
					  </div>					  
					</td>
				</tr>
				<tr>
					<td>
						<button type="submit" class="btn btn-success">Guardar Datos</button>
					</td>
				</tr>
			</form>
			<?
		}
		?>
	</table>
	<?
}
if($action=="guardar"){
	$sql = "UPDATE entrevistas SET tema='".$tema."', nombre='".$nombre."', date='".$date."', texto_portada='".$texto_portada."' where id='".$id."'";	
	echo "sql $sql<br>";
	$result2=mysqli_query($con,$sql);
	//exit();	
	header("Location: index.php?action=edit&id=$id");	
}
else{
	?>
	<table class="table table-striped" style="margin: 10px; width: 99%;">
		<tr>
			<td colspan="6">
				<div style="display: inline;">
					<a href="index.php?action=add_programa"><i class="far fa-plus-square" style="font-size: 24px;" alt="Añadir Programa" title="Añadir Programa"></i></a>
				</div>
				<div style="display: inline;">
					<?
						$sql2 = "SELECT * from comentarios where revisado='0'";							
						$result2=mysqli_query($con,$sql2);								
						$numero = mysqlI_num_rows($result2);					
					?>
					<a href="revisar_comentarios.php"><i class="far fa-comments" style="font-size: 24px; margin-left: 10px; margin-right: 5px;" alt="Revisar Comentarios" title="Revisar Comentarios"></a></i><b>(<?=$numero;?>)</b> 
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="6">
					<form action="index.php" method="post">
						<input type="hidden" id="action" name="action" value="search">
						<i class="fas fa-search" style="margin-right: 5px;"></i><input type="text" name="buscar" id="buscar">
						<button type="submit" class="btn btn-primary">Buscar</button>
					</form>
			</td>
		</tr>
		<?
		$limit=10;
		$sql2 = "SELECT * from entrevistas order by id DESC";
		if($action=="search"){
			$sql2 = "SELECT * from entrevistas where id like '".$buscar."' or tema like '%".$buscar."%' or nombre like '%".$buscar."%'";
		}
		$result2=mysqli_query($con,$sql2);		
		$numero = mysqlI_num_rows($result2); 	
		if (!$pagina) {
		   $inicio = 0;
		   $pagina = 1;
		}
		else {
		   $inicio = ($pagina - 1) * $limit;
		}
		//calculo el total de páginas
		$total_paginas = ceil($numero / $limit);	
		echo "<tr><td colspan='6'><b>($numero)</b> Numero de programas</td></tr>";	
		?>
		<tr>
			<td>Id</td>
			<td>Tema</td>
			<td>Autor</td>
			<td>Date</td>
			<td>Temporada</td>			
			<td>Autores</td>
			<td>Editar</td>
		</tr>		
		<?
		$sql = "SELECT * from entrevistas order by id DESC limit $inicio, $limit";	
		if($action=="search"){
			$sql = "SELECT * from entrevistas where id like '".$buscar."' or tema like '%".$buscar."%' or nombre like '%".$buscar."%' limit $inicio, $limit";
		}		
		$result=mysqli_query($con,$sql);	
		while($datos=mysqli_fetch_assoc($result)){ ?>
			<tr>
				<td><?=$datos['id'];?></td>
				<td><?=$datos['tema'];?></td>
				<td><?=$datos['nombre'];?></td>
				<td><?=$datos['date'];?></td>
				<td><?=$datos['temporada'];?></td>				
				<td><?=$datos['autores'];?></td>		
				<td>
					<form action="index.php" method="post">
						<input type="hidden" name="action" id="action" value="edit">
						<input type="hidden" name="id" id="id" value="<?=$datos['id'];?>">	
						<input type="hidden" name="pagina" id="pagina" value="<?=$pagina;?>">	
						<input type="hidden" name="search" id="buscar" value="<?=$buscar;?>">					
						<button type="submit" class="btn btn-success"><i class="far fa-save"></i></button>
					</form>
				</td>			
			</tr>
			<?
		}
		?>
			<tr>
				<td style="text-align: center;" colspan="6">
					<div>
					<?
					if ($total_paginas > 1) {
						?>
						<nav aria-label="Page navigation example">
							<ul class="pagination justify-content-center">
							<li class="page-item">
							<?
							if ($pagina != 1){
								?>
							  <a class="page-link" href="index.php?pagina=<?=$i;?>&buscar=<?=$buscar;?>&action=search" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span>
								<span class="sr-only">Anterior</span>
							  </a>
							</li>
							<?
							}
							  for ($i=1;$i<=$total_paginas;$i++) {
								 if ($pagina == $i){
									//si muestro el índice de la página actual, no coloco enlace
									?>
									<li class="page-item disabled">
									  <a class="page-link" href="#" tabindex="-1"><?=$i;?></a>
									</li>								
									<?								
								 }else{
									//si el índice no corresponde con la página mostrada actualmente,
									//coloco el enlace para ir a esa página
									?>
									<li class="page-item"><a class="page-link" href="index.php?pagina=<?=$i;?>&buscar=<?=$buscar;?>&action=search"><?=$i;?></a></li>								
									<?
							  }
							  }						  
							?>
							<li class="page-item">
							<?  if ($pagina != $total_paginas){ ?>							
							  <a class="page-link" href="index.php?pagina=<?=$i;?>&buscar=<?=$buscar;?>&action=search" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
								<span class="sr-only">Siguiente</span>
							  </a>
							</li>
							<? } ?>
						  </ul>
						</nav>
						<?	
						}
						?>
					</div>
				</td>
			</tr>
	</table>
	<?
}
?>