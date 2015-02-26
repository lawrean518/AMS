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
    });
/*
    $("#exportDB").click(function(){
        $("#exportDB").hide();
        $.ajax({
            url: "<?php echo base_url('application/controllers/exportDatabase.php'); ?>",
            success: function(){
              alert("WHAT");
            }
        });
    });*/
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
            <input class="form-control input-sm" placeholder="<?php echo $searchString; ?>"  type="text" name = "INPUT">
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
            if($buttonPushed =='Show All'){
                $query = $this->DCSMS_Model->showAllStudents();

                if($query->num_rows() == 0){
                  echo '  
                  <div id="wrapper">
                  <table id="keywords" cellspacing="0" cellpadding="0">
                    <thead>
                      <tr>
                       <td> No results found. </td>
                      </tr>
                    </thead>
                    </table>
                    </div>';
                }
                else{
                  echo ' <table id="keywords" cellspacing="0" cellpadding="0">
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
        }
    }


  }

  else if($tag == "Search"){
      
    $hidden = array('searchString' => $searchString, 'searchBy' => $searchBy,);
    echo form_open('DCSMS/searchQueryView');
    echo form_hidden($hidden);
    /*
    if(wala laman ung table){
  
    }
  
    else{(ung nasa baba)}   
    */
    echo "<table border = '1' style = 'Width: 90%'>
        <tr>
        <th>Student Number  <input type = 'submit' name = 'ascSN' value ='^'> <input type = 'submit' name = 'descSN' value ='v'></th> 
        <th>Name <input type = 'submit' value ='^' name='ascLN'><input type = 'submit' value ='v' name='descLN'></th>
        <th>Most Recent GWA <input type = 'submit' name = 'descGWA' value ='v'></th>
        <th>AH</th>
        <th>SSP</th>
        <th>MST</th>";

    if($DQTag == "withDQ"){
        $hidden2 = array('withDQTag' => 'TRUE', 'withoutDQTag' => 'FALSE');
        echo form_hidden($hidden2);
        echo "
          <th>Delinquency <input type = 'submit' value ='w/o' name = 'withoutDQ'></form></th>
          <th>Remarks</th>
          </tr>";

        $query = $this->DCSMS_Model->showSearchQuery_sortByAscGWAWithDQ($searchString, $searchBy);
        if($query->num_rows() == 0 || $searchString == ""){

          echo "<table border = '1' style = 'width: 90%'>
                <td> No results found. </td>
                </table>";
        }
        else{
          foreach ($query->result_array() AS $row){
            printRow($row);
          }   
        }
        
    }

    else if($DQTag == "withoutDQ"){
        $hidden2 = array('withDQTag' => 'FALSE', 'withoutDQTag' => 'TRUE');
        echo form_hidden($hidden2);
        echo "
          <th>Delinquency <input type = 'submit' value ='w/' name = 'withDQ'></form></th>
          <th>Remarks</th>
          </tr>";


        $query = $this->DCSMS_Model->showSearchQuery_sortByAscGWAWithoutDQ($searchString, $searchBy);
        
        if($query->num_rows() == 0 || $searchString == ""){

          echo "<table border = '1' style = 'width: 90%'>
                <td> No results found. </td>
                </table>";
        }
        else{
          foreach ($query->result_array() AS $row){
            printRow($row);
          }   
        }
    }

    else{
      $hidden2 = array('withDQTag' => 'FALSE', 'withoutDQTag' => 'FALSE');
      echo form_hidden($hidden2);
      echo "
        <th>Delinquency <input type = 'submit' value ='w/' name = 'withDQ'><input type = 'submit' value ='w/o' name = 'withoutDQ'></form></th>
        <th>Remarks</th>
        </tr>";

      $query = $this->DCSMS_Model->showSearchQuery_sortByAscGWA($searchString, $searchBy);
        
      if($query->num_rows() == 0 || $searchString == ""){

          echo "<table border = '1' style = 'width: 90%'>
                <td> No results found. </td>
                </table>";
      }
      else{
        foreach ($query->result_array() AS $row){
          printRow($row);
        }   
      }
    }   
  }

  echo form_close();
  
  //different views para dun sa sorting and shizz

    function printRow($row){
        echo "<tr>";
          echo "<td> <a href='http://localhost/DCSMS/index.php/DCSMS/showIndividualProfile/" . $row['stunum'] . "' target = '_blank'>" . $row['stunum'] . " </a></td>";
        echo "<td>" . $row['stuname'] . "</td>";
        echo "<td>" . round($row['gwa'], 4) . "</td>";
        echo "<td>" . $row['AH']. "</td>";
        echo "<td>" . $row['SSP'] . "</td>";
        echo "<td>" . $row['MST'] . "</td>";
        echo "<td>" . $row['DQ'] . "</td>";
        echo "<td>" . substr($row['stunote'], 0, 10) . "</td>"; //kelangan first 10 characters lang 
        echo "</tr>";
  }
 /* elseif($buttonPushed=='Search'){
              echo "such button pushed is search";
            }*/
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

  $('#w').click(function() {
    $(this).hide();
    $('#wo').show();
  });

  $('#wo').click(function() {
    $(this).hide();
    $('#w').show();
  });
</script>      



</body>
</html>