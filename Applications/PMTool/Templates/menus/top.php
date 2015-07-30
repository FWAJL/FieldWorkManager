
<?php 
if ($user->getAttribute(\Library\Enums\SessionKeys::UserRole) == 3) {
  require_once 'topBarMobile.php'; 
} else {
  require_once 'topBarDesktop.php'; 
}

