<?php
session_start();

class Klant {
    public $klant_id;
    public $naam;
    public $email;

    public function __construct($klant_id, $naam, $email) {
        $this->klant_id = $klant_id;
        $this->naam = $naam;
        $this->email = $email;
    }

    public function __toString() {
        return "ID: {$this->klant_id}, Naam: {$this->naam}, Email: {$this->email}";
    }
}

class KlantenSchema {
    public $klanten = [];

    public function klantToevoegen($klant_id, $naam, $email) {
        $nieuweKlant = new Klant($klant_id, $naam, $email);
        $this->klanten[] = $nieuweKlant;
    }

    public function klantVerwijderen($klant_id) {
        foreach ($this->klanten as $index => $klant) {
            if ($klant->klant_id == $klant_id) {
                unset($this->klanten[$index]);
                return true;
            }
        }
        return false;
    }

    public function klantAanpassen($klant_id, $nieuwe_naam = null, $nieuwe_email = null) {
        foreach ($this->klanten as $klant) {
            if ($klant->klant_id == $klant_id) {
                if ($nieuwe_naam) {
                    $klant->naam = $nieuwe_naam;
                }
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
        $schema->klantToevoegen($_POST['klant_id'], $_POST['naam'], $_POST['email']);
    } elseif ($actie == 'verwijderen') {
        $schema->klantVerwijderen($_POST['klant_id']);
    } elseif ($actie == 'aanpassen') {
        $schema->klantAanpassen($_POST['klant_id'], $_POST['nieuwe_naam'], $_POST['nieuwe_email']);
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
        <label>Klant ID: <input type="text" name="klant_id" required></label><br>
        <label>Naam: <input type="text" name="naam" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <button type="submit">Toevoegen</button>
    </form>

    <h2>Klant verwijderen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="verwijderen">
        <label>Klant ID: <input type="text" name="klant_id" required></label><br>
        <button type="submit">Verwijderen</button>
    </form>

    <h2>Klant aanpassen</h2>
    <form method="post">
        <input type="hidden" name="actie" value="aanpassen">
        <label>Klant ID: <input type="text" name="klant_id" required></label><br>
        <label>Nieuwe Naam: <input type="text" name="nieuwe_naam"></label><br>
        <label>Nieuwe Email: <input type="email" name="nieuwe_email"></label><br>
        <button type="submit">Aanpassen</button>
    </form>
</body>
</html>
