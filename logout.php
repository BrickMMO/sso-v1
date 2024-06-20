<?php

setcookie('jwt', '', time() - 3600, '/', 'brickmmo.com', false, false);
unset($_SESSION['user']);

message_set('Logged Out', 'You have successfully been logged out!');
header_redirect('/login');
