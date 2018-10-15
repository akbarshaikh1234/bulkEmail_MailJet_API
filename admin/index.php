<?php 
	require('../lib/bin/Database.php');
    session_start();
	$count = 0;
    if(isset($_SESSION['user'])){
		$db = new Database();
		$row = $db->getRows('SELECT distinct(email) FROM subs',[]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

     <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" >


    <!-- custom CSS -->
    <link rel="stylesheet" href="../lib/css/admin_style_main.css">
</head>
<body>
<div class="container">
    <div class="row profile">
		<div class="col-md-3">
			<div class="profile-sidebar">

				<div class="profile-usertitle">
                    <div class="display-4">
                        Welcome
                    </div>
					<div class="profile-usertitle-name">
						<?php echo $_SESSION['user'];?>
					</div>					
				</div>
                
				<!-- side menu -->
				<form action="../lib/bin/call_to_database.php?logout=true" method="post">
					<div class="profile-userbuttons">
						<button type="submit" name='logout' class="btn btn-danger btn-block btn-sm">Logout</button>
					</div>
				</form>
				

				<?php
					
				?>
				<hr>
				<div class="profile-usermenu">
					<ul class="nav flex-column" id="links">
						<li class=" nav-item ">
							<a href="#upload" class="active">
							<i class="fas fa-upload"></i>
							Upload CSV </a>
						</li>
						<li class="nav-item">
							<a href="#list-subs">
							<i class="far fa-list-alt"></i>
							Subscrbers List </a>
						</li>
						<li class="nav-item">
							<a href="#statistics">
							<i class="fas fa-chart-line"></i>
							Statistics </a>
						</li>
					</ul>
				</div>
				<!-- end menu -->
			</div>
		</div>
		
		<div class="col-md-9">			
             <div class="profile-content">
				<div class="row">
					<!-- upload section -->
					<div id="upload" class=" col-xl-8 col-lg-8 col-md-10 col-xs-12 content-area">
						<form method="POST" action="../lib/bin/call_to_database.php?fileupload=true" enctype="multipart/form-data">
							<div class="input-group ">
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="uploadCSV" name="file">
									<label class="custom-file-label" for="uploadCSV">Browse CSV file</label>
								</div>

								<div class="input-group-append">
									<button class="btn btn-primary" name="submit" type="submit" id="uploadcsv">Upload File</button>
								</div>
							</div>
						</form>						
					</div>

					<!-- list subscribers -->
					<div id="list-subs" class="content-area table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th><input type="checkbox" name="checkAll" id="checkAll"></th>
									<th scope="col">id</th>
									<th scope="col">Subscriber Emails</th>							
								</tr>
							</thead>
							<tbody>
								<div>
									<?php									
										foreach( $row as $data){
											echo "<tr>";
											echo "<td><input type='checkbox' class='check_list' name='check_list[]' value=".$data['email']."></td>";
											echo "<td>".++$count."</td>";
											echo "<td>".$data['email']."</td>";
											echo "</tr>";
										}										
									?>		
								</div>																				
							</tbody>
						</table>
						<div id="buttonContainer">
							<button type="button" class="fas fa-mail-bulk btn btn-danger btn-lg" name="sendmail" id="composeMail" data-toggle="modal" data-target="#mailModel" > <span> &nbsp; Compose </span></button>
						</div>
						
					</div>

					<!-- statistics -->
					<div id="statistics" class="content-area">
						show stats
					</div>
				</div> 			   
             </div>
		</div>
	</div>
</div>


<!-- Mail Model -->
<div class="modal fade bd-example-modal-lg" id="mailModel" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Compose Mail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="row">
            <div class="modal-body col-11" id="mailer-model" style="margin:0 auto;">        
            <form>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" id="subject" placeholder="subject">
                </div>
                <div class="form-group">
					<label for="message">Message</label>
					<textarea class="form-control" id="message" rows="8"></textarea>
				</div>
            </form>   
            <small id="errorHelper" class=""></small>             
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="sendMail">Send Mail</button>
          </div>
        </div>
      </div>
    </div>


<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Javascript custom -->
<script src="../lib/js/admin_script.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" ></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>



<?php
    }
    else{
        header('location:../index.php');
    }
?>