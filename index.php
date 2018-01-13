<?php
/*
 * Remark:
 *
 * Internet access is required to load Chart.js library files.
 * If no Internet, you must download and put these file in your
 * project directory and then change the "src" path value of
 * the <script> tags on "templates/index.html.php" file.
 *
 */

require_once 'Autoloader.php';
Autoloader::register();
use Com\Dritan\Demographics\Controller\DemographyController;

$controller = new DemographyController();

try {
    $action = isset($_GET['action']) ? trim($_GET['action']) : null;
    if (!$action || strlen($action) == 0) {
        $controller->indexAction();
    } else {
        // php function's call is not sensitive to char case
        $function = [$controller, $action . 'Action'];
        if (is_callable($function)) {
            call_user_func($function);
        } else {
            require './templates/404.html';
        }
    }
}
catch (Exception $ex) {
  echo '<h2>An error appeared...</h2>' . $ex->getMessage();
  echo '<p><a href="./">Go back home</a></p>';
  exit;
}
?>
