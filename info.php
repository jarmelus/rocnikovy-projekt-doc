<?php
session_start();
include('_inc/db.php');
include('_inc/udaje.php');
include('_inc/funkcie.php');
hlavicka('Informácie o reštaurácii');
include('_partials/navigacia.php');
?>

<section>

<?php

if(isset($_GET['kod'])  && over_kod_restauracie($mysqli, $_GET['kod'])){
	$rest = restauracia_data($mysqli, $_GET['kod']);
	$kod = $_GET['kod'];
	echo "<h2>" . $rest['nazov'] . "</h2>";
	echo "<p>" . $rest['popis'] . "</p>";
    echo "<h2>Adresa</h2>";
	echo "<p>" . $rest['adresa'] . "</p>";
    echo "<h2>Počet voľných miest</h2>";
	echo "<p>" . $rest['pocet_volnych_miest'] . "</p>";
	echo '<a href="rezervacia.php?kod=' . $kod . '">';
  	echo '<input type="submit" name="' . $_GET['kod'] . '" value="Vytvoriť rezerváciu">';
	echo '</a>';
}else{
	echo "<p class='chyba'>Zadaný kód neexistuje, ty beťár!</p>";
}
?>

</section>

<?php
include('_partials/pata.php');
?>