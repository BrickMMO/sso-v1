<?php

setcookie('jwt', '', time() - 3600, '/', 'brickmmo.com', false, false);
unset($_SESSION['user']);

setcookie('hash_id', '', time() - 1, '/', 'brickmmo.com');
setcookie('hash_string', '', time() - 1, '/', 'brickmmo.com');

message_set('Logged Out', 'You have successfully been logged out!');
header_redirect('/login');
