<?php
session_start();
include('_inc/db.php');
include('_inc/udaje.php');
include('_inc/funkcie.php');
hlavicka('Prihlásenie/Administrácia');
include('_partials/navigacia.php');
?>

<section>
 
<?php

if(isset($_POST["prihlasmeno"]) && isset($_POST["heslo"]) && $pouzivatel = over_pouzivatela($mysqli, $_POST["prihlasmeno"], $_POST["heslo"])){
	$_SESSION["uid"] = $pouzivatel["uid"];
	$_SESSION["login"] = $pouzivatel["login"];
	$_SESSION["meno"] = $pouzivatel["meno"];
	$_SESSION["priezvisko"] = $pouzivatel["priezvisko"];
	$_SESSION["admin"] = $pouzivatel["admin"];
}elseif(isset($_POST["odhlas"])){
	session_unset();
	session_destroy();
}

if(isset($_SESSION["login"])){
?>
<p id="hore">Vitajte v systéme <strong><?php echo $_SESSION['meno'] . " " . $_SESSION['priezvisko']; ?></strong>.</p>

<?php
if(isset($_POST['schval_zamietni'])){
	if(isset($_POST['akcia']) && ($_POST['akcia'] == 'a')){
		schval_odkaz($mysqli);
	} elseif(isset($_POST['akcia']) && ($_POST['akcia'] == 'n')){
		odstran_odkaz($mysqli);
	}
}

if(isset($_POST['prijat_odmietnut_uzatvorit'])){
	if(isset($_POST['akcia_rez']) && ($_POST['akcia_rez'] == 'prijat')){
		schval_rezervaciu($mysqli);
	} elseif(isset($_POST['akcia_rez']) && ($_POST['akcia_rez'] == 'odmietnut')){
		zamietni_rezervaciu($mysqli);
	} elseif(isset($_POST['akcia_rez']) && ($_POST['akcia_rez'] == 'uzatvorit')){
		uzatvor_rezervaciu($mysqli);
	}
}

if($_SESSION["admin"] == 1){
	echo '<p>Máte práva administrátora. Môžete prijímať alebo zamietať <a href="#odkazy_cakajuce">odkazy</a> a schvaľovať, zamietať alebo uzatvárať <a href="#rezervacie_neuzatvorene">rezervácie</a>! Kliknutím na ich nádpis sa vrátite hore.</p>';
	schval_vypis_odkazy($mysqli);
	administracia_vypis_rezervacie($mysqli);	
} else{
	echo 'NEmáte práva administrátora. Môžete prezerať ešte neschválené <a href="#odkazy_cakajuce">odkazy</a> a neuzatvorené <a href="#rezervacie_neuzatvorene">rezervácie</a>! Kliknutím na ich nádpis sa vrátite hore.';
	vypis_odkazy_uzivatel($mysqli);
	vypis_rezervacie_uzivatel($mysqli);
} 

?>
<p><em>Legenda:</em> <u>pending</u> = komentár/rezervácia čakajúc-i/-a na spracovanie; <u>accepted</u> = prijaté; <u>declined</u> = odmietnuté</p>
<form method="post"> 
  <p> 
    <input name="odhlas" type="submit" id="odhlas" value="Odhlás ma"> 
  </p> 
</form>

<?php
} else{
?>

	<form method="post">
		<p><label for="prihlasmeno">Prihlasovacie meno:</label> 
		<input name="prihlasmeno" type="text" size="30" maxlength="30" id="prihlasmeno" value="<?php if (isset($_POST["prihlasmeno"])) echo $_POST["prihlasmeno"]; ?>" ><br>
		<label for="heslo">Heslo:</label> 
		<input name="heslo" type="password" size="30" maxlength="30" id="heslo"> 
		</p>
		<p>
			<input name="submit" type="submit" id="submit" value="Prihlás ma">
		</p>
	</form>

<?php 
}
?>

</section>

<?php
include('_partials/pata.php');
?>
