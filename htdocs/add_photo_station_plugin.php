<?php
    $website_root="/var/services/web/joomla";
    exec("/usr/local/bin/php56 $website_root/cli/installextension.php -p $website_root/tmp/mod_photostation.zip", $execoutput, $execstatus);
?>
