<?php
session_start();

// Database configuratie
$host = "localhost";
$dbname = "karelveenstra";
$username = "root"; // Pas aan indien nodig
$password = ""; // Vul je databasewachtwoord in

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinding met database: " . $e->getMessage());
}

// Functie om klanten weer te geven
function klantenWeergeven($pdo) {
    $stmt = $pdo->query("SELECT * FROM klanten");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Formulierverwerking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $actie = $_POST['actie'];

    if ($actie == 'toevoegen') {
        $stmt = $pdo->prepare("INSERT INTO klanten (Voornaam, Achternaam, TelefoonNummer, Straat, Huisnummer, Postcode, Plaats, Email)
            VALUES (:voornaam, :achternaam, :telefoonnummer, :straat, :huisnummer, :postcode, :plaats, :email)");
        $stmt->execute([
            'voornaam' => $_POST['voornaam'],
            'achternaam' => $_POST['achternaam'],
            'telefoonnummer' => $_POST['telefoonnummer'],
            'straat' => $_POST['straat'],
            'huisnummer' => $_POST['huisnummer'],
            'postcode' => $_POST['postcode'],
            'plaats' => $_POST['plaats'],
            'email' => $_POST['email']
        ]);
    } elseif ($actie == 'verwijderen') {
        $stmt = $pdo->prepare("DELETE FROM klanten WHERE KlantID = :klantid");
        $stmt->execute(['klantid' => $_POST['klantid']]);
    } elseif ($actie == 'aanpassen') {
        $stmt = $pdo->prepare("UPDATE klanten SET Voornaam = :voornaam, Achternaam = :achternaam, TelefoonNummer = :telefoonnummer,
            Straat = :straat, Huisnummer = :huisnummer, Postcode = :postcode, Plaats = :plaats, Email = :email WHERE KlantID = :klantid");
        $stmt->execute([
            'voornaam' => $_POST['voornaam'],
            'achternaam' => $_POST['achternaam'],
            'telefoonnummer' => $_POST['telefoonnummer'],
            'straat' => $_POST['straat'],
            'huisnummer' => $_POST['huisnummer'],
            'postcode' => $_POST['postcode'],
            'plaats' => $_POST['plaats'],
            'email' => $_POST['email'],
            'klantid' => $_POST['klantid']
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klantenbeheer</title>
    <style>
#menu__toggle {
  opacity: 0;
}
#menu__toggle:checked + .menu__btn > span {
  transform: rotate(45deg);
}
#menu__toggle:checked + .menu__btn > span::before {
  top: 0;
  transform: rotate(0deg);
}
#menu__toggle:checked + .menu__btn > span::after {
  top: 0;
  transform: rotate(90deg);
}
#menu__toggle:checked ~ .menu__box {
  left: 0 !important;
}
.menu__btn {
  position: fixed;
  top: 20px;
  left: 20px;
  width: 26px;
  height: 26px;
  cursor: pointer;
  z-index: 1;
}
.menu__btn > span,
.menu__btn > span::before,
.menu__btn > span::after {
  display: block;
  position: absolute;
  width: 100%;
  height: 2px;
  background-color: #616161;
  transition-duration: .25s;
}
.menu__btn > span::before {
  content: '';
  top: -8px;
}
.menu__btn > span::after {
  content: '';
  top: 8px;
}
.menu__box {
  display: block;
  position: fixed;
  top: 0;
  left: -100%;
  width: 300px;
  height: 100%;
  margin: 0;
  padding: 80px 0;
  list-style: none;
  background-color: #ECEFF1;
  box-shadow: 2px 2px 6px rgba(0, 0, 0, .4);
  transition-duration: .25s;
}
.menu__item {
  display: block;
  padding: 12px 24px;
  color: #333;
  font-family: 'Roboto', sans-serif;
  font-size: 20px;
  font-weight: 600;
  text-decoration: none;
  transition-duration: .25s;
}
.menu__item:hover {
  background-color: #CFD8DC;
}
    </style>

<div class="hamburger-menu">
    <input id="menu__toggle" type="checkbox" />
    <label class="menu__btn" for="menu__toggle">
      <span></span>
    </label>

    <ul class="menu__box">
      <li><a class="menu__item" href="dashboard.php">Home</a></li>
			<li><a class="menu__item" href="Klantenbestand.php">Klantenbestand</a></li>
			<li><a class="menu__item" href="regrister.php">Regrister</a></li>
			<li><a class="menu__item" href="medewerker.php">Medewerkers</a></li>
    </ul>
  </div>
    <h1>Klantenbeheer</h1>
    <h2>Klantenlijst</h2>
    <ul>
        <?php foreach (klantenWeergeven($pdo) as $klant): ?>
            <li>
                <?= "ID: {$klant['KlantID']}, Naam: {$klant['Voornaam']} {$klant['Achternaam']}, 
                Telefoon: {$klant['TelefoonNummer']}, Adres: {$klant['Straat']} {$klant['Huisnummer']}, 
                {$klant['postcode']} {$klant['Plaats']}, Email: {$klant['Email']}" ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Klant toevoegen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="toevoegen">
        <label>Voornaam: <input type="text" name="voornaam" required></label><br>
        <label>Achternaam: <input type="text" name="achternaam" required></label><br>
        <label>Telefoonnummer: <input type="text" name="telefoonnummer" required></label><br>
        <label>Straat: <input type="text" name="straat" required></label><br>
        <label>Huisnummer: <input type="text" name="huisnummer" required></label><br>
        <label>Postcode: <input type="text" name="postcode" required></label><br>
        <label>Plaats: <input type="text" name="plaats" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <button type="submit">Toevoegen</button>
    </form>

    <h2>Klant verwijderen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="verwijderen">
        <label>KlantID: <input type="text" name="klantid" required></label><br>
        <button type="submit">Verwijderen</button>
    </form>

    <h2>Klant aanpassen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="aanpassen">
        <label>KlantID: <input type="text" name="klantid" required></label><br>
        <label>Voornaam: <input type="text" name="voornaam" required></label><br>
        <label>Achternaam: <input type="text" name="achternaam" required></label><br>
        <label>Telefoonnummer: <input type="text" name="telefoonnummer" required></label><br>
        <label>Straat: <input type="text" name="straat" required></label><br>
        <label>Huisnummer: <input type="text" name="huisnummer" required></label><br>
        <label>Postcode: <input type="text" name="postcode" required></label><br>
        <label>Plaats: <input type="text" name="plaats" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <button type="submit">Aanpassen</button>
    </form>
</body>
</html>
