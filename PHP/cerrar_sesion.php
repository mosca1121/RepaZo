<?php
session_start();
session_unset();
session_destroy();

// Redirige automáticamente a la página de inicio pública
header("Location: ../codigo.php");
exit;
?>