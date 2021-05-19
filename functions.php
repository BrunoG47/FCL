<?php
function pdo_connect_mysql()
{
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'tugaspot_tugaspot';
	$DATABASE_PASS = 'Pra@513285776@@@@';
	$DATABASE_NAME = 'tugaspot_fcl';
	try {
		return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
	} catch (PDOException $exception) {
		// If there is an error with the connection, stop the script and display the error.
		exit('Failed to connect to database!');
	}
}