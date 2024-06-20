<?php

setcookie('jwt', '', time() - 3600, '/', 'brickmmo.com', false, false);
unset($_SESSION['user']);

set_message('Logged Out', 'You have successfully been logged out!');
redirect('/login');