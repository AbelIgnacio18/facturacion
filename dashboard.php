
<?php
session_start();
if (!isset($_SESSION['usuario'])) {    
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SISTEMA INTEGRAL DE COLEGIO DE PSICOLÓGOS DEL PERÚ</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="shortcut icon" href="dist/img/logo2.ico" />
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
   
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="funcionbar" ><i class="fas fa-bars"></i></a>
      </li>
      
      
       
      
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <!-- Messages Dropdown Menu -->
    
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
         <input type="hidden" id="users" value="<?php echo $_SESSION['usuario'];?>">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user-circle "><span style="font-family:Sans-serif">&nbsp; <?php echo $_SESSION['usuario'];?></span></i>
        </a>
        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
          <a href="cerrar.php" class="dropdown-item">
            Cerrar Sesión
          </a>
        </div>
      </li>
    
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#121040">
   
       
       
    <!-- Sidebar -->
    <div class="sidebar" >
      <!-- Sidebar user panel (optional) -->
      
       
           
        <div class="mt-3 pb-3 mb-3 d-flex justify-content-center flex-column text-center">
        <div class="image">
          <img src="dist/img/logo3.png" class="img-fluid" alt="User Image">
        </div>
        <div class="">
         
            
            
            <a href="#" class="logo" style="transition: width .3s ease-in-out;font-size: 20px;text-align: center; width: 230px;font-family: "Helvetica Neue", Helvetica, Arial, sans-seri;    padding: 0 15px;font-weight: 300;">
          
             <span class="logo-lg" id="logo-lg"><b>COLEGIO DE PSICÓLOGOS DEL PERÚ</b> <br> CPSP</span>
               <span class="logo-mini d-none" id="logo-mini"><b>CPSP</b></span>
            </a>
        
        </div>
      </div>

    

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class=" nav-item menu-open" id="caja_menu">
            <a href="#" class="nav-link  active " id="caja_active">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>
                Caja
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link" onclick="ircaja()">
                  <i class="fa fa-check-circle nav-icon "></i>
                  <p>Lista</p>
                </a>
              </li>
                   <li class="nav-item" id="menu_caja">
                <a href="#" class="nav-link" onclick="iraperturacaja()">
                  <i class="fa fa-check-circle nav-icon text-primary"></i>
                  <p class="text-white" id="titulo_caja">Apertura</p>
                </a>
              </li>
        
            </ul>
          </li>
          <li class="nav-item treeview" id="comprobante_menu">
            <a href="#" class="nav-link d-none" id="comprobante_active" >
              <i class="nav-icon fa fa-sticky-note"></i>
              <p>
                Comprobante
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item" id="menu_boleta">
                <a href="#" class="nav-link" onclick="irBoleta()">
                  <i class="fa fa-check-circle nav-icon text-primary"></i>
                  <p class="text-white">Boleta</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link" onclick="irNC()">
                  <i class="fa fa-times-circle nav-icon"></i>
                  <p>Nota</p>
                </a>
              </li>
   
            </ul>
          </li>
          <li class="nav-item treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Agremiados
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link" onclick="iragremiados()">
                  <i class="far fa-user-plus nav-icon"></i>
                  <p>Lista</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="#" class="nav-link" onclick="irnvoagremiados()">
                  <i class="far fa-user-plus nav-icon"></i>
                  <p>Nuevo Registro</p>
                </a>
              </li>
        
            </ul>
          </li>
          
          <li class="nav-item treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-briefcase"></i>
              <p>
                Productos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link" onclick="irproductos()">
                  <i class="fas fa-check-square-o nav-icon"></i>
                  <p>Lista</p>
                </a>
              </li>
                <li class="nav-item">
                <a href="#" class="nav-link" onclick="irnvoproductos()">
                  <i class="far fa-user-plus nav-icon"></i>
                  <p>Nuevo Registro</p>
                </a>
              </li>
        
        
            </ul>
          </li>
   
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
          <div class="row" id="contenido_principal">

          </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer " style="border-top:0px;margin-top:-0.5px">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline ">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2025 <a href="https://colegiopsj.disitics.com/">disitics</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script> 


document.getElementById("funcionbar").addEventListener("click", function (e) {
    
if(document.getElementById("logo-mini").classList.contains("d-none")){
    
    $("#logo-lg").addClass("d-none");
    $("#logo-mini").removeClass("d-none"); 
    
    
    }else{
        
    $("#logo-lg").removeClass("d-none");
    $("#logo-mini").addClass("d-none"); 
    
    
    }
  
        
});

    consultarAperturaCaja()
    
function consultarAperturaCaja(){
        $.ajax({
            method: "POST",
            url: "ApiFacturacion/controller/controlador.php",
            data: {
                "accion": "CONSULTAR_APERTURA_CAJA",
                "usuario": $('#users').val()
            }
        }).done(function(data){
            json = JSON.parse(data);
            listado = json.listado;
            
    
            if(listado[0].fechaapertura != null){
                $('#caja_active').removeClass('active');
                $('#comprobante_active').addClass('active');
                $('#comprobante_active').removeClass('d-none');
                $('#caja_menu').removeClass('menu-open');
                $('#caja_menu').addClass('treeview');
                $('#comprobante_menu').addClass('menu-open');
                $('#comprobante_menu').removeClass('treeview');
                $('#titulo_caja').html("Cierre");
                $('#fondoinicial').attr('disabled','disabled');
                $('#usuario').attr('disabled','disabled');
                
            }
            if(listado[0].fechacierre != null){
                $('#caja_active').addClass('active');
                $('#comprobante_active').removeClass('active');
                $('#caja_menu').addClass('menu-open');
                $('#caja_menu').removeClass('treeview');
                $('#comprobante_menu').removeClass('menu-open');
                $('#comprobante_menu').addClass('treeview');
                $('#titulo_caja').html("Apertura");
                $('#menu_caja').addClass("d-none");
                $('#menu_boleta').addClass("d-none");
                $('#caja_active').addClass('bg-danger');
            }
            if(listado[0].fechaapertura != null){
                cambiar_estilo()
            }
            
                
            
            
        })
    }

//Nuevo registro de Agremiados
   function iraperturacaja(){
       
    $.ajax({
      method:"GET",
      url:"view/aperturacaja.php"
    }).done(function(data){
         consultarAperturaCaja();
      $('#contenido_principal').html(data);
        
      
      //const usuario1 = localStorage.getItem("usuario1");
      //const usuario2 = localStorage.getItem("usuario2");
      
      
      
          //if($('#users').val() == "RUTH"){
                 //if(usuario1==1){
                        //cambiar_estilo();
                       
                            //$('#fondoinicial').attr('disabled','disabled');
                            //$('#usuario').attr('disabled','disabled');
                 //}
            
            //}
            
            
             //if($('#users').val() == "MARIA"){
                 //if(usuario2==2){
                        //cambiar_estilo();
                      
                            //$('#fondoinicial').attr('disabled','disabled');
                            //$('#usuario').attr('disabled','disabled');
                 //}
            
            //}
            
            
         
            
      
    } 
    )
  }



  function irBoleta(){
    $.ajax({
      method:"GET",
      url:"view/boleta.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
      $('#nrocol').focus()
    } 
    )
    
    
  }

  function irFactura(){
    $.ajax({
      method:"GET",
      url:"view/factura.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }
  
  function irNC(){
    $.ajax({
      method:"GET",
      url:"view/nota_credito.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }

  function irND(){
    $.ajax({
      method:"GET",
      url:"view/nota_debito.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }

  function irRD(){
    $.ajax({
      method:"GET",
      url:"view/resumen_diario.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }

  function irRB(){
    $.ajax({
      method:"GET",
      url:"view/resumen_bajas.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }
  
//funciones de Agremiados

 function iragremiados(){
    $.ajax({
      method:"GET",
      url:"view/agremiados.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }
  //Nuevo registro de Agremiados
   function irnvoagremiados(){
    $.ajax({
      method:"GET",
      url:"view/nuevoagremiado.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }
  
  
  //funciones de prouctos
  
   function irproductos(){
    $.ajax({
      method:"GET",
      url:"view/productos.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }
  
   //Nuevo registro de Agremiados
   function irnvoproductos(){
    $.ajax({
      method:"GET",
      url:"view/nuevoproducto.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }
  
  
  
  
  
   
    function ircaja(){
    $.ajax({
      method:"GET",
      url:"view/caja.php"
    }).done(function(data){
      $('#contenido_principal').html(data)
    } 
    )
  }

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
    <script>
        Swal.fire({!! json_encode(session('swal')) !!});
    </script>
    

    <script>
        Livewire.on('swal',data=>{
            Swal.fire(data[0])
        });
    </script>
</body>
</html>
