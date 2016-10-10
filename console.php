#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

(new \Dotenv\Dotenv(__DIR__))->load();

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Symfony\Component\Console\Application $app */
$app = $container->get(\Symfony\Component\Console\Application::class);
$app->run();
