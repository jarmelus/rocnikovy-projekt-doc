<?php
include('_inc/db.php');
include('_inc/udaje.php');
include('_inc/funkcie.php');
hlavicka('Zoznam reštaurácií');
include('_partials/navigacia.php');
?>

<section>
	<form method="post">
		Zoradiť podľa: 
  	<input type="submit" name="nazov1" value="názvu (A-Z)">
  	<input type="submit" name="nazov2" value="názvu (Z-A)">
  	<input type="submit" name="cena1" value="miest (od najnižšieho voľného počtu)">
  	<input type="submit" name="cena2" value="miest (od najvyššieho voľného počtu)">
  </form> 

<?php
vypis_restauracie($mysqli);
?>
</section>

<?php
include('_partials/pata.php');
?>
