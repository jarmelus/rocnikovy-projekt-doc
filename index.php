<?php
include('_inc/db.php');
include('_inc/udaje.php');
include('_inc/funkcie.php');
hlavicka('Dáta pre prihlásenie & popis funkcionalít aplikácie');
?>

<section>
<h1>Tabuľka prihlasovacích údajov</h1>
<p>V prípade záujmu o znovupozretie, nájdete link na túto stránku v pätičke. Ak chcete vstúpiť do aplikácie, kliknite <a href="o_nas.php">tu</a>.</p>
<table class="center">
    <caption>Údaje</caption>
    <thead>
        <tr>
            <th>Prihlasovacie meno</th>
            <th>Heslo</th>
            <th>Admin</th>
        </tr>
    </thead>
    <tbody>
        <?php
        vypis_prihlasovacie_data($mysqli);
        ?>
    </tbody>
</table>
<h2>Popis funkcionalít</h2>
<p>V tejto aplikácii si používateľ môže prezerať reštaurácie s detailnejšími popismi v sekcii "Zoznam reštaurácií". Každá reštaurácia má obmedzenú kapacitu a pridelený počet voľných miest, ktorý sa znižuje na základe prijatých rezervácií o zadaný počet osôb. Každá rezervácia musí byť skontrolovaná administrátorom a ak bude odsúhlasená (môže byť aj zamietnutá), o daný počet sa zníži zobrazovaný počet voľných miest. Ak administrátor označí danú objednávku za vybavenú, o daný počet ľudí sa navýši počet voľných miest reštaurácie. Každý zákazník, ktorého rezervácia bola vybavená, môže zanechať odkaz na stránke v časti "Zanechajte odkaz". Všetky odkazy musia byť taktiež skontrolované a schválené administrátorom (môžu byť aj zamietnuté, kedy sa vymažú z databázy). Okrem administrátorov máme aj bežných používateľov, ktorí si môžu prezerať rezervácie a komentáre (iba tie komentáre, ktoré čakajú na schválenie), ale nemôžu meniť ich stav.</p>
<p><em>Zdroj dát o reštauráciach</em> nájdete <a href="https://slovakregion.sk/turizmus?mesto=All&tid_1=99">tu</a>. Všetky adresy boli <em>pozmenené</em> na úplne náhodné pomocou <a href="https://www.fakeaddressgenerator.com/">generátora náhodných adries</a>.</p>
</section>

<?php
include('_partials/pata.php');
?>