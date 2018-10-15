<?php 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <!-- custom CSS -->
    <link rel="stylesheet" href="lib/css/user_style_main.css">

</head>

<body>
    
<div class="container-fluid">
  <div class="row">
    <!-- nav header -->
    <header id="header" class="col-12">
      <div class="row">
        <div id="img-container" class="col-xl-2 col-lg-2 col-md-3 col-sm-12 col-xs-12">
          <img id="header-img" src="https://upload.wikimedia.org/wikipedia/commons/b/be/Lineage_OS_Logo.png" >
        </div>

        <div id="nav-container" class="col-xl-10 col-lg-10 col-md-9 col-sm-12 col-xs-12">
          <nav id="nav-bar" class="navbar justify-content-end">
            <ul class="nav">
              <li style="text-align:center;"><a class="nav-link" href="#subs">Subscribe</a></li>
              <li style="text-align:center;"><a href="" data-toggle="modal" data-target="#admin" class="nav-link">Admin</a></li>
              
            </ul>
          </nav>
        </div>
      </div>                
    </header>            
  </div>

  <section id="subs">     
    <div class="row">
        <div id="form-container">               
        <form id="form">
            <div class="display-4" style="text-align:center;padding-bottom:50px;">
            Subscribe   
            </div>
            <div class="form-group">
            <input class="form-control" type="email" name="email" id="email" placeholder="example@domain.com">
            </div>
            <div class="form-group">
            <input id="submit" class="btn btn-danger btn-block" value="Submit" type="submit">
            </div>
        </form>  
        </div>                      
    </div>
  </section>     
        
    <!-- Model Admin -->
  <div class="modal fade" id="admin" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Admin Login</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="row">
            <div class="modal-body col-11" id="login-model">        
            <form>
                <div class="form-group">
                    <label for="admin-email">Email address</label>
                    <input type="email" class="form-control" id="admin-email" placeholder="email">
                </div>
                <div class="form-group">
                    <label for="admin-pass">Password</label>
                    <input type="password" class="form-control" id="admin-pass" placeholder="Password">
                </div>
            </form>   
            <small id="errorHelper" class=""></small>             
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="login">Login</button>
          </div>
        </div>
      </div>
    </div>

<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Javascript custom -->
<script src="lib/js/user_script.js"></script>

<!--Bootstrap js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" ></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>

</html>