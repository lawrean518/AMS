<?php
if ($_GET['ajax']) {
  echo $_GET['myString'];
} else {
?>
<button type="button" id="clickMe">CLICK ME TO RUN PHP</button>
<pre id="data"></pre>
<?php } ?>