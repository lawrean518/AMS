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
      var studname;
      var studnum;

      setTimeout(function(){ //nagseset time out para mag run yung ibang function para syang sleep thread
       
        o = $('object');
        p = $('object');

        $('.invisible', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 0){
              //alert($(this).html());
              studname = ($(this).html());
            }
            if(index == 2){
              //alert($(this).html());
              studnum = ($(this).html());
              //alert(studnum);
            }
          });
        });

        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('th', p[0].contentDocument).each(function(index, value){
            if(index == 0){
              //alert($(this).html());
              sems.push($(this).html());
              //alert(sems);
            }
          });
        });
      
        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 5){
              //alert($(this).html());
              grades.push($(this).html());
              //alert(grades);
            }
          });
        });
        
        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 2){
              //alert($(this).html());
              subjects.push($(this).html());
              //alert(classes);
            }
          });
        });

        $('tr', o[0].contentDocument).each(function(index, value){
          $(this).find('td', p[0].contentDocument).each(function(index, value){
            if(index == 4){
              //alert($(this).html());
              units.push($(this).html());
              //alert(units);
            }
          });
        });  
        
      /*  alert(sems);
        alert(grades);
        alert(subjects);
        alert(units);
        alert(studnum);
        alert(studname);
*/
        //var jsons = JSON.stringify(grades);
        var person = JSON.parse("[" + '{\"firstName\":\"John\", \"lastName\":\"Doe\", \"age\":46}, {\"firstName\":\"Olivia\", \"lastName\":\"Demetria\", \"age\":46}' +"]");
        var textToParse = "{" + '\"info\" : [{ \"name\" : \"Olivia\", \"stunum\" : \"2012-61188\" }, { \"name\" : \"Olivia2\", \"stunum\" : \"2012-61189\" }], \"grades\" : {\"' + name + '\" : [ { \"SemNumber\" : 1, \"SchoolYear\" : 1213, \"GWA\": 3, \"GradesForSem\" : [ { \"grade\": 1, \"subject\": \"ES 10\", \"units\": 3 } ] } ] }' + "}";
        var textToParse2 = "[" + '{\"name\" : \"Olivia\", \"stunum\" : 201261188, \"grades\" : [ {\"SemNumber\" : 1, \"SchoolYear\" : 1213, \"GWA\": 3, \"GradesForSem\" : [ { \"grade\": 1, \"subject\": \"ES 10\", \"units\": 3} ] } ] }, {\"name\" : \"Tetey\", \"stunum\" : 201238409, \"grades\" : [ {\"SemNumber\" : 1, \"SchoolYear\" : 1213, \"GWA\": 3, \"GradesForSem\" : [ { \"grade\": 3, \"subject\": \"Bio 1\", \"units\": 3} ] } ] }' + "]";
        var text = JSON.parse(textToParse2);      
        
        
        //var jsons2 = JSON.parse(text);
        //var jsons = JSON.stringify(jsons2);
        var jsons = JSON.stringify(text);
        $.ajax({
            type: 'POST',
            data: {json: jsons},
            dataType: 'html',
            url: "<?php echo site_url("DCSMS/script");?>",
              success: function (meeeh) {
                    console.log("SUCH LIFE");
                    alert(meeeh);
                },
                error: function (xhr, ajaxOptions, thrownError) {
              alert(xhr.status);
              alert(thrownError);
          }
        });
      }, 3000);
 
    });
  });

</script>
</body>
</html>