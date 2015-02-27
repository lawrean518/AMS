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
        $("#DD").val(selText);
        $("#DROPDOWN").val(selText);
    });
});

</script>
</head>
<body background = "img/congruent_pentagon.png">

<div class="container-fluid">

      <div class="row">
        <div class="col-lg-12 text-center v-center">
          <br><br><br><br>
          <img src="img/dcs_logo.png" alt="DCS Logo">
          <h1>Department of Computer Science</h1>
          <p class="lead">Academic Monitoring System</p>         
          <br>
          <form class="col-lg-12" method = "get">
            <div class="input-group" style="width:400px;text-align:center;margin:0 auto;">
              <input class="form-control input-lg" placeholder="Search" type="text" name = "INPUT">
                <span class="input-group-btn">
                  <button type="button" class="btn btn-lg btn-success dropdown-toggle" name = "DD" id = "DD" data-toggle="dropdown" value = "Student Number">Student Number<span class = "caret"></span></button><input type = "hidden" name = "DROPDOWN" id = "DROPDOWN" value = "Student Number">
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Student Number</a></li>
                    <li><a href="#">Last Name</a></li>
                    <li><a href="#"></a></li>
                  </ul>
                </span>
            </div>
            <br><button class="btn btn-lg btn-primary" name="submit" value="Search" type="submit" formaction = "<?php echo site_url("DCSMS/search");?>">SEARCH</button><p>
            </p><button class="btn btn-lg btn-primary"  name="submit" value="Show All" type="submit" formaction = "<?php echo site_url("DCSMS/showAll");?>">SHOW ALL</button>
          </form>
        </div>
      </div> <!-- /row -->
  
    <br><br>

</div> <!-- /container full -->
</body>
</html>