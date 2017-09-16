<?php
if (file_exists(JPATH_LIBRARIES . '/yireo/loader.php'))
{
    return require_once(JPATH_LIBRARIES . '/yireo/loader.php');
}
else
{
    require_once JPATH_ADMINISTRATOR . '/components/com_emailscheduler/lib/loader.php';
}
