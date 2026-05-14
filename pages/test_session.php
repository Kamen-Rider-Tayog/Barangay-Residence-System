<?php
require_once '../includes/init.php';

echo '<pre>';
echo 'SESSION data:<br>';
print_r($_SESSION);
echo '</pre>';

echo 'isLoggedIn(): ' . (isLoggedIn() ? 'TRUE' : 'FALSE') . '<br>';
echo 'isResident(): ' . (isResident() ? 'TRUE' : 'FALSE') . '<br>';
echo 'isAdmin(): ' . (isAdmin() ? 'TRUE' : 'FALSE') . '<br>';
?>