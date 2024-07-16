<?php
// main-include.php
// This file is included in every page of the application

define("APP_NAME", "System");
define("APP_VERSION", "0.3.5");
define("APP_URL", "localhost");

// error_reporting(E_ALL);

ini_set("display_errors", "Off");

// set default title
$title = APP_NAME . " " . APP_VERSION;

// Display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// database connection
define("DB_HOST", getenv('MYSQL_HOST'));
define("DB_NAME", getenv('MYSQL_DATABASE'));
define("DB_USER", getenv('MYSQL_USER'));
define("DB_PASS", getenv('MYSQL_PASSWORD'));

// log files
define("LOG", "log/system.log");
define("SQL_LOG", "log/sql.log");
define("JSON_LOG", "log/json.log");

// financial constants
define("TARGET_NET_PROFIT_MARGIN", 0.3);
define("SHOP_RATE", 75);
define("COST_OF_OPERATION", (SHOP_RATE*(1-TARGET_NET_PROFIT_MARGIN)));

// Google stuff
define("CLIENT_ID", "844461726006-h9o1vgij5vim8v32tcfhais08erajd93.apps.googleusercontent.com");

// brand new MySQLi function
function db($sql, $multipleRows = true, $fetch = true ) {
	$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($connection->connect_errno) {
			ISLog("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . " " . $mysqli->host_info . " ");
		}
	$result = $connection->query($sql);

	// log to file
	$log = $connection->error . " " . $sql;
	ISLog($log, SQL_LOG);

	$connection->close();
	if ($fetch == true) {
		if ($multipleRows == true) {
			$rows = array();
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		} elseif ($multipleRows == false) {
			$rows = $result->fetch_assoc();
		}
		return($rows);
	} else {
		return true;
	}
}

function quickTable($array, $passedColumns = false) {
	echo "<table class=\"quicktable\">";
	echo "<tr>";
	if ($passedColumns) {
		foreach ($passedColumns as $name) {
			echo "<th>".$name."</th>";
		}
	}
	echo "</tr>";
	foreach ($array as $rows) {
		echo "<tr>";
		if (is_array($passedColumns)) {
			foreach ($passedColumns as $column) {
				echo "<td>".$rows[$column]."</td>";
			}
		} else {
			foreach ($rows as $column) {
				echo "<td>".$column."</td>";
			}
		}

		echo "</tr>";
	}
	echo "</table>";
}

function ISLog($writeto, $target = LOG) {
	$writeto = date('Y-m-d H:i:s')." : ".$writeto."\n";
	// $writeto .= file_get_contents($target);
	// file_put_contents($target, $writeto);
    // write to end of file
    file_put_contents($target, $writeto, FILE_APPEND);
}

function log_reverse($writeto, $target = LOG) {
	$writeto = date('Y-m-d H:i:s')." : ".$writeto."\n";
	$writeto .= file_get_contents($target);
	file_put_contents($target, $writeto);
}