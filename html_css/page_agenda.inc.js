 // Sélectionner toutes les cellules de la colonne du lundi
 const lundiCells = document.querySelectorAll('th:nth-child(2) ~ td:nth-child(2)');

 // Définir la plage horaire de l'événement (en minutes depuis le début de la journée)
 const debut = 8 * 60 + 30; // 8h30
 const fin = 10 * 60; // 10h

 // Parcourir toutes les cellules sélectionnées
 for (const cell of lundiCells) {
   // Récupérer l'heure affichée dans la première cellule de la ligne
   const heure = parseInt(cell.parentNode.firstElementChild.textContent.slice(0, 2), 10);
   const minutes = parseInt(cell.parentNode.firstElementChild.textContent.slice(3, 5), 10);

   // Convertir l'heure en un nombre de minutes depuis le début de la journée
   const temps = heure * 60 + minutes;

   // Si l'heure correspond à la plage horaire de l'événement, ajouter le texte de l'événement dans la cellule
   if (temps >= debut && temps < fin) {
     cell.innerHTML = '<strong>Événement</strong>';
   }
 }