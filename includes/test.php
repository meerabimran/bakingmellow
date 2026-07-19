<?php

echo "<pre>";

var_dump(extension_loaded('mysqli'));
var_dump(class_exists('mysqli'));

$conn = new mysqli("localhost", "root", "", "");

echo "MySQLi is working!";