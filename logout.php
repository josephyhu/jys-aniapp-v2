<?php
unset($code);
unset($data);
session_destroy();
header('Location: index.php?logged_out=1');
