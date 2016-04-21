<!DOCTYPE html>
<html>

	<head>
		<title>14S_CzibulaPeter_XML_Adatbazis</title>
		<meta charset="utf-8" />
	</head>

	<body>

<?php
	
	if (!file_exists("cards.xml")) {

		// XML létrehozása
		$xml = new DOMDocument('1.0', 'utf-8');
		$cards = $xml->createElement('cards');
		$xml->appendChild($cards);

		$db = fopen("cards.xml", "w") or die("Unable to open file!");
		fwrite($db, $xml->saveXML());
		fclose($db);

		echo "<h2>Fájl létrehozva</h2>";

	}

	// XML betöltése
	$db = simplexml_load_file("cards.xml");

	// Új kártya felvétele
	if (isset($_GET['f_name']) && isset($_GET['l_name']) && isset($_GET['mobil']) && isset($_GET['phone']) && isset($_GET['home'])){

		echo "<h2>Adatok sikeresen rögzitve!</h2>";

		$card = $db->addChild('card');
		$card->addAttribute('time', time());
		$card->addChild('f_name', $_GET['f_name']);
		$card->addChild('l_name', $_GET['l_name']);
		$card->addChild('mobil', $_GET['mobil']);
		$card->addChild('phone', $_GET['phone']);
		$card->addChild('home', $_GET['home']);

		$newdb = fopen("cards.xml", "w") or die("Unable to open file!");
		fwrite($newdb, $db->asXML());
		fclose($newdb);

	// Kártya törlése
	} elseif (isset($_GET['torol'])) {

		$i = 0;
		foreach ($db->xpath("card") as $card) {
			if ($card->attributes()->time == $_GET['torol']){
				unset($db->card[$i]);
				echo "<h2>Sikeres törlés</h2>";
				break;
			}
			$i++;
		}
		
		$newdb = fopen("cards.xml", "w") or die("Unable to open file!");
		fwrite($newdb, $db->asXML());
		fclose($newdb);

	} else {
		echo '<h2>Adatbázis cuccos</h2>';
	}
	
	// Kártyák kiolvasása xmlből
	$cards = $db->xpath("card");

?>

		<table style="width:100%">

			<tr>
				<td>Vezetéknév</td>
				<td>Keresztnév</td> 
				<td>Telefon</td>
				<td>Otthoni</td>
				<td>Lakcím</td>
				<td></td>
			</tr>

			<!-- Elemek kilistázása -->
			<?php

				foreach ($cards as $card) { 
					?>

					<tr>
						<td><?php echo $card->f_name; ?> </td>
						<td><?php echo $card->l_name; ?> </td>
						<td><?php echo $card->mobil; ?> </td>
						<td><?php echo $card->phone; ?> </td>
						<td><?php echo $card->home; ?> </td>
						<td><?php echo "<a href=\"?torol=" . $card->attributes()->time . "\">Törlés</a>"; ?> </td>
					</tr>

				<?php }
			?>

			<!-- Elem felvétele -->
			<tr>
				<form action="index.php" method="get">
					<td><input type="text" name="f_name"/></td>
					<td><input type="text" name="l_name"/></td>
					<td><input type="text" name="mobil"/></td>
					<td><input type="text" name="phone"/></td>
					<td><input type="text" name="home"/></td>
					<td><input type="submit" value="Feltölt"/></td>
				</form>
			</tr>

		</table>
	</body>

</html>