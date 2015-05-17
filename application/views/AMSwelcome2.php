<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />

  <link rel="stylesheet" href="<?php echo base_url("assets/css/styles.css"); ?>" />
  <title>AMS</title>

<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-1.11.2.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
<script type="text/javascript">
   function searchByFunction(){
    $("li").click(function(){
      var selText = $(this).text();
      $("#DD").html(selText + "<span class = 'caret'></span>");
      $("#DD").val(selText);
    });
  }
</script>
</head>
<body background = "img/congruent_pentagon.png">

<div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 text-center v-center">
          <br><br>
          <img src="img/upd_dcs_logo.png" alt="DCS Logo">
          <h1>Department of<br>Computer Science</h1>
          <p class="lead1">Academic Monitoring System</p>         
          <br>
          <form class="col-lg-12" method = "get">
            <div class="input-group" style="width:400px;text-align:center;margin:0 auto;">
              <input class="form-control input-lg" placeholder="Search" type="text" name = "INPUT">
                <span class="input-group-btn">
                 <span class="input-group-btn">
                  <button onclick = "searchByFunction()" type="button" class="btn btn-lg btn-success dropdown-toggle" name = "DD" id = "DD" data-toggle="dropdown" value = "Student Number">Student Number<span class = "caret"></span></button><input type = "hidden" name = "DROPDOWN" id = "DROPDOWN" value = "Student Number">
                  <ul class="dropdown-menu" role="menu">
                    <li><a onclick = "searchByFunction()" href="#">Student Number</a></li>
                    <li><a onclick = "searchByFunction()" href="#">Last Name</a></li>
                    </ul>
                </span>
            </div>
            <br><button class="btn btn-lg btn-primary" name="submit" value="Search" type="submit" formaction = "<?php echo site_url("DCSMS/search");?>">SEARCH</button><p>
            </p><button class="btn btn-lg btn-primary" id="try"  name="submit" value="Show All" type="submit" formaction = "<?php echo site_url("DCSMS/showAll");?>">SHOW ALL</button>
            </p><button class="btn btn-lg btn-primary" id="update"  name="updatebtn" value="Update Try" type="button">TRY UPDATE</button>
          </form>
        </div>
      </div> <!-- /row -->
</div> <!-- /container full -->


<div id="siteloader" style = "border: 2px solid red"></div> <!-- for debugging purposes. if a webpage is loaded in a div class (buburahin after ok na yung code natin) -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#update').click(function(){ //when Update try is clicked eto mangyayari dapat

      $("#siteloader").html('<object id="crs-object" data="https://crs.upd.edu.ph/viewgrades/" style = "width: 791px"/>'); //eto yung makikita sa div na webpage
      var grades = [];
      var subjects = [];
      var units = [];
      var sems = [];
      var gwas = [];
      var studname;
      var studnum;

      var temp;
      var index1;
      var index2;

      var jsonText;


  
      setTimeout(function(){ //nagseset time out para mag run yung ibang function para syang sleep thread
       
        o = $('object');
        p = $('object');

        $('.invisible', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 0){
              studname = ($(this).html());
            }
            if(index == 2){
              temp = $(this).html();
              temp = temp.replace("-", "");
              studnum = (temp);
            }
          });
        });

        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('th', p[0].contentDocument).each(function(index, value){
            if(index == 0){
              temp = $(this).html();
              if(temp.search("Summer") != -1 || temp.search("Semester") != -1){
                if(temp.search("Tag") == -1){
                  sems.push($(this).html());
                }
              }
            }
          });
        });
      
        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 5){
              temp = $(this).html().trim();
              if(temp.charAt(0) != ""){
                grades.push(temp);
              }
            }
          });
        });
        
        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 2){
              temp = $(this).html();
              if(temp.search("strong") == -1){
                index1 = temp.indexOf(" ");
                index2 = temp.indexOf(" ", index1+1);
                temp = temp.substring(0, index2);
                if(temp.charAt(0) != ""){
                  subjects.push(temp);
                }
              }
              else{
                index1 = temp.indexOf(">");
                index2 = temp.indexOf("<", index1);
                temp = temp.substring(index1+1, index2);
                gwas.push(temp);
              }
            }
          });
        });

        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 4){
              temp = $(this).html();
              temp = temp.replace("("

                , "");
              temp = temp.replace(")", "");
              units.push(temp);
            }
          });
        });  

        alert(sems);
        alert(grades);
        alert(subjects);
        alert(gwas);
        alert(units);
        alert(studnum);
        alert(studname);
        

  

      }, 3000);
    });
  });

</script>
</body>
</html>