<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>Simple Table Sorting with jQuery - Treehouse Demo</title>
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
  <link rel="stylesheet" href="<?php echo base_url("assets/css/styles.css"); ?>" />
  <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-1.11.2.min.js"); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url("assets/js/jquery.tablesorter.min.js"); ?>"></script>
</head>

<body>
 <div id="wrapper">
  
  <table class="dq" id="keywords" cellspacing="0" cellpadding="0">
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
    <tbody>
      <tr>
        <td class="lalign">silly tshirts</td>
        <td>6,000</td>
        <td>110</td>
        <td>1.8%</td>
        <td>22.2</td>
      </tr>
      <tr>
        <td class="lalign">desktop workspace photos</td>
        <td>2,200</td>
        <td>500</td>
        <td>22%</td>
        <td>8.9</td>
      </tr>
      <tr>
        <td class="lalign">arrested development quotes</td>
        <td>13,500</td>
        <td>900</td>
        <td>6.7%</td>
        <td>12.0</td>
      </tr>
      <tr class = "without" id = "without">
        <td class="lalign">popular web series</td>
        <td>8,700</td>
        <td>350</td>
        <td>4%</td>
        <td>7.0</td>
      </tr>
      <tr class = "with" id = "with">
        <td class="lalign">2013 webapps</td>
        <td>9,900</td>
        <td>460</td>
        <td>4.6%</td>
        <td>11.5</td>
      </tr>
      <tr>
        <td class="lalign">ring bananaphone</td>
        <td>10,500</td>
        <td>748</td>
        <td>7.1%</td>
        <td>17.3</td>
      </tr>
    </tbody>
  </table>
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