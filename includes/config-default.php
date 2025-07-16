<?php

session_name('{session_name}');
session_start();

$con = mysql_connect('{host}}','{user}','{password}');
mysql_select_db('{db}');
