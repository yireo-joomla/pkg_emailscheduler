<?php
$logFile = JPATH_SITE . '/logs/com_emailscheduler.log';
file_put_contents($logFile, var_export($variables, true)."\n", FILE_APPEND);
