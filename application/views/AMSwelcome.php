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
          <br><br><br>
          <form class="col-lg-12" method = "get">
            <div class="input-group" style="width:400px;text-align:center;margin:0 auto;">
              <input class="form-control input-lg" style="width:100%" placeholder="Search" type="text" name = "INPUT">
                <span class="input-group-btn">
                 <span class="input-group-btn">
                  <button onclick = "searchByFunction()" type="button" class="btn btn-lg btn-success dropdown-toggle="collapse" " name = "DD" id = "DD" data-toggle="dropdown" value = "Student Number">Student Number<span class = "caret"></span></button><input type = "hidden" name = "DROPDOWN" id = "DROPDOWN" value = "Student Number">
                  <ul class="dropdown-menu" role="menu">
                    <li><a onclick = "searchByFunction()" href="#">Student Number</a></li>
                    <li><a onclick = "searchByFunction()" href="#">Last Name</a></li>
                    </ul>
                </span>
            </div>
            <br><button class="btn btn-lg btn-primary" name="submit" value="Search" type="submit" formaction = "<?php echo site_url("DCSMS/search");?>">SEARCH</button><pre></pre>
            <button class="btn btn-lg btn-primary" id="try"  name="submit" value="Show All" type="submit" formaction = "<?php echo site_url("DCSMS/showAll");?>">SHOW ALL</button><pre></pre>
            <button class="btn btn-lg btn-primary" id="update"  name="updatebtn" value="Update Try" type="button">UPDATE</button>
          </form>
        </div>
      </div> <!-- /row -->
</div> <!-- /container full -->


