<?php
session_start();
session_unset();
session_destroy();
header("Location: ../html/landing-page.html");
exit();
