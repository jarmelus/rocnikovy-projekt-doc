<?php
session_start();
include('_inc/db.php');
include('_inc/udaje.php');
include('_inc/funkcie.php');
hlavicka('Zanechajte odkaz');
include('_partials/navigacia.php');
?>

<section>
<?php
$chyby = array();
if (isset($_POST['submit'])){

    if(isset($_POST['cislo_rezervacie']) && (rezervacia_data($mysqli, osetri($_POST['cislo_rezervacie'])) === false) ){
        $chyby['cislo_rezervacie'] = '<p class="chyba">Rezervácia s daným číslom neexistuje alebo bola zadaná chybne!</p>';
    }

    if(isset($_POST['cislo_rezervacie']) && (rezervacia_data($mysqli, osetri($_POST['cislo_rezervacie']))['stav_rezervacie'] != 'done') ){
        $chyby['cislo_rezervacie'] = '<p class="chyba">Rezervácia s daným číslom ešte nebola uzatvorená! Odkazy môžete pridávať až po uzatvorení rezervácie!</p>';
    }

    if(isset($_POST['odkaz']) && empty($_POST['odkaz'])){
        $chyby['odkaz'] = '<p class="chyba">Musíte zadať odkaz!</p>';
    } 
}

if(empty($chyby) && isset($_POST['submit'])) {
    $data = rezervacia_data($mysqli, osetri($_POST['cislo_rezervacie']));
    pridaj_odkaz($mysqli, $data['meno_priezvisko'], $data['rezid'], osetri($_POST['odkaz']));

} else {
    if (!empty($chyby)) {
        foreach($chyby as $ch) {
            echo "<p>$ch</p>\n";
        }
    }	

?>

<form method="post">
        
        <p><label for="cislo_rezervacie">Zadajte číslo Vašej rezervácie:</label>
        <input type="text" name="cislo_rezervacie" id="cislo_rezervacie" size="20" maxlength="40" value="<?php if
        (isset($_POST["cislo_rezervacie"])) echo $_POST["cislo_rezervacie"]; else echo '' ?>" placeholder="č. rezervácie" required>
        </p>

		<p><label for="odkaz">Pridajte odkaz:</label><br>
            <textarea name="odkaz" cols="60" rows="4" id="odkaz" placeholder="Sem vložte svoj odkaz s maximálnou dĺžkou 1000 znakov"><?php if
        (isset($_POST["odkaz"])) echo $_POST["odkaz"]; else echo ''; ?></textarea>
		</p>

        <p><em>Poznámka: Odkazy môžu zanechávať iba zákazníci, ktorých rezervácia už bola uzatvorená! Všetky odkazy sa zobrazia až po kontrole a odsúhlasení administrátora. Ďakujeme za pochopenie. </em></p>
		<p>
			<input name="submit" type="submit" id="submit" value="Pridaj odkaz">
		</p>
	</form>

<?php
vypis_odkazy($mysqli);
}
?>



</section>

<?php
include('_partials/pata.php');
?>