<?php
session_start();

class Klant {
    public $naam;
    public $email;
    public $adres = [];
    public $telefoonnummers = [];

    public function __construct($naam, $email) {
        $this->naam = $naam;
        $this->email = $email;
    }

    public function voegAdresToe($straat, $huisnummer, $postcode, $plaats) {
        $this->adres = [
            'straat' => $straat,
            'huisnummer' => $huisnummer,
            'postcode' => $postcode,
            'plaats' => $plaats
        ];
    }

    public function verwijderAdres() {
        $this->adres = [];
    }

    public function voegTelefoonnummerToe($nummer) {
        $this->telefoonnummers[] = $nummer;
    }

    public function verwijderTelefoonnummer($nummer) {
        $index = array_search($nummer, $this->telefoonnummers);
        if ($index !== false) {
            unset($this->telefoonnummers[$index]);
            $this->telefoonnummers = array_values($this->telefoonnummers);
        }
    }

    public function __toString() {
        $adresString = empty($this->adres) ? "Geen adres beschikbaar" : implode(", ", $this->adres);
        $telefoonString = empty($this->telefoonnummers) ? "Geen telefoonnummers beschikbaar" : implode(", ", $this->telefoonnummers);

        return "Naam: {$this->naam}, Email: {$this->email}, Adres: {$adresString}, Telefoonnummers: {$telefoonString}";
    }
}

class KlantenSchema {
    public $klanten = [];

    public function klantToevoegen($naam, $email) {
        $nieuweKlant = new Klant($naam, $email);
        $this->klanten[] = $nieuweKlant;
    }

    public function klantVerwijderen($naam) {
        foreach ($this->klanten as $index => $klant) {
            if ($klant->naam == $naam) {
                unset($this->klanten[$index]);
                $this->klanten = array_values($this->klanten);
                return true;
            }
        }
        return false;
    }

    public function klantAanpassen($naam, $nieuwe_email = null) {
        foreach ($this->klanten as $klant) {
            if ($klant->naam == $naam) {
                if ($nieuwe_email) {
                    $klant->email = $nieuwe_email;
                }
                return true;
            }
        }
        return false;
    }

    public function klantenWeergeven() {
        return $this->klanten;
    }
}

if (!isset($_SESSION['schema'])) {
    $_SESSION['schema'] = serialize(new KlantenSchema());
}

$schema = unserialize($_SESSION['schema']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $actie = $_POST['actie'];

    if ($actie == 'toevoegen') {
        $schema->klantToevoegen($_POST['naam'], $_POST['email']);
    } elseif ($actie == 'verwijderen') {
        $schema->klantVerwijderen($_POST['naam']);
    } elseif ($actie == 'aanpassen') {
        $schema->klantAanpassen($_POST['naam'], $_POST['nieuwe_email']);
    } elseif ($actie == 'adres_toevoegen') {
        foreach ($schema->klantenWeergeven() as $klant) {
            if ($klant->naam == $_POST['naam']) {
                $klant->voegAdresToe($_POST['straat'], $_POST['huisnummer'], $_POST['postcode'], $_POST['plaats']);
            }
        }
    } elseif ($actie == 'adres_verwijderen') {
        foreach ($schema->klantenWeergeven() as $klant) {
            if ($klant->naam == $_POST['naam']) {
                $klant->verwijderAdres();
            }
        }
    } elseif ($actie == 'telefoon_toevoegen') {
        foreach ($schema->klantenWeergeven() as $klant) {
            if ($klant->naam == $_POST['naam']) {
                $klant->voegTelefoonnummerToe($_POST['telefoon']);
            }
        }
    } elseif ($actie == 'telefoon_verwijderen') {
        foreach ($schema->klantenWeergeven() as $klant) {
            if ($klant->naam == $_POST['naam']) {
                $klant->verwijderTelefoonnummer($_POST['telefoon']);
            }
        }
    }

    $_SESSION['schema'] = serialize($schema);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klantenbeheer</title>
</head>
<body>
    <h1>Klantenbeheer</h1>
    <h2>Klantenlijst</h2>
    <ul>
        <?php foreach ($schema->klantenWeergeven() as $klant): ?>
            <li><?= $klant ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Klant toevoegen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="toevoegen">
        <label>Naam: <input type="text" name="naam" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <button type="submit">Toevoegen</button>
    </form>

    <h2>Klant verwijderen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="verwijderen">
        <label>Naam: <input type="text" name="naam" required></label><br>
        <button type="submit">Verwijderen</button>
    </form>

    <h2>Klant aanpassen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="aanpassen">
        <label>Naam: <input type="text" name="naam" required></label><br>
        <label>Nieuwe Email: <input type="email" name="nieuwe_email"></label><br>
        <button type="submit">Aanpassen</button>
    </form>

    <h2>Adres toevoegen of aanpassen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="adres_toevoegen">
        <label>Naam: <input type="text" name="naam" required></label><br>
        <label>Straat: <input type="text" name="straat" required></label><br>
        <label>Huisnummer: <input type="text" name="huisnummer" required></label><br>
        <label>Postcode: <input type="text" name="postcode" required></label><br>
        <label>Plaats: <input type="text" name="plaats" required></label><br>
        <button type="submit">Opslaan</button>
    </form>

    <h2>Adres verwijderen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="adres_verwijderen">
        <label>Naam: <input type="text" name="naam" required></label><br>
        <button type="submit">Verwijderen</button>
    </form>

    <h2>Telefoonnummer toevoegen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="telefoon_toevoegen">
        <label>Naam: <input type="text" name="naam" required></label><br>
        <label>Telefoonnummer: <input type="text" name="telefoon" required></label><br>
        <button type="submit">Toevoegen</button>
    </form>

    <h2>Telefoonnummer verwijderen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="telefoon_verwijderen">
        <label>Naam: <input type="text" name="naam" required></
