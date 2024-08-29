Free Download Source Code "Payroll Management System"

FIRST Download

1.XAMPP

2."TEXT EDITOR" NOTEPAD++ OR SUBLIME TEXT 3 / ETC.

3"Payroll_Management_System"

4. Download the zip file/ download winrar

5. Extract the file and copy "Payroll_Management_System" folder

6.Paste inside root directory/ where you install xammp local disk C: drive D: drive E: paste: (for xampp/htdocs, 

7. Open PHPMyAdmin (http://localhost/phpmyadmin)

8. Create a database with name payroll

6. Import payroll.sql file(given inside the zip package in SQL file folder)

7.Run the script http://localhost/Payroll_Management_System


**LOGIN DETAILS** 

Create your own User 

Admin
user: admin
pass: admin123

****** https://www.campcodes.com/ ******
Subcribe my Youtube Channel **** SerBermz ****


<?php

// Liste des employés avec leur salaire brut
$employes = [
    ['nom' => 'Dupont', 'salaire_brut' => 3000],
    ['nom' => 'Martin', 'salaire_brut' => 2500],
    ['nom' => 'Durand', 'salaire_brut' => 3500]
];

// Liste des cotisations en pourcentage
$cotisations = [
    'Sécurité sociale' => 7.3,
    'Retraite' => 6.9,
    'Chômage' => 2.4
];

// Fonction pour calculer le salaire net
function calculerSalaireNet($salaire_brut, $cotisations) {
    $total_cotisations = 0;
    foreach ($cotisations as $taux) {
        $total_cotisations += ($salaire_brut * $taux / 100);
    }
    return $salaire_brut - $total_cotisations;
}

// Calcul et affichage du salaire net pour chaque employé
foreach ($employes as $employe) {
    $salaire_net = calculerSalaireNet($employe['salaire_brut'], $cotisations);
    echo "Employé : " . $employe['nom'] . "\n";
    echo "Salaire brut : " . $employe['salaire_brut'] . " €\n";
    echo "Salaire net : " . number_format($salaire_net, 2) . " €\n\n";
}

?>