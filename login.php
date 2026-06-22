<?php

session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $usuario= filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
    $pass=$_POST['password'];
    $pass = hash('sha512', $pass);
    $error='';   
    
    //var_dump(REQUEST_METHOD['POST']);
    
    try {
        $conexion= new PDO('mysql:host=localhost; dbname=u850070988_bdfacturacion', 'u850070988_admin', '?jQDa~0U');        
    } catch (PDOException $e) {
        echo "Error: ". $e->getMessage();
    }

    $statement= $conexion->prepare("SELECT * FROM users WHERE usuario=:usu and passwords= :passw ");
    $statement->execute(array(':usu'=>$usuario, ':passw'=>$pass));
    $result= $statement->fetch();
    

    if ( $result !== false) {
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');            
    }else{
        $error = '<li>Datos incorrectos</li>';
    }
}

?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400, 300" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="shortcut icon" href="dist/img/logo2.ico" />
     <link rel="stylesheet" href="dist/css/adminlte.min.css">
      <link rel="stylesheet" href="dist/css/loginimg.css">
    <title>Iniciar Sesión </title>
</head>
<body>
     <div class="" >
      <img src="dist/img/portada.png" alt="" class="" id="image_portada" style="height:100vh;width: 100%;position: absolute;">
      
      
         <div class="col-md-4 col-sm-12 row mx-0" style="z-index:20;position: relative;background: rgba(217,217,217);height:100vh" >
             
              <div class="col-md-12 text-center  px-3 d-flex flex-column align-items-center justify-content-center ">
                   <div class="">
                           <img src="dist/img/logo4.png" class=""  alt="" width="200px" height="200px" >
                           
                    </div>
                
                   <div class="" style="color:#0c2f4d" >
                       
                         <h1   style="font-weight: bold;font-family:'Impact' ">CPSP 2025</h1>
                         <h3   id="titulo_sistema" style="font-weight: bold;font-family:'sans-serif' ">Colegio de Psicólogos del Perú</h3>
                    </div>
             
                    
               </div>
               
                 <div class="col-md-12 px-3 text-center d-flex justify-content-center">
                    
               
                 <div class=" col-lg-10  px-3 pt-4 card" style="background:#fff;height:230px">
                     <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="formulario" name="login" >
                            
                            <div class="form-group has-feedback m-3">
                        <input type="text" class="form-control bg-light " placeholder="Usuario" name="usuario" style=";border:0px solid #000">
                     
                      </div>
                            
                            
                            <div class="form-group has-feedback m-3 mt-4">
                              
                                <input type="password" name="password" class="form-control bg-light  " placeholder="Contraseña" style=";border:0px solid #000">
                                
                                    
                                
                                
                            </div>
                            
                            <div class="row form-group has-feedback justify-content-end text-right self-items-end " >
                              
                                <div class="col-6 mr-3 mt-2 ">
                                    <p class="submit-btn  btn btn-primary btn-block  my-0" onclick="login.submit()" style="width:100%;font-family:sans-serif" >Ingresar</p>
                                </div>
                                
                                    
                                
                                
                            </div>
                            
                      
                
                            <?php if(!empty($error)):?>
                                <div>
                                    <ul>
                                        <?php echo $error;?>
                                    </ul>
                                </div>
                            <?php endif;?>
                        </form>
                </div>
                     
                        
                        
    
             
               </div>
             
             
        

     
        
         </div>
         
      
    </div>

</body>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
   $(document).ready(function(){
        if(window.innerWidth < "768"){
            $("#image_portada").addClass("d-none");
            $("#titulo_sistema").addClass("d-none");
        }
    });
   
 </script>


</html> 