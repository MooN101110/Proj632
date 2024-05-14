window.onload = function() {
    var evenements = [
        // Exemple d'événement le matin de 8h15 à 9h45 le lundi
        {
            debut: { heure: 8, minutes: 15 },
            fin: { heure: 9, minutes: 45 },
            jour: 0 // 0 pour lundi
        }
    ];

    evenements.forEach(function(evenement) {
        var debutHeure = evenement.debut.heure;
        var debutMinutes = evenement.debut.minutes;
        var finHeure = evenement.fin.heure;
        var finMinutes = evenement.fin.minutes;
        var jour = evenement.jour;

        for (var h = debutHeure; h < finHeure || (h === finHeure && debutMinutes < finMinutes); h++) {
            for (var m = (h === debutHeure ? Math.ceil(debutMinutes / 15) : 0); m < (h === finHeure ? Math.floor(finMinutes / 15) : 4); m++) {
                var cellId = 'cell_' + ('0' + h).slice(-2) + '_' + ('0' + (m * 15)).slice(-2) + '_' + ('0' + (jour + 1)).slice(-2) + '_' + ('0' + (new Date().getMonth() + 1)).slice(-2) + '_' + new Date().getFullYear();
                var cell = document.getElementById(cellId);
                if (cell) {
                    cell.textContent = 'Cours';
                }
            }
        }
    });
};



