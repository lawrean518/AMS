<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
   <meta http-equiv="Content-Type" content="text/html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
  <link rel="stylesheet" href="<?php echo base_url("assets/css/styles.css"); ?>" />
  <!--<link rel="stylesheet" href="< // ?php echo base_url("assets/css/styles.css"); ?>" />-->
  <!--link rel="shortcut icon" href="img/dcs_logo.ico"-->
  <title>AMS</title>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-1.11.2.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.tablesorter.min.js"); ?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("li").click(function(){
        var selText = $(this).text();
        $("#DD").html(selText + "<span class = 'caret'></span>");
        $("#DD").val(selText);
        $("#DROPDOWN").val(selText);
    });

    $("#exportDB").click(function(){
//        $("#exportDB").hide();
      //  $("#exportDBhere").append("SUCH HTML<?php
        //                        $theFile = fopen("db.csv", "w") or die("Unable to open file!");
          //                     ?>");
          $.get("exportDatabase.php",function(data){
                $("#exportDBhere").append(data);
        });
      //  $("#exportDBhere").load("exportDatabase.php");  $.ajax({
           
        $.ajax({
            type:'POST',
            url:'DCSMS/exportdb'
        /*    data:{'search':input},
            success:function(data){
                $('#resultdiv').html(data);
            }*/
        });
    });
  });
</script>


</head>
<body background = "<?php echo base_url("img/congruent_pentagon.png"); ?>">
<div class="container-fluid">
      <div class="row">
        <div class="col-lg-10">
          <br>
          <img style = "float:left" src="<?php echo base_url("img/upd_dcs_logo.png"); ?>" alt="DCS Logo">
          <h2> Department of Computer Science</h2>
          <p class="lead"> Academic Monitoring System</p>
        </div>
      </div> 
      <div class="row">
        <div class="col-lg-6">
          <form role = "form" class="col-lg-9">
            <div class="input-group" style="width:330px;text-align:center;margin:-3 auto;">

            <input class="form-control input-sm" placeholder="Search" value = "<?php echo $searchString; ?>" type="text" name = "INPUT">
              <span class="input-group-btn">
              <button type= "button" class="btn btn-sm btn-success dropdown-toggle" name = "DD" id = "DD" data-toggle="dropdown"><?php echo $searchBy; ?><span class = "caret"></span></button><input type = "hidden" id = "DROPDOWN" name = "DROPDOWN" value = "<?php echo $searchBy; ?>">
              <ul class="dropdown-menu" role="menu">
              <li><a href="#">Student Number</a></li>
              <li><a href="#">Last Name</a></li>
              <li><a href="#"></a></li>
              </ul>
              <button class="btn btn-sm btn-danger" type="submit" name= "submit" value="Search" formaction = "<?php echo site_url("DCSMS/search");?>">SEARCH</button></span>
            </div>           
            <button class="btn btn-sm btn-primary" type="submit" name= "submit" value="Show All" formaction = "<?php echo site_url("DCSMS/showAll");?>">SHOW ALL</button>
            <button class="btn btn-sm btn-primary" type="submit" id = "exportDB" value="ExportDB" formaction ="<?php echo site_url("DCSMS/exportDB");?>">EXPORT DATABASE</button>
            <button class="btn btn-sm btn-primary" type="submit" name= "submit" value="updateDB" formaction = "<?php echo site_url("DCSMS/home");?>">UPDATE DATABASE</button>
          </form>
          <div id="exportDBhere">
          </div>

          <?php 
            if($buttonPushed =='Show All'){
                $query = $this->DCSMS_Model->showAllStudents();
                if($query->num_rows() == 0 || $searchString == ""){
                  echo '
                  <br><br><br>  
                  <div id="wrapper">
                  <table class = "dq" id = "keywords" cellspacing="0" cellpadding="0">
                    <thead>
                      <tr>
                       No results found.
                      </tr>
                    </thead>
                    </table>
                    </div>';
                }
                else{
                  echo ' <br><br><br><br><br><div id="wrapper"> <table class="dq" id="keywords" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                    <th class="hover"><span>Student Number</span></th>
                    <th class="hover"><span>Name</span></th>
                    <th class="hover"><span>Most Recent GWA</span></th>
                    <th class="sorter-false"><span>AH</span></th>
                    <th class="sorter-false"><span>MST</span></th>
                    <th class="sorter-false"><span>SSP</span></th>
                    <th class="sorter-false"><span>Delinquency
                    <button type="button" class="btn btn-sm btn-success" id="w">w/</button>
                    <button type="button" class="btn btn-sm btn-success" id="wo">w/o</button></span></th>
                    <th class="sorter-false"><span>Remarks</span></th>
                    </tr>
                    </thead>
                    <tbody>';
                  foreach ($query->result_array() AS $row){
                    printRow($row);
                  }  
                  echo "</tbody></table></div>";
               }   
            }
  //different views para dun sa sorting and shizz
