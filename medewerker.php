<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Voorbeeldlijst van medewerkers
$medewerkers = [
    ["naam" => "Jan Jansen", "functie" => "Manager", "email" => "jan.jansen@example.com", "telefoon" => "123-456-7890"],
    ["naam" => "Piet Pietersen", "functie" => "Developer", "email" => "piet.pietersen@example.com", "telefoon" => "987-654-3210"],
    ["naam" => "Anna de Vries", "functie" => "HR Specialist", "email" => "anna.devries@example.com", "telefoon" => "555-666-7777"]
];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .medewerker-lijst {
            list-style: none;
            padding: 0;
        }

        .medewerker-lijst li {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }

        .medewerker-lijst li:hover {
            background-color: #f0f0f0;
        }

        .medewerker-details {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .medewerker-details h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
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

    <main>
        <h1>Welkom bij de medewerkers, <?php echo htmlspecialchars($_SESSION['Gebruiker']); ?>!</h1>

        <h2>Medewerkers</h2>
        <ul class="medewerker-lijst" id="medewerkerLijst">
            <?php foreach ($medewerkers as $index => $medewerker): ?>
                <li onclick="toonDetails(<?php echo $index; ?>)">
                    <?php echo htmlspecialchars($medewerker['naam']); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="medewerker-details" id="medewerkerDetails" style="display: none;">
            <h2 id="detailsNaam"></h2>
            <p><strong>Functie:</strong> <span id="detailsFunctie"></span></p>
            <p><strong>Email:</strong> <span id="detailsEmail"></span></p>
            <p><strong>Telefoon:</strong> <span id="detailsTelefoon"></span></p>
        </div>
    </main>

    <script>
        const medewerkers = <?php echo json_encode($medewerkers); ?>;

        function toonDetails(index) {
            const medewerker = medewerkers[index];
            document.getElementById('detailsNaam').textContent = medewerker.naam;
            document.getElementById('detailsFunctie').textContent = medewerker.functie;
            document.getElementById('detailsEmail').textContent = medewerker.email;
            document.getElementById('detailsTelefoon').textContent = medewerker.telefoon;
            document.getElementById('medewerkerDetails').style.display = 'block';
        }

        function toggleMenu() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('active');
        }
    </script>
</body>
</html>
