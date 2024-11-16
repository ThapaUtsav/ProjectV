<?php

session_start();


session_unset();  
session_destroy();  


header("Location: /PROJECT%20V/Userlogin/userlogin.html");
exit();
?>