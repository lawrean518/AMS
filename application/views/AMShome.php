<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
   <meta http-equiv="Content-Type" content="text/html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
  <link rel="stylesheet" href="<?php echo base_url("assets/css/styles.css"); ?>" />

  <title>AMS</title>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-1.11.2.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.tablesorter.min.js"); ?>"></script>
<script type="text/javascript">
  $(document).ready(function(){ //for the dropdown (selecting text)
    $("li").click(function(){
        var selText = $(this).text();
        $("#DD").html(selText + "<span class = 'caret'></span>");
        $("#DD").val(selText);
        $("#DROPDOWN").val(selText);
    });
  });
</script>


</head> <!--design of the webpage-->
<body background = "<?php echo base_url("img/congruent_pentagon.png"); ?>">
<div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <br>
          <img style = "float:left" src="<?php echo base_url("img/dcs_logo.png"); ?>" alt="DCS Logo"><h2> Department of Computer Science</h2>
          <p class="lead"> Academic Monitoring System</p>
        </div>
      </div> 
      <div class="row">
        <div class="col-lg-12">
          <form role = "form" class="col-lg-9">
            <div class="input-group" overflow:"hidden" style="width:330px;text-align:center;margin:4 auto;">

            <input class="form-control input-sm" style = "margin-right: 17px"placeholder="Search" value = "<?php echo $searchString; ?>" type="text" name = "INPUT">
              <span class="input-group-btn">
              <button type= "button" style = "margin-right: 3px; margin-left: 3px" class="btn btn-sm btn-success dropdown-toggle" name = "DD" id = "DD" data-toggle="dropdown"><?php echo $searchBy; ?><span class = "caret"></span></button><input type = "hidden" id = "DROPDOWN" name = "DROPDOWN" value = "<?php echo $searchBy; ?>">
              <ul class="dropdown-menu" role="menu">
              <li><a href="#">Student Number</a></li>
              <li><a href="#">Last Name</a></li>
              <li><a href="#"></a></li>
              </ul>
              <button class="btn btn-sm btn-danger" type="submit" name= "submit" value="Search" formaction = "<?php echo site_url("DCSMS/search");?>" >SEARCH</button></span>
            </div> 
            <div style="padding: 1px; margin-bottom: 3px; margin-top: 3px">
            <button class="btn btn-sm btn-primary" type="submit" name= "submit" value="Show All" formaction = "<?php echo site_url("DCSMS/showAll");?>">Show All</button>
            <button class="btn btn-sm btn-primary" type="submit" id = "exportDB" value="ExportDB" formaction ="<?php echo site_url("DCSMS/exportDB");?>">Export Database</button>
            <button class="btn btn-sm btn-primary" type="submit" name= "submit" value="updateDB" formaction = "<?php echo site_url("DCSMS/home");?>">Update Database</button>
            </div>
          </form>
        </div>
      </div>
      <div class = "row"><br>
          <?php 
            if($buttonPushed =='Show All'){
                $query = $this->DCSMS_Model->showAllStudents();
                if($query->num_rows() == 0){
                  echo '
                  <br><br><br>  
                  <br><br><br><br>
                  <div id="noresults" style:"margin-left: 40px" >
                      No results found.       
                  </div>';
                }
                else{
                  echo '<div class = "row">
                  <div id="wrapper" style:"margin-left: 40px" > <table class = "dq" id="keywords" cellspacing="0" cellpadding="0">
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
                  echo "</tbody></table></div></div>";
               }   
            }

            else if($buttonPushed == 'Search'){ //what will happen if Search button is pressed
                $query = $this->DCSMS_Model->showSearchQuery($searchString, $searchBy); //gets the value of search textbox and the dropdown menu
                if($query->num_rows() == 0 || $searchString == ""){ //if empty string output "No results found."
                echo '  
                <br><br><br><br>
                <div id="noresults" style:"margin-left: 40px" >
  
                      No results found.
     
                </div>';
                }
                else{ //if with value, create table and print the coresponding query
                  echo '<br><br><br><br><div id="wrapper" style:"margin-left: 40px" > <table class = "dq" id="keywords" cellspacing="0" cellpadding="0">
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


            function printRow($row){ //separates the class of with DQ to without DQ then prints the corresponding students
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
                echo "<td>" . substr($row['stunote'], 0, 10) . "</td>"; //outputs the first ten characters
                echo "</tr>";
            }
          ?>
        </div>
      </div> 
</div> 

<script type="text/javascript"> //javascript coommands
  $(function(){
    $("table").tablesorter({
      headers: {
        3: { sorter: false }, //sets the fourth column to be "unsortable"    
        4: { sorter: false }, //sets the fifth column to be "unsortable"
        5: { sorter: false }, //sets the sixth column to be "unsortable"
        6: { sorter: false }, //sets the seventh column to be "unsortable"
        7: { sorter: false } //sets the eighth column to be "unsortable"
      }
    });
  });

  var rows = $('table.dq tr');
  var WITH = rows.filter('.with');
  var WITHOUT = rows.filter('.without');

  $('#w').click(function() { //function that shows the with or without dq students
    $(this).hide();
    $('#wo').show();    
    WITHOUT.hide()
    WITH.show()
  });

  $('#wo').click(function() { //if the "w/"button is clicked, the "w/o" button disappears and vice versa
    $(this).hide();
    $('#w').show();
    WITH.hide()
    WITHOUT.show()
  });
</script>      
</body>
</html>