//<a href='http://localhost/AMS/index.php/DCSMS/showIndividualProfile/" . $row['stunum'] . "' target = '_blank'>" . $row['stunum'] . " </a>
            else if($buttonPushed == 'Search'){
                $query = $this->DCSMS_Model->showSearchQuery($searchString, $searchBy);
                if($query->num_rows() == 0 || $searchString == ""){
                  echo '  
                  <div id="wrapper">
                  <table class = "dq" id = "keywords" cellspacing="0" cellpadding="0">
                    <thead>
                      <tr>
                       No results found. 
                      </tr>
                    </thead>
                    </table>
                    </div>';
                }
                else{
                  echo '<div id="wrapper"> <table class = "dq" id="keywords" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                    <th class="hover"><span>Student Number</span></th>
                    <th class="hover"><span>Name</span></th>
                    <th class="hover"><span>Most Recent GWA</span></th>
                    <th class="sorter-false"><span>AH</span></th>
                    <th class="sorter-false"><span>MST</span></th>
                    <th class="sorter-false"><span>SSP</span></th>
                    <th class="sorter-false"><span>Delinquency
                    <button type="button" class="btn btn-sm btn-success" id="w">w/</button>
                    <button type="button" class="btn btn-sm btn-success" id="wo">w/o</button></span></th>
                    <th class="sorter-false"><span>Remarks</span></th>
                    </tr>
                    </thead>
                    <tbody>';
                  foreach ($query->result_array() AS $row){
                    printRow($row);
                  }  
                  echo "</tbody></table></div>";
               }   
            }


            function printRow($row){
                if($row['DQ'] == "with DQ"){
                  echo "<tr class = 'with'>";
                }
                else{
                  echo "<tr class = 'without'>";
                }
               echo "<td> <a href='http://localhost/AMS/index.php/DCSMS/showIndividualProfile/" . $row['stunum'] . "' target = '_blank'>" . $row['stunum'] . " </a></td>";
                echo "<td >" . $row['stuname'] . "</td>";
                echo "<td>" . round($row['gwa'], 4) . "</td>";
                echo "<td>" . $row['AH']. "</td>";
                echo "<td>" . $row['SSP'] . "</td>";
                echo "<td>" . $row['MST'] . "</td>";
                echo "<td>" . $row['DQ'] . "</td>";
                echo "<td>" . substr($row['stunote'], 0, 10) . "</td>"; //kelangan first 10 characters lang 
                echo "</tr>";
            }
          ?>
        </div>
      </div> 
</div> 

<script type="text/javascript">
  $(function(){
    $("table").tablesorter({
      headers: {
        3: { sorter: false },    
        4: { sorter: false },
        5: { sorter: false },
        6: { sorter: false },
        7: { sorter: false }
      }
    });
  });
  var rows = $('table.dq tr');
  var WITH = rows.filter('.with');
  var WITHOUT = rows.filter('.without');

  $('#w').click(function() {
    $(this).hide();
    $('#wo').show();    
    WITHOUT.hide()
    WITH.show()
  });

  $('#wo').click(function() {
    $(this).hide();
    $('#w').show();
    WITH.hide()
    WITHOUT.show()
  });
</script>      
</body>
</html>