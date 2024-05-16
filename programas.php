<html>
	<head>
		<?
		include_once('conexion.php');
        $programa = mysqli_real_escape_string($conn, $_REQUEST['programa']);
		$sql2 = "SELECT * from entrevistas where id='".$programa."'";
		$result2=mysqli_query($con,$sql2);	
		$datos2=mysqli_fetch_assoc($result2);
		?>	
		<title><?=$datos2['tema'];?>-<?=$datos2['nombre'];?></title>
		<?
		include_once('cargas.php');
		if(isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
        }
		if(isset($action) and $action=="comentar"){
			$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
			$recaptcha_secret = '6LeUw7gZAAAAAKRhzfjJlDN6f0eW3lY5w3LGc1O9'; 
			$recaptcha_response = $_POST['recaptcha_response']; 
			$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
			$recaptcha = json_decode($recaptcha); 

			if($recaptcha->score >= 0.7){
				$nombre_comentario=$_REQUEST['nombre_comentario'];	
				$comentario=$_REQUEST['comentario'];			
				$respondea=$_REQUEST['respondea'];						
				$sql_add_comentarios = "INSERT INTO comentarios SET autor='".$nombre_comentario."', comentario='".$comentario."', entrevista='".$programa."', responde=''";	
				$result_comentarios=mysqli_query($con,$sql_add_comentarios);	
				$okcomentario=1;
			  // código para procesar los campos y enviar el form

			} else {
				$okcomentario=0;			
			  // código para lanzar aviso de error en el envío

			}		
		}
		?>
	   <script src='https://www.google.com/recaptcha/api.js?render=6LeUw7gZAAAAAMpNyjxeRk8yacoeoiQTisXeg-yP'> 
		</script>
		<script>
            grecaptcha.ready(function() {
            grecaptcha.execute('6LeUw7gZAAAAAMpNyjxeRk8yacoeoiQTisXeg-yP', {action: 'formulario'})
            .then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
            });});
		</script>		
	</head>
	<body>
		<?
			include_once('cabecera.php');
		?>
		<div class="container-fluid">
			<table class="table table-striped" style="margin: 10px; width: 99%;">
				<?
				if(isset($okcomentario) and $okcomentario=="1"){
					?>
					<tr>
						<td style="width: 80px;">
							<i class="far fa-thumbs-up" style="font-size: 45px; color: green;"></i>
						</td>
						<td>
							Gracias, su comentario ha sido enviado, una vez revisado formará parte de la web.			
						</td>
					</tr>
					<?
				}
				if(isset($okcomentario) and $okcomentario=="0"){
					?>
					<tr>
						<td style="width: 80px;">
							<i class="fas fa-times" style="font-size: 45px; color: red;"></i>
						</td>
						<td>
							Oops, debe rellenar el Recapcha para publicar mensajes.			
						</td>
					</tr>
					<?
				}
				$sql = "SELECT * from entrevistas where id='".$programa."'";
				$result=mysqli_query($con,$sql);		
				while($datos=mysqli_fetch_assoc($result)){ ?>
					<tr>
						<td style="width: 80px;">
							<i class="fas fa-align-justify" style="font-size: 45px; margin-right: 10px;" alt="Tema tratado" title="Tema tratado"></i>						
						</td>
						<td>
							<h3><?=$datos['tema'];?></h3>
						</td>
					</tr>
					<tr>
						<td>
							<i class="far fa-calendar-alt" style="font-size: 45px; margin-right: 10px;" alt="Fecha emisión" title="Fecha emisión"></i>						
						</td>
						<td>
							<?=$datos['date'];?>						
						</td>
					</tr>
					<tr>
						<td>
							<i class="fas fa-users" style="font-size: 45px; margin-right: 10px;" alt="Autores" title="Autores"></i>	
						</td>
						<td>
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
											<input type="hidden" name="action" id="action" value="autores">
											<input type="hidden" id="autor" name="autor" value="<?=$porciones[$i];?>">	
											<?
											echo "<button type=\"submit\" class=\"btn btn-light\" style='margin-right: 5px;'><i class=\"far fa-user\" style='margin-right: 5px;'></i>".$datos2['nombre']."</button>";
											?>
										</form>
										<?
									}
								}					
							?>
						</td>
					</tr>
					<?
					$nombre_fichero = "programas/".$datos['id'].".mp3";
					if (file_exists($nombre_fichero)) {
						?>
						<tr>
							<td>
								<i class="fas fa-headphones" style="font-size: 45px; margin-right: 10px;" alt="Escuchar" title="Escuchar"></i>
							</td>
							<td>
								<audio controls="controls" src="programas/<?=$datos['id'];?>.mp3">
									Your browser does not support the HTML5 Audio element.
								</audio>						
							</td>
						</tr>						
						<?
					} else {
					}					
					?>
					<tr>					
						<td>
							<i class="fas fa-cloud-download-alt" style="font-size: 45px; margin-right: 10px;" alt="Descargas" title="Descargas"></i>	
						</td>
						<td>
							<form action="descargas.php" method="post">
								<input type="hidden" name="id" id="id" value="<?=$datos['id'];?>">	
								<button type="submit" class="btn btn-link" style="margin-top: 15px;"><i class="far fa-save" style="font-size: 45px; margin-right: 10px; margin-top: -25px;" alt="Descargar" title="Descargar"></i></button>
							</form>
						</td>			
					</tr>
				</table>
				<table class="table table-hover">
					<thead>
						<tr>
							<td style="width: 80px;">
								<i class="far fa-comments" style="font-size: 45px; margin-right: 10px;" alt="Comentarios" title="Comentarios"></i>
							</td>
							<td>
									<?
									$sql2 = "SELECT * from comentarios where entrevista='".$datos['id']."' and revisado='1'";							
									$result2=mysqli_query($con,$sql2);								
									$numero = mysqlI_num_rows($result2);
									?>
								<div style="display: inline;">
									<?
									echo "($numero) Comentarios";							
									?>
									<a href="#" data-toggle="modal" data-target="#comments"><i class="far fa-comment" style="font-size: 28px; margin-left: 10px;" alt="Añadir comentario" title="Añadir comentario"></i></a>								
								<div>
							</td>
						</tr>
					</thead>
						<?
						while($comentarioss=mysqli_fetch_assoc($result2)){
						    ?>
							<tr>
								<td colspan="2">									
									<div>
										<div><i class="far fa-user" style="margin-right: 10px;" alt="Autor del comentario" title="Autor del comentario"></i><?=$comentarioss['autor'];?><div>
										<div><i class="far fa-clock" style="margin-right: 10px;"></i><?=$comentarioss['tr'];?></div>
										<div><i class="far fa-keyboard" style="margin-right: 10px;" alt="Comentario" title="Comentario"></i><span style="font-size: 14px;"><?=$comentarioss['comentario'];?></span></div>
									</div>
								</td>
							</tr>									
							<?
						}
						?>
						<!-- Modal -->
						<div class="modal fade bannerformmodal" role="dialog" aria-labelledby="comments" id="comments">
							<form action="programas.php" method="post" name="formulario">
								<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
								<input type="hidden" name="programa" id="programa" value="<?=$datos['id'];?>">
								<input type="hidden" name="action" id="action" value="comentar">									
								<div class="modal-dialog" role="document">
								<div class="modal-content">
								  <div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Añadir comentario a <?=$datos['tema'];?></h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">&times;</span>
									</button>
								  </div>
								  <div class="modal-body">
									  <div class="form-group">
										<label for="exampleInputEmail1">Nombre a mostrar</label>
										<input type="text" class="form-control" id="nombre_comentario" name="nombre_comentario">
										<small id="emailHelp" class="form-text text-muted">Su nombre a mostrar en el comentario.</small>
									  </div>
									  <div class="form-group">
										<label for="exampleFormControlTextarea1">Comentario</label>
										<textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
									  </div>								  
								  </div>
								  <div class="modal-footer">
									<button type="submit" class="btn btn-primary">Enviar comentario</button>
								  </div>
								</div>
							  </div>
						  </form>
						</div>					
					<?
				}
				?>
			</table>
		</div>