<?php
if (! empty($google_analytics_id)) {
    $google_analytics_block = <<<END
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '$google_analytics_id']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; 
ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 
'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; 
s.parentNode.insertBefore(ga, s);
  })();

</script>
END;
  print $google_analytics_block;
}

?>