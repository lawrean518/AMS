<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />

  <!--<link rel="stylesheet" href="< // ?php echo base_url("assets/css/styles.css"); ?>" />-->
  <!--link rel="shortcut icon" href="img/dcs_logo.ico"-->
  <title>AMS</title>
</head>
<body background = "img/congruent_pentagon.png">
<div class="container-full">

      <div class="row">
       
        <div class="col-lg-12 text-center v-center">
          <br><br><br><br><br>
          <img src="img/dcs_logo.png" alt="DCS Logo">
          <h1>Department of Computer Science</h1>
          <p class="lead">Academic Monitoring System</p>         
          <br>
          <form role = "form" class="col-lg-12">
            <div class="input-group" style="width:400px;text-align:center;margin:0 auto;">
            <input class="form-control input-lg" title="" placeholder="Search" type="text">
              <span class="input-group-btn">
              <button type="button" class="btn btn-lg btn-success dropdown-toggle" data-toggle="dropdown">Student Number<span class = "caret"></button>
          <ul class="dropdown-menu" role="menu">
          <li><a href="#">Student Number</a></li>
          <li><a href="#">Last Name</a></li>
          <li><a href="#"></a></li>
          </ul></span>
            </div>
            <br><button class="btn btn-lg btn-primary" type="submit" formaction = "<?php echo site_url("DCSMS/home");?>">SEARCH</button><p>        </p><button class="btn btn-lg btn-primary" type="submit" formaction = "<?php echo site_url("DCSMS/home");?>">SHOW ALL</button>
          </form>
        </div>
        
      </div> <!-- /row -->
  
    <br><br><br><br><br>

</div> <!-- /container full -->
</body>
</html>