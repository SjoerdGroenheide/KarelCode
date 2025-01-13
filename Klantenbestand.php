<?php

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
    private $klanten = [];

    public function klantToevoegen($klant_id, $naam, $email) {
        $nieuweKlant = new Klant($klant_id, $naam, $email);
        $this->klanten[] = $nieuweKlant;
        echo "Klant {$naam} is toegevoegd.\n";
    }

    public function klantVerwijderen($klant_id) {
        foreach ($this->klanten as $index => $klant) {
            if ($klant->klant_id == $klant_id) {
                unset($this->klanten[$index]);
                echo "Klant met ID {$klant_id} is verwijderd.\n";
                return;
            }
        }
        echo "Klant met ID {$klant_id} niet gevonden.\n";
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
                echo "Klant met ID {$klant_id} is aangepast.\n";
                return;
            }
        }
        echo "Klant met ID {$klant_id} niet gevonden.\n";
    }

    public function klantenWeergeven() {
        if (empty($this->klanten)) {
            echo "Geen klanten beschikbaar.\n";
        } else {
            echo "Lijst van klanten:\n";
            foreach ($this->klanten as $klant) {
                echo $klant . "\n";
            }
        }
    }
}

// Voorbeeldgebruik
$schema = new KlantenSchema();

while (true) {
    echo "\n1. Klant toevoegen\n";
    echo "2. Klant verwijderen\n";
    echo "3. Klant aanpassen\n";
    echo "4. Klanten weergeven\n";
    echo "5. Afsluiten\n";

    $keuze = readline("Maak een keuze: ");

    switch ($keuze) {
        case "1":
            $klant_id = readline("Voer klant ID in: ");
            $naam = readline("Voer naam in: ");
            $email = readline("Voer email in: ");
            $schema->klantToevoegen($klant_id, $naam, $email);
            break;
        case "2":
            $klant_id = readline("Voer klant ID in om te verwijderen: ");
            $schema->klantVerwijderen($klant_id);
            break;
        case "3":
            $klant_id = readline("Voer klant ID in om aan te passen: ");
            $nieuwe_naam = readline("Voer nieuwe naam in (of laat leeg om niet te wijzigen): ");
            $nieuwe_email = readline("Voer nieuwe email in (of laat leeg om niet te wijzigen): ");
            $schema->klantAanpassen($klant_id, $nieuwe_naam ?: null, $nieuwe_email ?: null);
            break;
        case "4":
            $schema->klantenWeergeven();
            break;
        case "5":
            echo "Programma wordt afgesloten.\n";
            exit;
        default:
            echo "Ongeldige keuze, probeer opnieuw.\n";
    }
}
