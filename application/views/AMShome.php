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
<body background = "<?php echo base_url("img/congruent_pentagon.png"); ?>">
<div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">
          <br>
          <img style = "float:left" src="<?php echo base_url("img/dcs_logo.png"); ?>" alt="DCS Logo"><h2> Department of Computer Science</h2>
          <p class="lead"> Academic Monitoring System</p>         
        </div>
      </div> 
      <div class="row">
        <div class="col-lg-6">
          <form role = "form" class="col-lg-6">
            <div class="input-group" style="width:330px;text-align:center;margin:0 auto;">
            <input class="form-control input-sm" title="" placeholder="Search" type="text">
              <span class="input-group-btn">
              <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">Student Number<span class = "caret"></span></button>
              <ul class="dropdown-menu" role="menu">
              <li><a href="#">Student Number</a></li>
              <li><a href="#">Last Name</a></li>
              <li><a href="#"></a></li>
              </ul>
              <button class="btn btn-sm btn-danger" type="submit" formaction = "<?php echo site_url("DCSMS/home");?>">SEARCH</button></span>
            </div>
           
            <button class="btn btn-sm btn-primary" type="submit" formaction = "<?php echo site_url("DCSMS/home");?>">SHOW ALL</button>
            <button class="btn btn-sm btn-primary" type="submit" formaction = "<?php echo site_url("DCSMS/home");?>">SHOW ALL</button>
          </form>        
        </div>
      </div> 
</div> 


<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-1.11.2.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>

</body>
</html>