<div id="siteloader"></div> <!-- for debugging purposes. if a webpage is loaded in a div class (buburahin after ok na yung code natin) -->
<script type="text/javascript">


  $(document).ready(function() {
    $('#update').click(function(){ //when Update try is clicked eto mangyayari dapat

      $("#siteloader").html('<object id="crs-object" data="https://crs.upd.edu.ph/viewgrades/" style = "width: 791px"/>'); //eto yung makikita sa div na webpage

      var rawFile = new XMLHttpRequest();

      function readTextFile(file){
        
        rawFile.open("GET", file, false);
        rawFile.onreadystatechange = function (){
            if(rawFile.readyState === 4){
                if(rawFile.status === 200 || rawFile.status == 0){
                    var allText = rawFile.responseText;
                    getStudents = allText;
                }
            }
        }
        rawFile.send(null);
      } 

      readTextFile("assets/students/students.txt");
      var theStudents = [];
      theStudents = getStudents.split("\n");
      theLength = theStudents.length;

      x = 0;
    
      // HUWAG TANGGALIN SA PAGKAKACOMMENT! var studarr = ['201261188', '201265955', '201238409'];

      var jsonText = "[" ; 
      var firstStudent = true;
      
      var studentsInJsonText = "";
      //HUWAG TANGGALIN SA PAGKAKACOMMENT! setTimeout(function(){  
      
      o = $('object');
      p = $('object');

      x=0;

      var checker = function(){ 

        $('#txt_studentno', o[0].contentDocument).val(theStudents[x]);
        $('input', o[0].contentDocument).each(function(index, value){
          if(index == 2){
            $(value).click(); 
          }
        });

        setTimeout(function(){
          var grades = [];
          var subjects = [];  
          var gecount = [];
          var units = [];
          var sems = [];
          var gwas = [];
          var pass = [];

          var studname;
          var studnum;

          var temp;
          var index;
          var index1;
          var index2;
          var a = 0;


          var passed;
          var failed;

          $('.tinytext', o[0].contentDocument).each(function(index, value){
            temp = $(this).html();
            index1 = temp.indexOf("%");

            if(index1 != -1){
              var temp2 = temp.split("<br>");
              index1 = temp2[0].indexOf("%");
              passed = temp2[0].substring(0, index1).trim();
              index1 = temp2[1].indexOf("%");
              failed = temp2[1].substring(0, index1).trim();
              pass.push(passed);
              pass.push(failed);
            }
          });
          
          $('.invisible', o[0].contentDocument).each(function(index, value){
            $(this).find('td', p[0].contentDocument).each(function(index, value){
              if(index == 0){
                studname = ($(this).html());
              }
              if(index == 2){
                temp = $(this).html();
                temp = temp.replace("-", "");
                studnum = temp;
              }
            });
          });

        if(studname){
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
          console.log(studname);
          $('tr', o[0].contentDocument).each(function(index, value){
            $(this).find('td', p[0].contentDocument).each(function(index, value){
              if(index == 5){
                temp = $(this).html().trim();
                if(temp.charAt(0) != ""){
                  if(temp.indexOf("(") != -1){
                    index1 = temp.indexOf("(");
                    index2 = temp.indexOf(")");
                    temp = temp.substring(index1+1, index2-1);
                    grades.push(temp);
                  }
                  else if(temp.search("INC") != -1){
                    grades.push(6);
                  }
                  else if(temp.search("DRP") != -1){
                    grades.push(7)
                  }
                  else{
                    grades.push(temp);
                  }
                }
              }
            });
          });
          
          $('tr', o[0].contentDocument).each(function(index, value){
            $(this).find('td', p[0].contentDocument).each(function(index, value){
              if(index == 2){
                temp = $(this).html().trim();
                if(isNaN(temp)){
                  if(temp.search("strong") == -1){
                    index1 = temp.indexOf(" ");
                    index2 = temp.indexOf(" ", index1+1);
                    if(index1 != -1){
                      if(index2 == -1){
                        temp = temp.substring(0, index1);
                      }
                      else{
                        var index3 = temp.indexOf("&");
                        if(index3 == -1){ 
                          if(isNaN(temp.charAt(index1+1))){
                            index3 = temp.indexOf(" ", index2+1);
                            temp = temp.substring(0, index3);
                          }
                          else{
                            temp = temp.substring(0, index2);
                          }
                        }
                        else{
                          temp = temp.replace("&amp;", "&");
                          index2 = temp.indexOf(" ", index3+2);
                          temp = temp.substring(0, index2);
                        }
                      }
                      if(temp.charAt(0) != ""){
                        subjects.push(temp);
                      }
                    }

                  }
                  else{
                    index1 = temp.indexOf(">");
                    index2 = temp.indexOf("<", index1);
                    temp = temp.substring(index1+1, index2);
                    subjects.push("End of Sem");
                    gwas.push(temp);
                  }
                }
                else{
                  if(temp.charAt(0) != ""){
                    if(a < 3){
                      a++;
                      gecount.push(temp);
                    }
                  }
                }
              }
            });
          });

          $('tr', o[0].contentDocument).each(function(index, value){
            $(this).find('td', p[0].contentDocument).each(function(index, value){
              if(index == 4){
                temp = $(this).html();
                temp = temp.replace("(", "");
                temp = temp.replace(")", "");
                units.push(temp);
              }
            });
          });  

         
       
          appendToJSONString();
          studentsInJsonText = studentsInJsonText + "" + studname;

          function appendToJSONString(){
            var i;
            var j = 0;
            var k = 0;
            var l = 0;
            var semNumber;
            var schoolYear;

            var index3;
            var index4;

            var firstLoop1 = false;

            //insert comma before {
            if(firstStudent){
              jsonText = jsonText + " {\"name\": \"" + studname + "\", \"stunum\": " + parseInt(studnum) + ", \"AH\": " + parseInt(gecount[0]) + ", \"SSP\": " + parseInt(gecount[1]) + ", \"MST\": " + parseInt(gecount[2]) + ", \"grades\": [";
              firstStudent = false; 
            }
            else{
              jsonText = jsonText + ", {\"name\": \"" + studname + "\", \"stunum\": " + parseInt(studnum) + ", \"AH\": " + parseInt(gecount[0]) + ", \"SSP\": " + parseInt(gecount[1]) + ", \"MST\": " + parseInt(gecount[2]) + ", \"grades\": [";
            }

            for (i = 0; i < sems.length; i++){
              index3 = sems[i].search(" ");
              index4 = sems[i].search("AY") + 3; //start index nung SY
              semNumber = sems[i].substring(0, index3);
              if(semNumber.search("First") != -1){
                semNumber = 1;
              }
              else if(semNumber.search("Second") != -1){
                semNumber = 2;
              }
              else if(semNumber.search("Summer") != -1){
        
                semNumber = 3;
              }
              if(semNumber == 3){  
                var after = parseInt(sems[i].substring(index3+3, index3+5));
                var before =  after - 1;
              
                schoolYear = "" + before + "" + after;
                parseInt(schoolYear);
              }
              else{
                schoolYear = sems[i].substring(index4+2, index4+4) + "" + sems[i].substring(index4+7, index4+9);  
              }
              if(!firstLoop1){
                jsonText = jsonText + "{ ";
                firstLoop1 = true;
              }  
              else{
                jsonText = jsonText + ", { ";
              }
              jsonText = jsonText + "\"semNumber\": " + semNumber + ", \"schoolYear\": " + schoolYear + ", \"GWA\": " + gwas[i] + ", \"pass\": " + parseInt(pass[l]) + ", \"fail\": " + parseInt(pass[l+1]) + ", \"GradesForSem\": [ ";
              l = l+2;
              var firstLoop2 = false;
              while(subjects[j].search("End of Sem") == -1){
                if(!firstLoop2){  
                  jsonText = jsonText + "{ ";
                  firstLoop2 = true;
                }
                else{
                  jsonText = jsonText + ", {";
                }
                
                jsonText = jsonText + " \"subject\": \"" + subjects[j] + "\", \"grade\": " + parseFloat(grades[k]) + ", \"units\": " + parseInt(units[k]) + " }";
                j++;
                k++;
              }

              j++;
              if(k >= grades.length){
                break;
              }
              jsonText = jsonText + " ] }";
            }
            jsonText = jsonText + " ] } ] }";
          }
        }
        }, 6000);
        
        x = x+1;
        if(x == theLength){
          clearInterval(timer);
        }
    };
     
    timer = setInterval(checker, 6000);
   
    setTimeout(function(){
      jsonText = jsonText + " ]";
      var text = JSON.parse(jsonText);      
      var jsons = JSON.stringify(text);
      var urlz = "<?php echo site_url("DCSMS/script");?>";
      $.ajax({
          type: 'POST',
          data: {json: jsons},
          dataType: 'html',
          url: urlz,
            success: function () {
                  alert("Database updated.");
              },
            error: function (xhr, ajaxOptions, thrownError) {
            }
      });
    }, 20000);
    });
    
  });

</script>
</body>
</html>