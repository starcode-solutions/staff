<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

(new \Dotenv\Dotenv(dirname(__DIR__)))->load();