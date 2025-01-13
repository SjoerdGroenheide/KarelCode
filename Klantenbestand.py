class Klant:
    def __init__(self, klant_id, naam, email):
        self.klant_id = klant_id
        self.naam = naam
        self.email = email

    def __str__(self):
        return f"ID: {self.klant_id}, Naam: {self.naam}, Email: {self.email}"


class KlantenSchema:
    def __init__(self):
        self.klanten = []

    def klant_toevoegen(self, klant_id, naam, email):
        nieuwe_klant = Klant(klant_id, naam, email)
        self.klanten.append(nieuwe_klant)
        print(f"Klant {naam} is toegevoegd.")

    def klant_verwijderen(self, klant_id):
        for klant in self.klanten:
            if klant.klant_id == klant_id:
                self.klanten.remove(klant)
                print(f"Klant met ID {klant_id} is verwijderd.")
                return
        print(f"Klant met ID {klant_id} niet gevonden.")

    def klant_aanpassen(self, klant_id, nieuwe_naam=None, nieuwe_email=None):
        for klant in self.klanten:
            if klant.klant_id == klant_id:
                if nieuwe_naam:
                    klant.naam = nieuwe_naam
                if nieuwe_email:
                    klant.email = nieuwe_email
                print(f"Klant met ID {klant_id} is aangepast.")
                return
        print(f"Klant met ID {klant_id} niet gevonden.")

    def klanten_weergeven(self):
        if not self.klanten:
            print("Geen klanten beschikbaar.")
        else:
            print("Lijst van klanten:")
            for klant in self.klanten:
                print(klant)


# Voorbeeldgebruik
if __name__ == "__main__":
    schema = KlantenSchema()

    while True:
        print("\n1. Klant toevoegen")
        print("2. Klant verwijderen")
        print("3. Klant aanpassen")
        print("4. Klanten weergeven")
        print("5. Afsluiten")

        keuze = input("Maak een keuze: ")

        if keuze == "1":
            klant_id = input("Voer klant ID in: ")
            naam = input("Voer naam in: ")
            email = input("Voer email in: ")
            schema.klant_toevoegen(klant_id, naam, email)
        elif keuze == "2":
            klant_id = input("Voer klant ID in om te verwijderen: ")
            schema.klant_verwijderen(klant_id)
        elif keuze == "3":
            klant_id = input("Voer klant ID in om aan te passen: ")
            nieuwe_naam = input("Voer nieuwe naam in (of laat leeg om niet te wijzigen): ")
            nieuwe_email = input("Voer nieuwe email in (of laat leeg om niet te wijzigen): ")
            schema.klant_aanpassen(klant_id, nieuwe_naam if nieuwe_naam else None, nieuwe_email if nieuwe_email else None)
        elif keuze == "4":
            schema.klanten_weergeven()
        elif keuze == "5":
            print("Programma wordt afgesloten.")
            break
        else:
            print("Ongeldige keuze, probeer opnieuw.")
