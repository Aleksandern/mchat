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
    'token' => 1, // (1 - on, 0 - off)
    'token_salt' => '123123123',
);
