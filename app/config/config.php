<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'driving_license_system');

define('APP_NAME', 'Driving Liscense System');
define('BASE_URL', 'http://localhost/DrivingLiscenseSystem/public');
define('APP_ROOT', dirname(dirname(__FILE__)));

define('UPLOAD_PATH', APP_ROOT . '/../public/uploads/');
define('PROFILE_UPLOAD_PATH', UPLOAD_PATH . 'profiles/');
define('LICENSE_UPLOAD_PATH', UPLOAD_PATH . 'licenses/');

define('TEMP_LICENSE_VALIDITY_MONTHS', 6);
define('PASSING_SCORE', 60); 

define('RECORDS_PER_PAGE', 10);

date_default_timezone_set('Asia/Colombo');

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>