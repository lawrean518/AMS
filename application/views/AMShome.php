<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />

  <!--<link rel="stylesheet" href="< // ?php echo base_url("assets/css/styles.css"); ?>" />-->
  <!--link rel="shortcut icon" href="img/dcs_logo.ico"-->
  <title>AMS</title>

<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-1.11.2.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("li").click(function(){
        var selText = $(this).text();
        $("#DD").html(selText + "<span class = 'caret'></span>");
    });

    $("#exportDB").click(function(){
        $("#exportDB").hide();
        $("#exportDBhere").append("SUCH HTML<?php
                                $theFile = fopen("db.csv", "w") or die("Unable to open file!");
                               ?>");
        
        //$("#exportDBhere").load("exportDatabase.php");
    });
  });
</script>


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
          <form role = "form" class="col-lg-9">
            <div class="input-group" style="width:330px;text-align:center;margin:-3 auto;">
            <input class="form-control input-sm" title="" placeholder="<?php echo $searchString; ?>"  type="text">
              <span class="input-group-btn">
              <button type="button" class="btn btn-sm btn-success dropdown-toggle" id = "DD" data-toggle="dropdown">Student Number<span class = "caret"></span></button>
              <ul class="dropdown-menu" role="menu">
              <li><a href="#">Student Number</a></li>
              <li><a href="#">Last Name</a></li>
              <li><a href="#"></a></li>
              </ul>
              <button class="btn btn-sm btn-danger" type="submit" name= "submit" value="Search" formaction = "<?php echo site_url("DCSMS/home");?>">SEARCH</button></span>
            </div>           
            <button class="btn btn-sm btn-primary" type="submit" name= "submit" value="Show All" formaction = "<?php echo site_url("DCSMS/home");?>">SHOW ALL</button>
            <button class="btn btn-sm btn-primary" type="button" id = "exportDB" onclick="exportdb()">EXPORT DATABASE</button>
            <button class="btn btn-sm btn-primary" type="submit" name= "submit" value="updateDB" formaction = "<?php echo site_url("DCSMS/home");?>">UPDATE DATABASE</button>
          </form>        
          <?php 
            if($buttonPushed=='Show All'){
              echo "such button pushed is show all";
            }
            elseif($buttonPushed=='Search'){
              echo "such button pushed is search";
            }
          ?>
        </div>
      </div> 
</div> 
<div id="exportDBhere">
</div>
</body>
</html>