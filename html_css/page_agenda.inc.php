
<link rel="stylesheet" href="css/page_agenda.inc.css"/>

<div id="agenda">
<?php
// Définir la date actuelle
$date = date('Y-m-d');

// Calculer le jour de la semaine de la date actuelle (0 = dimanche, 1 = lundi, etc.)
$jour_semaine = date('w', strtotime($date));

// Calculer le nombre de jours dans la semaine actuelle
$nb_jours = 7;

// Calculer la date du premier jour de la semaine (lundi)
$premier_jour = date('Y-m-d', strtotime($date . ' -' . $jour_semaine . ' days'));

// Définir le nombre de créneaux horaires par jour (24 heures * 4 créneaux de 15 minutes par heure)
$nb_creneaux = 24 * 4;

// Afficher le calendrier hebdomadaire avec les cases verticales pour chaque créneau de 15 minutes
echo '<table border="1">';
echo '<tr><th>Heure</th><th>Lundi</th><th>Mardi</th><th>Mercredi</th><th>Jeudi</th><th>Vendredi</th><th>Samedi</th><th>Dimanche</th></tr>';

// Boucle pour afficher les créneaux horaires de la journée
for ($i = 0; $i < $nb_creneaux; $i++) {
    // Calculer l'heure et les minutes pour le créneau actuel
    $heure = floor($i / 4);
    $minutes = ($i % 4) * 15;

    // Afficher l'heure et les minutes dans la première cellule de la ligne
    echo '<tr><td>' . str_pad($heure, 2, '0', STR_PAD_LEFT) . 'h' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . '</td>';

    // Boucle pour afficher les cases pour chaque jour de la semaine
    for ($j = 0; $j < $nb_jours; $j++) {
        $jour = date('d', strtotime($premier_jour . ' +' . $j . ' days'));
        $mois = date('m', strtotime($premier_jour . ' +' . $j . ' days'));
        $annee = date('Y', strtotime($premier_jour . ' +' . $j . ' days'));

        // Afficher une case vide pour chaque créneau horaire
        echo '<td id="cell_' . str_pad($heure, 2, '0', STR_PAD_LEFT) . '_' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . '_' . str_pad($jour, 2, '0', STR_PAD_LEFT) . '_' . $mois . '_' . $annee . '">&nbsp;</td>';

    }

    echo '</tr>';
}

echo '</table>';
?>

<script type="text/javascript">
    <?php include 'page_agenda.inc.js'; ?>
</script>
</div>