<?php
include('_inc/db.php');
include('_inc/udaje.php');
include('_inc/funkcie.php');
hlavicka('Rezervácia');
include('_partials/navigacia.php');
?>

<section>
<?php 

$chyby = array();
if (isset($_POST["posli"])) {
	$meno_priezvisko = osetri($_POST['meno_priezvisko']);
	$pocet_osob = osetri($_POST['pocet_osob']);
    if (isset($_POST['deti'])) $deti = osetri($_POST['deti']);
	$email = osetri($_POST['email']);
	$tel_cislo = osetri($_POST['tel_cislo']);
    $poznamka = osetri($_POST['poznamka']);
    $restauracia = osetri($_POST['restauracia']);
    $datum = osetri($_POST['datum']);
	
	if (!spravne_meno($meno_priezvisko)) $chyby['meno_priezvisko'] = 'Meno nie je v správnom formáte! Meno a priezvisko musia byť dĺžky minimálne 3 znaky a obsahovať medzi sebou presne 1 medzeru!';
	if (empty($meno_priezvisko)) $chyby['meno_priezvisko'] = 'Nezadali ste meno!';
    if ($pocet_osob == '') $chyby['pocet_osob'] = 'Nezvolili ste počet osôb!';
    if (!dostupnost_miesta($mysqli, $pocet_osob, $restauracia) && !($restauracia == '') && !($pocet_osob == '')) $chyby['pocet_osob'] = 'Daná reštaurácia nemá dostatočný počet voľných miest! Prosím, znížte počet hostí alebo zmeňte reštauráciu!';
    if (!isset($_POST['deti'])) $chyby['deti'] = 'Nevyjadrili ste sa k deťom!';
	if (!spravny_email($email)) $chyby['email'] = 'Email nie je v správnom formáte! Musí obsahovať @ a maximálne 4 znaky najvyššej domény (znaky po symbole .)!';
	if (empty($email)) $chyby['email'] = 'Nezadali ste email!';
    if (!spravne_cislo($tel_cislo)) $chyby['tel_cislo'] = 'Telefónne číslo nie je v správnom formáte! Musí obsahovať symbol + na prvom miesta a byť dĺžky 12 číslic!';
	if (empty($tel_cislo)) $chyby['tel_cislo'] = 'Nezadali ste telefónne číslo!';
	if ($restauracia == '') $chyby['restauracia'] = 'Nezvolili ste žiadnu reštauráciu!';
    if ($datum == '') $chyby['datum'] = 'Nezvolili ste dátum!';
}
 	
     if (empty($chyby) && isset($_POST["posli"])) {
        if (!$mysqli->connect_errno) {
            $sql = "INSERT INTO gastroapp_rezervacie SET meno_priezvisko='$meno_priezvisko', deti='$deti', pocet_osob='$pocet_osob', email='$email', telefon='$tel_cislo', poznamka='$poznamka', rid='$restauracia', datum_rezervacie='$datum', datum_vytvorenia=NOW(),  stav_rezervacie='pending'"; // definuj dopyt
            if ($result = $mysqli->query($sql) ) {  // vykonaj dopyt
                echo '<p><strong>Rezervácia bola úspešne zaevidovaná pod číslom ' . $mysqli->insert_id . '</strong>!</p>';
            } else {
                // dopyt sa NEpodarilo vykonať!
                echo '<p class="chyba">NEpodarilo sa získať údaje z databázy!</p>' . $mysqli->error ;
            }
        }
     } else{
        if (!empty($chyby)) {
            echo '<p class="chyba">Nevyplnili ste všetky povinné polia objednávky (vrátane detí), resp. ste niektoré polia nevyplnili korektne!</p>';
            echo '<p class="chyba"><strong>Chyby v objednávke</strong>:<br>';
            foreach($chyby as $ch) {
                echo "$ch<br>\n";
            }
            echo '</p>';
        }

?>

<form method="post">
        <fieldset>
		<legend>Údaje o rezervovateľovi</legend>

		<p>
		<label for="meno_priezvisko">Meno a priezvisko:*</label>
        <input type="text" name="meno_priezvisko" id="meno_priezvisko" size="60" maxlength="40" value="<?php if
        (isset($_POST["meno_priezvisko"])) echo $_POST["meno_priezvisko"]; ?>" placeholder="max. 40 znakov, meno a priezvisko aspoň 3 znaky oddelené iba 1 medzerou" required>
        </p>

        <p>
		<label for="pocet_osob">Počet osôb:*</label>
        <select name="pocet_osob" id="pocet_osob" required>
			<option value="">-</option>
			<?php 
            if (isset($_POST['pocet_osob'])) vypis_select(1, 30, $_POST['pocet_osob']);
            else vypis_select(1, 30, -1); ?>
		</select>
        </p>

        <p>
        Deti:*
        <input type="radio" name="deti" id="deti_ano" value="1" <?php if (isset($_POST['deti']) && ($_POST['deti'] == '1')) echo 'checked'; ?>> <label for="deti_ano">áno</label>
        <input type="radio" name="deti" id="deti_nie" value="0" <?php if (isset($_POST['deti']) && ($_POST['deti'] == '0')) echo 'checked'; ?>> <label for="deti_nie">nie</label>
        </p>

        <p>
            <label for="email">Email:*</label>
            <input type="email" name="email" id="email" value = "<?php if
            (isset($_POST["email"])) echo $_POST["email"]; ?>" placeholder="max. 70 znakov so @ a najvyššia domená max. 4 znaky" size="45" required>
            <label for="tel_cislo">Telefónne číslo:*</label>
            <input type="tel" name="tel_cislo" id="tel_cislo" value = "<?php if
            (isset($_POST["tel_cislo"])) echo $_POST["tel_cislo"]; ?>" placeholder="predvoľba s presne 12 číslicami" size="23" required>
        </p>

        <p>
		<label for="poznamka">Poznámka:</label><br>
        <textarea name="poznamka" id="poznamka" rows="4" cols="50" placeholder="Tu môžete uviesť poznámky, žiadosti, otázky a všetky relevantné informácie pre prijímateľa Vašej rezervácie."><?php if
        (isset($_POST["poznamka"])) echo $_POST["poznamka"]; else echo ''; ?></textarea>
        </p>

	</fieldset>

	<fieldset>
		<legend>Údaje o rezervácii</legend>

		<p>
		<label for="restauracia">Reštaurácia:*</label>
		<select name="restauracia" id="restauracia" required>
			<option value="">-</option>
			<?php 
             if (isset($_POST['restauracia'])) restauracie_rezervacia($mysqli, $_POST['restauracia']);
             else restauracie_rezervacia($mysqli); ?>
		</select>
        </p>

		<p>	
			<label for="datum">Dátum:*</label>
			<input type="date" min="<?php echo date("Y-m-d"); ?>" name="datum" id="datum" value="<?php if(isset($_POST['datum'])) echo $_POST['datum']; else echo ''; ?>" required>
        </p>

	</fieldset>
    <p><em>Legenda: položky označené symbolom * sú povinné polia</em></p>
	<input type="submit" name="posli" value="Odošli rezerváciu">
</form>

<?php
}
?>

</section>

<?php
include('_partials/pata.php');
?>