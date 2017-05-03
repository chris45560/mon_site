<?php
session_start();
session_unset(); // on détruit les variables $_SESSION
session_destroy();
// $_SERVER['HTTP_REFERER']; ==> On peut utiliser la variable $_SERVER['HTTP_REFERER'] pour revenir à la page en cours lors de la déconnexion
