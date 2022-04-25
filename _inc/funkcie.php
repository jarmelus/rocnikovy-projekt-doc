<?php
date_default_timezone_set('Europe/Bratislava');
include('db.php');

// VYPISUJE CISLA OD MIN PO MAX
function vypis_select($min, $max, $oznac = -1) {
	for($i = $min; $i <= $max; $i++) {
		echo "<option value='$i'";
		if ($i == $oznac) echo ' selected';
		echo ">$i</option>\n";
	}
}
	
function hlavicka($nadpis) {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $nadpis; ?></title>
<link href="assets/styly.css" rel="stylesheet">
</head>

<body>

<header>
<h1><?php echo $nadpis; ?></h1>
</header>
<?php
}

/* kontroluje meno (meno a priezvisko)
vráti TRUE, ak celé meno ($m) obsahuje práve 1 medzeru, pred a za medzerou sú časti aspoň dĺžky 3 znaky
*/
function spravne_meno($m) {
  $medzera = strpos($m, ' ');
  if (!$medzera) return false;       
  $priezvisko = substr($m, $medzera + 1);  
  return ($medzera > 2 && (strpos($priezvisko, ' ') === FALSE) && strlen($priezvisko) > 2);
}

// KONTROLUJE, CI JE DOSTATOK VOLNYCH MIEST
function dostupnost_miesta($mysqli, $pocet_osob, $restauracia){
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM gastroapp_restauracie WHERE rid='$restauracia'";  // definuj dopyt
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			$row = $result->fetch_assoc();

			if($row['pocet_volnych_miest'] < $pocet_osob) return false;
			else return true;
		} 
 	} 
}

// ZISKAVA DATA REZERVACIE NA ZAKLADE ID = REZID
function rezervacia_data($mysqli, $rezid){
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM gastroapp_rezervacie WHERE rezid = '$rezid'";
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
			return $result->fetch_assoc();
		}else{
			return false;
		} 
	} else {
		return false;
	}
}

// KONTROLUJE SPRAVNOST MAILU, CI MA @ A NAJVYSSIA DOMENA MA MAX 4 ZNAKY
function spravny_email($e){
	$zavinac = strpos($e, '@');
	$bodka = strpos(substr($e, $zavinac + 1), '.');
	if (!$zavinac || !$bodka) return false;
	$bodka2 = substr(substr($e, $zavinac + 1), $bodka + 1);
	return (strlen($bodka2) <= 4);
}

// KONTROLUJE SPRAVNOST ZADANEHO TEL. CISLA, CI ZACINA + A OKREM TOHO MA PRESNE 12 CISLIC
function spravne_cislo($c){
	$plusko = strpos($c, '+');
	if ($plusko != 0) return false;
	$cislo = substr($c, 1);
	return ((strlen($cislo) == 12) && (is_numeric($cislo)));
}

//OSETRUJE VSTUPY
function osetri($co) {
	return trim(strip_tags($co));
}

// ZISTI, CI DANA RESTAURACIA EXISTUJE
function over_kod_restauracie($mysqli, $rid) {
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM gastroapp_restauracie WHERE rid='$rid'";  // definuj dopyt
//		echo "sql = $sql <br>";
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {  // vykonaj dopyt
			// dopyt sa podarilo vykonať
			return true;
		} else {
			// dopyt sa NEpodarilo vykonať, resp. používateľ neexistuje!
			return false;
		}
 	} else {
		// NEpodarilo sa spojiť s databázovým serverom!
		return false;
	}
}

//VYPISUJE PRIHLASOVACIE UDAJE (MENO, HESLO), KTORE SU ZHODNE, TEDA V TOMTO PRIPADE VYPISE IBA DVAKRAT LOGIN, KTORY JE AJ HESLOM
function vypis_prihlasovacie_data($mysqli) {
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM gastroapp_pouzivatelia"; // definuj dopyt
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>' . $row['login'] . '</td>';
				echo '<td>' . $row['login'] . '</td>';
				if ($row['admin'] == 1) echo '<td>áno</td>';
				else echo '<td>nie</td>';
				echo '</tr>';
			}
		} else {
			// dopyt sa NEpodarilo vykonať!
			echo '<p class="chyba">NEpodarilo sa získať údaje z databázy!</p>' . $mysqli->error ;
		}
	}
}

