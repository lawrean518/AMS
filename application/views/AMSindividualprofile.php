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
        <div class="col-lg-10">
          <br>
          <div class = "logo"><img style = "float:left" src="<?php echo base_url("img/upd_dcs_logo.png"); ?>" alt="DCS Logo"></div>
          <div class = "vertical">
          <h2>Department of Computer Science</h2>
          <p class="lead">Academic Monitoring System</p></div>
        </div>
      </div> 
      <div class="row">
          <br>
          <?php 
              $this->load->helper('form');
              $stuNum = $StuNum;
              $query = $this->DCSMS_Model->getStuNameAndNote($stuNum);
              $row = $query->first_row('array');
              $stuName =  $row['stuname'];
              $stuNote = $row['stunote'];
              echo "<h4> View Grades </h4>";
              echo "<h5><br>       " .$stuNum. "";
              echo "<br>      " .$stuName. "</h5>";

              $query->free_result();
          ?>
      </div>
      <div class = "row">
        <?php
          $query = $this->DCSMS_Model->getStudent($stuNum); //example query
          //query that returns a table of student A's grades, subjects, semester, schoolyear, ordered by sem and schoolyear         
          echo "<br><div id = 'wrap'><table class = 'dq' id = 'keywords' cellspacing='0' cellpadding='0' border = '1' style = 'Width: 100%'>";
          $row = $query->first_row('array');  
  
          $currentYear = $row['SchoolYear']; //first entry dapat sa table
          $currentSem = $row['Sem']; //first entry dapat sa table
          $count = 0;
          if($currentSem == 1){
            echo "<th colspan = '8'>" .$currentSem. "st Semester, AY '" .substr($currentYear, 0, 2). "-'" .substr($currentYear, 2, 4) . "</th>";

          }
          else if($currentSem == 2){
            echo "<th colspan = '8'>" .$currentSem. "nd Semester, AY '" .substr($currentYear, 0, 2). "-'" .substr($currentYear, 2, 4) . "</th>"; 
          }
          else if($currentSem == 3){ 
            echo "<th colspan = '8'> Midterm Semester, AY '" .substr($currentYear, 0, 2). "-'" .substr($currentYear, 2, 4) . "</th>"; 
          }

          echo "  <tr>
            <th>Subject</th>
            <th>Units</th>
            <th>Grade</th>
            </tr>";

          foreach($query->result_array() AS $row){ //while table still has rows unread
            if($currentYear == $row['SchoolYear'] && $currentSem == $row['Sem']){ //if currentYear and Sem ay ung   current entry sa table
                echo "<tr>";
                echo "<td>" . $row['StuSubject'] . "</td>"; //echo ung subject
                echo "<td>" . $row['Units'] . ".0". "</td>"; //echo ung units
      
                if($row['Grade'] == 1 || $row['Grade'] == 2 || $row['Grade'] == 3 || $row['Grade'] == 4 || $row['Grade'] == 5){
                    echo "<td>" . $row['Grade'] . ".0". "</td>"; //echo ung grade
                }else{
                echo "<td>" . $row['Grade'] . "</td>"; //echo ung grade
                }
                echo "</tr>";
            }
            else{ //new table na dapat since supposedly new year or new sem na
                echo "</table></div>";
                $currentYear = $row['SchoolYear']; //replace with the new AY
                $currentSem = $row['Sem']; //replace with the new Sem
                echo "<br><br>";
                echo "<div id = 'wrap'><table class = 'dq' id = 'keywords' cellspacing='0' cellpadding='0' border = '1' style = 'Width: 100%'>";
                if($currentSem == 1){
                  echo "<th  colspan = '8'>" .$currentSem. "st Semester, AY '" .substr($currentYear, 0, 2). "-'" .substr($currentYear, 2, 4) . "</th>";
                }
                else if($currentSem == 2){
                  echo "<th  colspan = '8'>" .$currentSem. "nd Semester, AY '" .substr($currentYear, 0, 2). "-'" .substr($currentYear, 2, 4) . "</th>"; 
                }
                else if($currentSem == 3){ 
                  echo "<th  colspan = '8'> Midterm Semester, AY '" .substr($currentYear, 0, 2). "-'" .substr($currentYear, 2, 4) . "</th>"; 
                }
                echo "<tr>
                      <th>Subject</th>
                      <th>Units</th>
                      <th>Grade</th>
                      </tr>";
                //then put new data
                echo "<tr>";
                echo "<td>" . $row['StuSubject'] . "</td>"; //echo ung subject
                echo "<td>" . $row['Units'] . ".0". "</td>"; //echo ung units
                

                if($row['Grade'] == 1 || $row['Grade'] == 2 || $row['Grade'] == 3 || $row['Grade'] == 4 || $row['Grade'] == 5){
                  echo "<td>" . $row['Grade'] . ".0". "</td>"; //echo ung grade
                }else{
                  echo "<td>" . $row['Grade'] . "</td>"; //echo ung grade
                }
                
                echo "</tr>";
            }
          }
          echo "</table>";  
          echo "</div>";
          echo "<br><br>";
         echo "<div id = 'wrap'><table class = 'dq' id = 'keywords' cellspacing='0' cellpadding='0' border = '1' style = 'Width: 100%'>";;
          echo "<th>" .'Delinquencies'. "</th>";

          $query->free_result();
          $query = $this->DCSMS_Model->getDQs($stuNum);
          if($query->num_rows() == 0){
            //echo "<p> None. </p>";
            echo "<tr><td> None. </td></tr>";
          }
          else{
            foreach($query->result_array() AS $row){
              echo "<tr><td>" .$row['DQDetails'] . "</td></tr>";
            }
          }
          echo "</table></div>";

          echo form_open('DCSMS/showIndividualProfile_/' .$stuNum. '');
          echo "<br><br>";
          echo "<textarea name='myRemark'>" .$stuNote. "</textarea>";
          echo "<br>";
          $data = array(  'name' => 'newRemark',  
                    'value' => 'update',
                    'class' => 'button');
          echo form_submit($data);
          echo form_close();
        ?>
       
</div> 
</body>
</html>