<?php

$mysqli = new mysqli('localhost', 'root', '', 'rp');
if ($mysqli->connect_errno) {
	echo '<p class="chyba">NEpodarilo sa pripojiť! (' . $mysqli->connect_errno . ' - ' . $mysqli->connect_error . ')</p>';
} else {
	$mysqli->query("SET CHARACTER SET 'utf8'");
}

?>