<?php
// incoporar la clase mailer
require_once('conf/mailer_5_2_11/PHPMailerAutoload.php');
require_once('conf/funcion.php');

//---formulario general-------------------
$dni= $_POST['dni'];
$nombre  = ucfirst($_POST['name']);
$apellido  = ucfirst($_POST['lastname']);
$email  = strtolower($_POST['mail']);
$toemail = 'alan.n.portillo@gmail.com';//strtolower($_POST['toemail']);
$telefono  = $_POST['telephone'];
$consulta  = ucfirst($_POST['message']);
//-----------------------------------------

if($email){
				$sql= "INSERT INTO vm_contacto2(									                                        
                                   dni,
                                   nombre,
                                   apellido,
                                   email,
                                   telefono,
                                   escuela,
                                   consulta                                    
                        ) VALUES (
                                   '$dni',
                                   '$nombre',
                                   '$apellido',
                                   '$email',
                                   '$telefono',
                                   'empty',
                                   '$consulta'
                       );";

                        $a=sql_do_query($sql,'1');

                    
                    if($nombre and $email){						
						$mail = new PHPMailer;

						//---Configuracion servidor SMTP
						$mail->isSMTP();                 							// Set mailer to use SMTP
						$mail->Host = 'smtp.gmail.com';  							// Specify main and backup SMTP servers
						$mail->SMTPAuth = true;          							// Enable SMTP authentication
						$mail->Username = 'marina-mercante@prefecturanaval.edu.ar'; // SMTP username
						$mail->Password = 'Dedu@2022';                           	// SMTP password
						$mail->SMTPSecure = 'ssl';                            		// Enable TLS encryption, `ssl` also accepted
						$mail->Port = 465;                                    		// TCP port to connect to

						//---Datos de los correos a enviar
						$mail->setFrom('marina-mercante@prefecturanaval.edu.ar');
						$mail->addAddress($toemail);     							// Add a recipient
						//$mail->addCC($email);
						
						/* ---mas datos de envio---
						$mail->addAddress('ellen@example.com');               // Name is optional
						$mail->addReplyTo('info@example.com', 'Information');
						$mail->addCC('cc@example.com');
						$mail->addBCC('bcc@example.com');*/

						//   ---datos de archivos adjuntos---
						//$mail->addAttachment("pdf/$cursoid.pdf", 'Le enviamos la informaci&oacute;n que requiri&oacute;.');         // Add attachments
						//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

						//---variable para aceptar HTML
						$mail->isHTML(true);                                  // Set email format to HTML

						$mail->Subject = $nombre. ' intenta contactarte ';
						$mail->Body    = "      <table style= width: 100%; max-width: 700px;>
												<tr><td>
												<!-- <center><img src='https://marina-mercante.iusm.edu.ar/img/webcampus/logo-pna.svg' width: '250px' height: 'auto' margin-left: '0' margin-right: '0'></center> -->
												</td></tr>
												<tr><td style='padding: 10px;'>
												<center>
														<h2>Marina Mercante</h2>
														<p>Este mail se ha generado automatica mediante la pagina institucional https://marina-mercante.iusm.edu.ar </p>
														<p> Favor de responder al correo detallado mas abajo. </p>
														<br>
													
												<table style='background-color: #EEEEEE; padding: 10px; text-align: center;'>
												<tr>
												<td>Nombre:</td><td><h2> ".$nombre."&nbsp; </h2></td>
			
												</tr>
												<tr>
													<td>E-mail:</td><td><h2> ".$email."&nbsp; </h2</td>
												</tr>
												<tr>
													<td>Telefono:</td><td><h2> ".$telefono."&nbsp; </h2</td>
												</tr>
												<tr>
													<td>Consulta:</td><td><h2> ".$consulta."&nbsp; </h2</td>
												</tr>
												</table>
												
												</center>
												</td></tr>
												<!-- <tr><td style='padding: 10px; background-color: #222222;'>
												<p style='text-align: center; font-size: 10px; color: #CBCBCB;'>Powered by:</p>
												<center><a href='http://grupovalkirias.net'  alt='Web Development and Design Studio' target='_new' style='text-decoration: none;'><img src='img/powered-by-blanco.png'></a></center>
												</td></tr> -->
												</table>
                        ";
                        
						//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

						//---validacion de envio
						
						$error = $mail->send();						
						if(!$error){
							?>
							<script type="text/javascript">alert("Revise la informacion proporcionda, el correo no fue enviado.");</script>
							<script>window.location="index.php#contacto";</script> 			
							 <?php
								
						}else{
        						?>
								<script type="text/javascript">alert("La informacion fue enviada al correo electronico ingresado");</script>
								<script>window.location="index.php#contacto";</script> 			
        		 				<?php				
						}
					}
				}
?>