// vrati udaje pouzivatela ako asociativne pole, ak existuje pouzivatel $username s heslom $pass, inak vrati FALSE
function over_pouzivatela($mysqli, $username, $pass) {
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM gastroapp_pouzivatelia WHERE login = '$username' AND heslo = MD5('$pass')";  // definuj dopyt

		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {  // vykonaj dopyt
			$row = $result->fetch_assoc();
			$result->free();
			return $row;
		} else {
			// dopyt sa NEpodarilo vykonať, resp. používateľ neexistuje!
			return false;
		}
	} else {
		// NEpodarilo sa spojiť s databázovým serverom!
		return false;
	}
}

//SLUZI NA VYPISANIE RESTAURACII V RAMCI VEREJNEJ DATABAZY
function vypis_restauracie($mysqli){ 
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM gastroapp_restauracie "; 
		if (isset($_POST['nazov2'])) $sql .= 'ORDER BY nazov DESC'; 
		elseif (isset($_POST['cena1'])) $sql .= 'ORDER BY pocet_volnych_miest ASC'; 
		elseif (isset($_POST['cena2'])) $sql .= 'ORDER BY pocet_volnych_miest DESC';
		else $sql .= 'ORDER BY nazov ASC'; // definuj dopyt
	
		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			while ($row = $result->fetch_assoc()) {
				echo '<h2><a href=info.php?kod=' . $row['rid'] . '>' . $row['nazov'];
				echo ' (' . $row['pocet_volnych_miest'] . ' voľných miest)' . '</a></h2>';
			}
			$result->free();
		} elseif ($mysqli->errno) {
			echo '<p class="chyba">NEpodarilo sa vykonať dopyt! (' . $mysqli->error . ')</p>';
		}
	}
}

// SLUZI NA  VYPISANIE RESTAURACII V REZERVACNOM FORMULARI
function restauracie_rezervacia($mysqli, $oznac = -1){
	if (!$mysqli->connect_errno) {
		$sql = "SELECT * FROM gastroapp_restauracie"; 

		if ($result = $mysqli->query($sql)) {
			while ($row = $result->fetch_assoc()) {
				echo '<option value="' . $row['rid'] . '"'; 
				if ($row['rid'] == $oznac || $row['rid'] == $_GET['kod']) echo ' selected';
				echo '>' . $row['nazov'] . ' (počet voľných miest: ' . $row['pocet_volnych_miest'] . ')' . '</option>';
			}
			$result->free();
		} elseif ($mysqli->errno) {
			echo '<p class="chyba">NEpodarilo sa vykonať dopyt! (' . $mysqli->error . ')</p>';
		}
}
}

// ZISKAVA DATA Z DANEJ RESTAURACIE NA ZAKLADE JEJ ID (RID)
function restauracia_data($mysqli, $rid){
	$sql = "SELECT * FROM gastroapp_restauracie WHERE rid = '$rid'";
	if (!$mysqli->connect_errno) {
		if ($result = $mysqli->query($sql)) {
			return $result->fetch_assoc();
		}
	}
	return false;
}

