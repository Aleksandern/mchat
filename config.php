<?php

return array(
    'db' => Array (
        'name' => 'mchat',
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
    ),
    'tables' => Array (
        'users' => 'mchat_users',
        'msgs' => 'mchat_msgs',
    ),
    'nick' => 'Anonym', // default nick
    'token' => 1, // включить или выключить проверку token (1 - включено, 0 - выключено)
    'token_salt' => '123123123',
);
