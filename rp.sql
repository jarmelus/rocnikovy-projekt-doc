-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Št 10.Jún 2021, 15:48
-- Verzia serveru: 5.7.24
-- Verzia PHP: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `rp`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gastroapp_odkazy`
--

CREATE TABLE `gastroapp_odkazy` (
  `oid` int(11) NOT NULL,
  `meno_priezvisko_odkazujuceho` varchar(100) COLLATE utf8_slovak_ci NOT NULL,
  `rezid` int(11) NOT NULL,
  `odkaz` varchar(1000) COLLATE utf8_slovak_ci NOT NULL,
  `datum` datetime NOT NULL,
  `stav_odkazu` varchar(100) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `gastroapp_odkazy`
--

INSERT INTO `gastroapp_odkazy` (`oid`, `meno_priezvisko_odkazujuceho`, `rezid`, `odkaz`, `datum`, `stav_odkazu`) VALUES
(44, 'Julius Juliovsky', 18, 'Skvelý personál! 10/10', '2021-06-10 16:37:59', 'accepted'),
(46, 'Michael Rezervacny', 17, 'Jedinečný zážitok!', '2021-06-10 17:43:31', 'pending');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gastroapp_pouzivatelia`
--

CREATE TABLE `gastroapp_pouzivatelia` (
  `uid` smallint(6) NOT NULL,
  `login` varchar(20) COLLATE utf8_slovak_ci NOT NULL,
  `heslo` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `meno` varchar(20) COLLATE utf8_slovak_ci NOT NULL,
  `priezvisko` varchar(30) COLLATE utf8_slovak_ci NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `gastroapp_pouzivatelia`
--

INSERT INTO `gastroapp_pouzivatelia` (`uid`, `login`, `heslo`, `meno`, `priezvisko`, `admin`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrátor', 'Systému', 1),
(2, 'jaro', 'df5b7cbf526831f88b39134244e6332a', 'Jaroslav', 'Aron', 1),
(5, 'silvia', 'e5cb7c411f1d9a67f68deff4a954cfbc', 'Silvia', 'Skladová', 0),
(6, 'victor', 'ffc150a160d37e92012c196b6af4160d', 'Victor', 'Administratívny', 1),
(7, 'uwa', '78f0f32c08873cfdba57f17c855943c0', 'Predmet', 'UWA', 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gastroapp_restauracie`
--

CREATE TABLE `gastroapp_restauracie` (
  `rid` int(11) NOT NULL,
  `nazov` varchar(100) COLLATE utf8_slovak_ci NOT NULL,
  `popis` text COLLATE utf8_slovak_ci NOT NULL,
  `adresa` text COLLATE utf8_slovak_ci NOT NULL,
  `pocet_volnych_miest` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `gastroapp_restauracie`
--

INSERT INTO `gastroapp_restauracie` (`rid`, `nazov`, `popis`, `adresa`, `pocet_volnych_miest`) VALUES
(1, 'Chutná Varecha', 'Sme rodinným podnikom zameraným na tradičnú, slovenskú kuchyňu. Neváhajte sa zastaviť a pochutnať si na kvalitných pečených buchtách domácej výroby, či haluškách ako od starej mamy! ', 'Tuhárske nám. 3, 984 01, Lučenec', 42),
(2, 'Black Peacock', 'Jednoduchosť, kvalita, jedinečnosť. Príďte a spoznajte nezabudnuteľné gastronomické zážitky, ktoré Vám exkluzívne ponúkame v rámci našej medzinárodnej siete reštaurácií.', 'Čelakovského 1388/2, 811 03, Staré Mesto, Bratislava', 25),
(3, 'Salaš Fujara', 'Hľadáte príjemné, pokojné prostredie a zároveň tradičnú slovenskú kuchyňu? Ponúkame Vám posedenie v drevenici s nádychom pokoja a vôni dreva. Naše tradičné slovenské jedlá vo Vás zanechajú príjemný zážitok.', 'Novoveská cesta 929/31, 05 401, Levoča', 37),
(4, 'Al Cavallo', 'Priestor s klenbovými stropmi je jedinečným doplnkom a napriek tomu, že podnik je priestranný, členenie na niekoľko miestností poskytuje hosťom dostatok súkromia. Jedálny lístok je orientovaný hlavne na taliansku kuchyňu. ', 'Veľkoúľanská cesta 1332, 925 21, Sládkovičovo', 56),
(5, 'Chillout restaurant', 'Bohatý jedálny lístok s početnými kreáciami a kvalita uspokojí každého. kto má rád ľahké nápadité jedlá za rozumné ceny. Nachádza sa v tichom prostredí a má kvalitnú kuchyňu.', 'Ľ. Stárka 2202, 911 05, Trenčín', 48),
(6, 'Furman', 'Na jedálnom lístku reštaurácie nájdete výdatné desiatové polievky a rýdze slovenské jedlá ako napr. halušky.\r\nV ponuke bývajú aj sezónne jedlá. K pohode rodičov prispieva bezpečné ihrisko pre deti.\r\n\r\n', 'SNP 878/10, 920 01, Hlohovec', 62),
(7, 'Hotel Thermia Palace - Grand restaurant', 'Atmosféru príjemného večera dotvárajú tóny živej hudby. Tunajší šéfkuchár sa činí, jedlá sú chutné, na tanieri pekne upravené. Zameranie je na grécku kuchyňu.', 'Nám. baníkov 17, 048 01, Rožňava', 48),
(8, 'Hotel Tilia - reštaurácia', 'Jedálny lístok je dostatočne pestrý na to, aby aby uspokojil najmä konzervatívnejšie založených hostí. Ponúka širokú škálu jedál tradičnej slovenskej kuchyne. V letných mesiacoch sa hojne používa letná terasa.', 'Hraničná 1695/16, 821 05 Ružinov, Bratislava', 55),
(9, 'Hotel U Leva', 'Čistý a útulný interiér charakterizujú farebné stoly, steny, obrazy. Úctivý a ochotný personál sa aktívne zaujíma o hosťa hneď po jeho vstupe a následne počas celého stolovania. Zameranie na východoázijskú kuchyňu.', 'Volgogradská 4935/21, 036 01 Martin', 72),
(10, 'La Casa Al Paco', 'Interiér, rovnako ako názov, evokuje atmosféru slnečného Španielska (zameranie reštaurácie)- teplé tóny v klenbových priestoroch, zaujímavé osvetlenie, motívy španielskeho juhu. Okrem dobrej kuchyne zaujme aj obsluha.', 'Zátureckého 9948/5, 831 07 Bratislava - Vajnory', 43),
(11, 'Liviano', 'Interiér je tu veľmi prešpekulovaný, pôsobí inteligentne a inovatívne. Zásluhu má na tom aj plátno, na ktorom sa premieta aktuálny dej v kuchyni. Šéfkuchár pôsobil v minulosti u mediálneho hrdinu gastronómie Jamieho Olivera. Raritou v našich podmienkach je príprava a tlač. Zameranie je na európsku kuchyňu.', 'Dolné Rudiny 4, 010 01, Žilina', 26),
(12, 'Med malina', 'V reštaurácii varí poľský kuchár, ktorý kuchyňu obohacuje o rôzne chutné jedlá ako napr. barszcz, pirohy, bigos. Obsluha je veľmi spoľahlivá. Veľmi príjemným oživením sú poľské dezerty.', 'Továrenská 765/7, 919 04 Smolenice', 73),
(13, 'Molo', 'Nachádza sa vedľa nákupného centra Pohon. Je tu krásny výhľad, ktorý si priam musíte spestriť dobrým jedlom. Čoraz viac sa venuje rybám a morským špecialitkám.', 'Poľovnícka 2353/29, 974 01, Banská Bystrica - Šalková', 67),
(14, 'Mottolino', 'O tejto reštaurácii je známe, že tu majú najlepšiu pizzu v Rimavskej Sobote. Okrem nej tu ponúkajú aj steaky, rizoto a rôzne iné mäsité jedlá.', 'Košická cesta 1566, 979 01, Rimavská Sobota', 33),
(15, 'Patriot', 'Reštaurácia na pešej zóne v centre mesta sa darí udržiavať výbornú úroveň, o čom svedčí jej početná stála klientela. Hostia sem chodia pre príjemnú atmosféru, vkusný interiér v pivničných priestoroch, ale najmä pre kuchyňu. Jedálný lístok prináša atraktívne receptúry, menej tradičné kombinácie chutí a ingrediéncií. Obsluha je pozorná, rýchla a všímavá. Zameranie je na francúzsku kuchyňu.', 'Francisciho 2388, 058 01, Poprad', 33),
(16, 'Olivo', 'Podnik sa nachádza priamo v centre mesta.Jedálny lístok ponúka okrem pizze aj domáce cestoviny, jedlá z morských rýb a rôzne iné špeciality.Gurmánov poteší aj pečivo vlastnej výroby.', 'Textilná 1836/8, 040 12, Košice', 59),
(17, 'Kerling', 'Jedálny lístok ponúka kulinársku kreativitu z celého sveta a uspokojí požiadavky aj najnáročnejších gurmánov. Výnimočnosť jedál dopĺňa ponuka vín z rôznych kútov sveta.', 'Západ 1057, 028 01, Trstená', 38),
(18, 'Osaka sushibar', 'Perfektné sushi. Minimalisticky zariadená reštaurácia, v ktorej každú nedeľu ponúkajú deťom na obed jedlo zadarmo.', 'Námestie sv. Anny 360/10, 911 01, Trenčín', 29),
(19, 'San Marten', 'Reštaurácia ponúka príjemné posedenie s krásnym výhľadom nielen z letnej terasy, ale aj zo zaujímavého klenbového interiéru. Ponuka jedál je inšpirovaná talianskou kuchyňou. Dostatočne členený interiér ponúka hosťom príjemné súkromie.', 'Pod Táborom 2788/3, 080 01, Prešov', 26),
(20, 'Sole Mio - pizza & grill', 'Podnik sídli vedľa tzv. Mamuta, tradičnej pivárne a reštaurácie, vybudovanej v starej sladovni. Ľudia sem chodia na chrumkavú pizzu, ktorú kuchári pripravujú priamo pred zrakmi hostí, zaujimavé sú aj talianské údeniny. Reštaurácia je členom Asociácie majiteľov talianských reštaurácií na Slovensku.', 'Lúčna 16/14, 971 01, Prievidza', 22),
(21, 'U Richtára', 'Podnik v srdci v Batizoviec upúta aj náhodného turistu. V jedálnom lístku nájdete jedlá z hlavných druhov mäsa, divinu a slovenské špeciality. Výborne zostavená vinná karta ponúka vína prevažne od známych a menej známych vinárov.', 'Nálepkova 192, 059 35, Batizovce', 18),
(22, 'The Irish Times Pub', 'Typickú írsku krčmu s ešte typickejšou patinou by ste v blízkosti centra Trenčianskych Teplíc nemali prehliadnuť. Jedálny lístok sa skladá zo samých írskych špecialít.', 'T. G. Masaryka 565/3, 914 51, Trenčianske Teplice', 17),
(23, 'Vináreň u Ludvika', 'Reštaurácia sa nachádza hneď pri kostole. Deti sa môžu vyšantiť na ihrisku s udržiavaným trávnikom. Domácu atmosféru dotvárajú vidiecke dekorácie a regionálna kuchyňa. Majiteľom je, ako inak, vinár.', 'Továrenská 2374/3, 901 01, Malacky', 23),
(24, 'Taverna', 'Taverna ponúka ilúziu posedenia kdesi na gréckom vidieku alebo na niektorom z početných ostrovov. Úzko-grécky je pochopiteľne zameraná aj kuchyňa, a tak výber z jedálného lístka sa začína i končí takmer výhradne gréckými špecialitami. Pomerne rozsiahle obedové menu je v ponuke, okrem každého pracovného dňa, aj v sobotu.', 'Rybničná 9959/40, 831 07 Bratislava - Vajnory', 34),
(25, 'Kogo - caffé ristorante', 'Pohostinné počas celého dňa. Večer si tu môžete posedieť na dvoch terasách. Kogo robí dobré meno stredomorskej kuchyni.', 'Čerín 64, 974 01, Kysuce', 0),
(26, 'Milton', 'Nová, moderná, čierno-biela reštaurácia použila zaujímavé dekorácie, a to fotografie modeliek Petra Nagya. Hlási sa k zážitkovej gastronómii, viaceré mäsá naozaj zniesli najvyššie kritérium, nuž zeleninové prílohy už natoľko nezaujmú.', 'Adama Trajana 4648/2, 921 01, Piešťany', 26),
(27, 'Koliba Goral', 'Jedna z najznámejších kolíb. Je tu salónik pre uzavreté spoločnosti - Slovenská izba - a servíruje sa aj na poschodí s výhľadom na celý podnik. Obsluha je úctivá a ochotná. Samozrejme, zameranie je na slovenskú tradičnú kuchyňu.', 'M. R. Štefánika 1805/2, 075 01, Trebišov', 23);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `gastroapp_rezervacie`
--

CREATE TABLE `gastroapp_rezervacie` (
  `rezid` int(11) NOT NULL,
  `meno_priezvisko` varchar(40) COLLATE utf8_slovak_ci NOT NULL,
  `deti` varchar(11) COLLATE utf8_slovak_ci NOT NULL,
  `pocet_osob` int(11) NOT NULL,
  `email` varchar(70) COLLATE utf8_slovak_ci NOT NULL,
  `telefon` varchar(15) COLLATE utf8_slovak_ci NOT NULL,
  `poznamka` text COLLATE utf8_slovak_ci NOT NULL,
  `rid` tinyint(50) NOT NULL,
  `datum_rezervacie` date NOT NULL,
  `datum_vytvorenia` datetime NOT NULL,
  `stav_rezervacie` varchar(10) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `gastroapp_rezervacie`
--

INSERT INTO `gastroapp_rezervacie` (`rezid`, `meno_priezvisko`, `deti`, `pocet_osob`, `email`, `telefon`, `poznamka`, `rid`, `datum_rezervacie`, `datum_vytvorenia`, `stav_rezervacie`) VALUES
(1, 'Jaroslav Aron', '1', 6, 'jaroslav.aron@gmail.com', '+421999999999', '', 15, '2021-06-09', '2021-06-09 11:14:12', 'pending'),
(2, 'Victor Didius', '0', 5, 'victor.did@email.com', '+420147963258', 'alergický na mlieko', 18, '2021-06-11', '2021-06-09 11:32:06', 'done'),
(12, 'Federico Juan', '1', 14, 'fred.ju@lat.br', '+055635856776', 'gluten allergy', 11, '2021-06-13', '2021-06-09 11:39:27', 'pending'),
(13, 'Juan Pedro', '1', 7, 'juanos.pedro@yahoo.com', '+420587495874', '3 sillas para niños', 10, '2021-06-19', '2021-06-09 11:51:10', 'done'),
(14, 'iujzhtg kjhg', '1', 5, 'jhjn@sdc.dcsx', '+421999999999', '', 14, '2021-06-09', '2021-06-09 16:05:52', 'pending'),
(15, 'Max Brightwood', '0', 5, 'max.br@uklive.com', '+987654321654', '', 15, '2021-06-17', '2021-06-09 17:43:24', 'pending'),
(16, 'Jin Xipin', '1', 3, 'jinxi@kri.ch', '+963852741963', '', 11, '2021-06-26', '2021-06-09 17:47:19', 'pending'),
(17, 'Michael Rezervacny', '1', 6, 'mich.rez@fict.com', '+421123456789', '', 4, '2021-06-11', '2021-06-10 14:07:14', 'done'),
(18, 'Julius Juliovsky', '1', 11, 'julo.julo@julo.sk', '+963789789879', '', 25, '2021-06-10', '2021-06-10 16:36:24', 'done'),
(19, 'Markus Marekis', '1', 11, 'marekis.markus@marekis.com', '+963852741963', '', 11, '2021-06-11', '2021-06-10 16:39:27', 'done'),
(20, 'Danius Danion', '1', 11, 'dan.dan@dan.dan', '+789789789789', '', 25, '2021-06-11', '2021-06-10 16:40:52', 'accepted'),
(21, 'Marekus Marekis', '1', 12, 'mar.mar@mar.com', '+421999999999', '', 25, '2021-06-11', '2021-06-10 16:41:52', 'declined');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `gastroapp_odkazy`
--
ALTER TABLE `gastroapp_odkazy`
  ADD PRIMARY KEY (`oid`);

--
-- Indexy pre tabuľku `gastroapp_pouzivatelia`
--
ALTER TABLE `gastroapp_pouzivatelia`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Indexy pre tabuľku `gastroapp_restauracie`
--
ALTER TABLE `gastroapp_restauracie`
  ADD PRIMARY KEY (`rid`);

--
-- Indexy pre tabuľku `gastroapp_rezervacie`
--
ALTER TABLE `gastroapp_rezervacie`
  ADD PRIMARY KEY (`rezid`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `gastroapp_odkazy`
--
ALTER TABLE `gastroapp_odkazy`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pre tabuľku `gastroapp_pouzivatelia`
--
ALTER TABLE `gastroapp_pouzivatelia`
  MODIFY `uid` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pre tabuľku `gastroapp_restauracie`
--
ALTER TABLE `gastroapp_restauracie`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pre tabuľku `gastroapp_rezervacie`
--
ALTER TABLE `gastroapp_rezervacie`
  MODIFY `rezid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