function vypis_odkazy($mysqli){
	$sql = "SELECT * FROM gastroapp_odkazy, gastroapp_restauracie, gastroapp_rezervacie WHERE stav_odkazu = 'accepted' AND stav_rezervacie = 'done' AND gastroapp_odkazy.rezid = gastroapp_rezervacie.rezid AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid ORDER BY gastroapp_odkazy.datum DESC";
	
	if (!$mysqli->connect_errno) {
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
			$poradie = 0;
			echo "<h2>Odkazy zákazníkov</h2>";
			echo '<table>';
			echo '<tr>';
			echo '<th>Číslo odkazu</th><th>Meno a priezvisko zákazníka</th><th>Názov reštaurácie</th><th>Odkazy</th><th>Dátum a čas vytvorenia</th>';
			echo '</tr>';
			echo '<ol>';
			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<td>';
				echo ++$poradie . '.';
				echo '</td>';
				echo '<td>' . $row['meno_priezvisko_odkazujuceho'] . '</td>';
				echo '<td>' . $row['nazov'] . '</td>';
				echo '<td>' . $row['odkaz'] . '</td>';
				echo '<td>' . $row['datum'] . '</td>';
				echo '</tr>';	
			}
			echo '</ol>'; 
			echo '</table>';
			$result->free();
		}
	}else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function pridaj_odkaz($mysqli, $meno_priezvisko_odkazujuceho, $rezid, $odkaz) {
	if (!$mysqli->connect_errno) {

		$sql = "INSERT INTO gastroapp_odkazy SET meno_priezvisko_odkazujuceho='$meno_priezvisko_odkazujuceho', rezid='$rezid', odkaz='$odkaz', datum=NOW(), stav_odkazu='pending'"; // definuj dopyt

		if ($result = $mysqli->query($sql)) {  // vykonaj dopyt
			echo '<p class="ok">Odkaz bol odoslaný na schválenie!</p>';
		} elseif ($mysqli->errno) {
			echo '<p class="chyba">Nastala chyba pri odosielaní. (' . $mysqli->error . ')</p>';
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}


// 
// ADMINISTRACIA ODKAZOV A OBJEDNAVOK
//
function schval_vypis_odkazy($mysqli){
	$sql = "SELECT * FROM gastroapp_odkazy, gastroapp_restauracie, gastroapp_rezervacie WHERE stav_odkazu = 'pending' AND stav_rezervacie = 'done' AND gastroapp_odkazy.rezid = gastroapp_rezervacie.rezid AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid ORDER BY gastroapp_odkazy.datum DESC";
	
	if (!$mysqli->connect_errno) {
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
				echo '<h2 id="odkazy_cakajuce"><a href="#hore" style="text-decoration: none; color: black">Odkazy na schválenie</a></h2>';
				echo '<form method="post">';
				echo '<table>';
				echo '<tr>';
				echo '<th>schváľ / zamietni</th><th>Meno a priezvisko zákazníka</th><th>Reštaurácia</th><th>Odkaz</th><th>Dátum a čas vytvorenia</th>';
				echo '</tr>';

			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo "<td><input type='checkbox' name='gastro[]' value=" . $row['oid'] . "></td>";
				echo "<td>" . $row['meno_priezvisko_odkazujuceho'] . "</td>";
				echo "<td>" . $row['nazov'] . "</td>";
				echo "<td>" . $row['odkaz'] . "</td>";
				echo "<td>" . $row['datum'] . "</td>";
				echo '</tr>';
			} 

			echo '<tr>';
			echo '<td><label for="akcia_a">schváľ</label><input type="radio" name="akcia" value="a" id="akcia_a"> | <input type="radio" name="akcia" value="n" id="akcia_n"><label for="akcia_n">zamietni</label> 
			<input type="submit" name="schval_zamietni" value="Potvrď"></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';

			$result->free();
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function schval_odkaz($mysqli){
	if (!$mysqli->connect_errno && isset($_POST['gastro'])) {
		foreach($_POST['gastro'] as $id => $oid) {
			$sql = "UPDATE gastroapp_odkazy SET stav_odkazu='accepted' WHERE oid='$oid'";
			if ($result = $mysqli->query($sql)){
				echo '<p class="ok"><strong>Komentáre boli schválené!</strong></p>';
			} 
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function odstran_odkaz($mysqli){
	if (!$mysqli->connect_errno && isset($_POST['gastro'])) {
		foreach($_POST['gastro'] as $id => $oid) {
			$sql = "DELETE FROM gastroapp_odkazy WHERE oid='$oid'";
			if ($result = $mysqli->query($sql)){
				echo '<p class="smutne_ok"><strong>Komentáre boli odstránené!</strong></p>'. "\n";
			} 
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function administracia_vypis_rezervacie($mysqli){
	$sql = "SELECT * FROM gastroapp_restauracie, gastroapp_rezervacie WHERE stav_rezervacie != 'done' AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid ORDER BY gastroapp_rezervacie.datum_vytvorenia DESC";
	
	if (!$mysqli->connect_errno) {
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
			echo '<h2 id="rezervacie_neuzatvorene"><a href="#hore" style="text-decoration: none; color: black">Administrácia rezervácií</a></h2>';
			echo '<form method="post">';
			echo '<table>';
			echo '<tr>';
			echo '<th>schváľ / zamietni / uzatvor</th><th>Meno a priezvisko zákazníka</th><th>Deti</th><th>Počet osôb</th><th>Email</th><th>Telefón</th><th>Poznámka</th><th>Reštaurácia</th><th>Dátum rezervácie</th><th>Dátum a čas vytvorenia</th><th>Stav rezervácie</th>';
			echo '</tr>';

			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo "<td><input type='checkbox' name='rezervacie[]' value=" . $row['rezid'] . "></td>";
				echo "<td>" . $row['meno_priezvisko'] . "</td>";
				if($row['deti'] == 1) echo "<td>áno</td>"; else echo "<td>nie</td>";
				echo "<td>" . $row['pocet_osob'] . "</td>";
				echo "<td>" . $row['email'] . "</td>";
				echo "<td>" . $row['telefon'] . "</td>";
				echo "<td>" . $row['poznamka'] . "</td>";
				echo "<td>" . $row['nazov'] . "</td>";
				echo "<td>" . $row['datum_rezervacie'] . "</td>";
				echo "<td>" . $row['datum_vytvorenia'] . "</td>";
				echo "<td>" . $row['stav_rezervacie'] . "</td>";
				echo '</tr>';
			} 

		echo '<tr>';
		echo '<td><input type="radio" name="akcia_rez" value="prijat" id="akcia_prijat"><label for="akcia_prijat">schváľ</label> | <input type="radio" name="akcia_rez" value="odmietnut" id="akcia_odmietnut"><label for="akcia_odmietnut">zamietni</label> | <input type="radio" name="akcia_rez" value="uzatvorit" id="akcia_uzatvorit"><label for="akcia_uzatvorit">uzatvor</label> 
		<input type="submit" name="prijat_odmietnut_uzatvorit" value="Potvrď"></td>';
		echo '</tr>';
		echo '</table>';
		echo '</form>';

		$result->free();
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function schval_rezervaciu($mysqli){
	if (!$mysqli->connect_errno && isset($_POST['rezervacie'])) {
		foreach($_POST['rezervacie'] as $id => $rezid) {

			// DATA Z REZERVACIE A RESTAURACIE O POCTE VOLNYCH MIEST + POCTE OSOB NA REZERVACIU!
			$sql_rest = "SELECT * FROM gastroapp_restauracie, gastroapp_rezervacie WHERE gastroapp_restauracie.rid = gastroapp_rezervacie.rid AND gastroapp_rezervacie.rezid = '$rezid'";
			$result_rest = $mysqli->query($sql_rest);
			$konk = $result_rest->fetch_assoc();

			// IBA AK JE DOST VOLNYCH MIEST
			if ($konk['pocet_volnych_miest'] >= $konk['pocet_osob']){
				
				// ODPOCITA POCET MIEST, AK ESTE NIE JE ACCEPTED
				$sql2 = "UPDATE gastroapp_restauracie, gastroapp_rezervacie SET gastroapp_restauracie.pocet_volnych_miest = gastroapp_restauracie.pocet_volnych_miest - gastroapp_rezervacie.pocet_osob WHERE gastroapp_rezervacie.rezid = '$rezid' AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid AND gastroapp_rezervacie.stav_rezervacie != 'accepted'";
				if ($result2 = $mysqli->query($sql2)){
					
				} 

				$sql3 = "UPDATE gastroapp_rezervacie SET stav_rezervacie='accepted' WHERE rezid='$rezid' AND stav_rezervacie != 'accepted'";
				if ($result = $mysqli->query($sql3)){
					echo '<p class="ok"><strong>Rezervácie boli schválené!</strong></p>';
				}

			} else{
				echo '<p class="chyba"><strong>Nedostatočná kapacita pre danú rezerváciu!</strong></p>';
			}
			 
			
		}

	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function zamietni_rezervaciu($mysqli){
	if (!$mysqli->connect_errno && isset($_POST['rezervacie'])) {
		foreach($_POST['rezervacie'] as $id => $rezid) {
			// PRIPOCITA POCET MIEST, AK PREDTYM ACCEPTED
			$sql2 = "UPDATE gastroapp_restauracie, gastroapp_rezervacie SET gastroapp_restauracie.pocet_volnych_miest = gastroapp_restauracie.pocet_volnych_miest + gastroapp_rezervacie.pocet_osob WHERE gastroapp_rezervacie.rezid = '$rezid' AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid AND gastroapp_rezervacie.stav_rezervacie = 'accepted'";
			if ($result2 = $mysqli->query($sql2)){
			
			} else{
				echo '<p class="chyba">Nastala chyba pri odosielaní. (' . $mysqli->error . ')</p>';
			}

			$sql = "UPDATE gastroapp_rezervacie SET stav_rezervacie='declined' WHERE rezid='$rezid'";
			if ($result = $mysqli->query($sql)){
				echo '<p class="smutne_ok"><strong>Rezervácie boli zamietnuté!</strong></p>'. "\n";
			} else{
				echo '<p class="chyba">Nastala chyba pri odosielaní. (' . $mysqli->error . ')</p>';
			}
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function uzatvor_rezervaciu($mysqli){
	if (!$mysqli->connect_errno && isset($_POST['rezervacie'])) {
		foreach($_POST['rezervacie'] as $id => $rezid) {
			// PRIPOCITA POCET MIEST, AK BOLI ACCEPTED
			$sql2 = "UPDATE gastroapp_restauracie, gastroapp_rezervacie SET gastroapp_restauracie.pocet_volnych_miest = gastroapp_restauracie.pocet_volnych_miest + gastroapp_rezervacie.pocet_osob WHERE gastroapp_rezervacie.rezid = '$rezid' AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid AND gastroapp_rezervacie.stav_rezervacie = 'accepted'";
			if ($result2 = $mysqli->query($sql2)){
				
			} else{
				echo '<p class="chyba">Nastala chyba pri odosielaní. (' . $mysqli->error . ')</p>';
			}

			// UZATVORI REZERVACIU
			$sql = "UPDATE gastroapp_rezervacie SET stav_rezervacie='done' WHERE rezid='$rezid'";
			if ($result = $mysqli->query($sql)){
				echo '<p class="smutne_ok"><strong>Rezervácie boli uzatvorené!</strong></p>'. "\n";
			} else{
				echo '<p class="chyba">Nastala chyba pri odosielaní. (' . $mysqli->error . ')</p>';
			}			
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

// 
// PREZERANIE ODKAZOV A OBJEDNAVOK
//
function vypis_odkazy_uzivatel($mysqli){
	$sql = "SELECT * FROM gastroapp_odkazy, gastroapp_restauracie, gastroapp_rezervacie WHERE stav_odkazu = 'pending' AND stav_rezervacie = 'done' AND gastroapp_odkazy.rezid = gastroapp_rezervacie.rezid AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid ORDER BY gastroapp_odkazy.datum DESC";
	
	if (!$mysqli->connect_errno) {
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
				echo '<h2 id="odkazy_cakajuce"><a href="#hore" style="text-decoration: none; color: black">Odkazy na schválenie</a></h2>';
				echo '<table>';
				echo '<tr>';
				echo '<th>Meno a priezvisko zákazníka</th><th>Reštaurácia</th><th>Odkaz</th><th>Dátum a čas vytvorenia</th>';
				echo '</tr>';

			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo "<td>" . $row['meno_priezvisko_odkazujuceho'] . "</td>";
				echo "<td>" . $row['nazov'] . "</td>";
				echo "<td>" . $row['odkaz'] . "</td>";
				echo "<td>" . $row['datum'] . "</td>";
				echo '</tr>';
			} 

			echo '</table>';

			$result->free();
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}

function vypis_rezervacie_uzivatel($mysqli){
	$sql = "SELECT * FROM gastroapp_restauracie, gastroapp_rezervacie WHERE stav_rezervacie != 'done' AND gastroapp_rezervacie.rid = gastroapp_restauracie.rid ORDER BY gastroapp_rezervacie.datum_vytvorenia DESC";
	
	if (!$mysqli->connect_errno) {
		if (($result = $mysqli->query($sql)) && ($result->num_rows > 0)) {
				echo '<h2 id="rezervacie_neuzatvorene"><a href="#hore" style="text-decoration: none; color: black">Neuzatvorené rezervácie</a></h2>';
				echo '<table>';
				echo '<tr>';
				echo '<th>Meno a priezvisko zákazníka</th><th>Deti</th><th>Počet osôb</th><th>Email</th><th>Telefón</th><th>Poznámka</th><th>Reštaurácia</th><th>Dátum rezervácie</th><th>Dátum a čas vytvorenia</th><th>Stav rezervácie</th>';
				echo '</tr>';

			while ($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo "<td>" . $row['meno_priezvisko'] . "</td>";
				if($row['deti'] == 1) echo "<td>áno</td>"; else echo "<td>nie</td>";
				echo "<td>" . $row['pocet_osob'] . "</td>";
				echo "<td>" . $row['email'] . "</td>";
				echo "<td>" . $row['telefon'] . "</td>";
				echo "<td>" . $row['poznamka'] . "</td>";
				echo "<td>" . $row['nazov'] . "</td>";
				echo "<td>" . $row['datum_rezervacie'] . "</td>";
				echo "<td>" . $row['datum_vytvorenia'] . "</td>";
				echo "<td>" . $row['stav_rezervacie'] . "</td>";
				echo '</tr>';
			} 

			echo '</table>';

			$result->free();
		}
	} else {
		// NEpodarilo sa vykonať dopyt!
	echo '<p class="chyba">Nastala chyba pri získavaní údajov z DB.</p>' . "\n";
		}
}
?>
