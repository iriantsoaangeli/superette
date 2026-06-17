/**
 * ajouterLigne(tableau, input, qtte)
 * -----------------------------------
 * - tableau : l'élément <table> dans lequel ajouter la ligne
 * - input   : le <select> dont les options ont un attribut "prix"
 * - qtte    : le champ <input type="number"> contenant la quantité
 *
 * Comportement :
 * 1. Récupère le texte de l'option sélectionnée dans "input"
 * 2. Récupère son attribut "prix"
 * 3. Récupère la quantité depuis "qtte"
 * 4. Calcule le total (qtte * prix)
 * 5. Crée une nouvelle ligne <tr> avec 4 colonnes : libellé, prix, qtte, total
 * 6. Ajoute cette ligne dans le tableau
 */
function ajouterLigne(tableau, input, qtte) {
    // Option actuellement sélectionnée dans le select
    const selectedOption = input.options[input.selectedIndex];

    const selectedQtte = parseInt(selectedOption.getAttribute('selected'), 10) || 0;
    // Libellé = texte de l'option
    const libelle = selectedOption.text;

    // Prix lu sur l'attribut "prix" de l'option (ex: prix="10")
    const prix = parseFloat(selectedOption.getAttribute('prix')) || 0;

    console.log(`Libellé: ${libelle}, Prix: ${prix}, Quantité: ${qtte.value}`);
    if (prix === 0) {
        alert("Veuillez sélectionner un produit valide.");
        return;
    }
    // Quantité saisie dans le champ qtte
    const quantite = parseInt(qtte.value, 10) || 0;

    console.log(`Quantité saisie: ${quantite}`);
    if (quantite <= 0) {
        alert("Veuillez saisir une quantité valide.");
        return;
    }

    if (quantite + selectedQtte > parseInt(selectedOption.getAttribute('qtte'), 10)) {
        alert("La quantité saisie dépasse le stock disponible.");
        return;
    }
    // Calcul du total pour cette ligne
    const total = prix * quantite;

    // Création de la ligne et de ses cellules
    const ligne = document.createElement('tr');

    const idProduit = selectedOption.value; // Stocke l'id du produit dans un attribut personnalisé de la ligne


    const tdLibelle = document.createElement('td');
    tdLibelle.textContent = libelle;

    const tdPrix = document.createElement('td');
    tdPrix.textContent = prix;

    const tdQtte = document.createElement('td');
    tdQtte.textContent = quantite;

    const tdTotal = document.createElement('td');
    tdTotal.textContent = total;

    const tdId = document.createElement('td');
    tdId.textContent = idProduit;
    tdId.style.display = 'none'; // Masque la colonne de l'id

    ligne.appendChild(tdLibelle);
    ligne.appendChild(tdPrix);
    ligne.appendChild(tdQtte);
    ligne.appendChild(tdTotal);
    ligne.appendChild(tdId);

    selectedOption.setAttribute('selected', selectedQtte + quantite); // Met à jour l'attribut "selected" de l'option sélectionnée   
    // Ajout dans le <tbody> s'il existe, sinon directement dans le tableau
    const corps = (tableau.tBodies && tableau.tBodies[0]) ? tableau.tBodies[0] : tableau;
    corps.appendChild(ligne);
    refactoTable(tableau); // Consolidation des lignes avec le même id
    sauvegarderTableau(tableau);
}

document.addEventListener('DOMContentLoaded', () => {
    const tableau = document.getElementById('table-achat');
    restaurerTableau(tableau);
    document.getElementById('resetBtn').addEventListener('click', function () {
        event.preventDefault();
    });
});

// Sauvegarde après ajout d'une ligne
function sauvegarderTableau(tableau) {
    const lignes = [...tableau.querySelectorAll('tbody tr')].map(tr =>
        [...tr.children].map(td => td.textContent)
    );
    localStorage.setItem('lignesCommande', JSON.stringify(lignes));
}

// Restauration au chargement de la page
function restaurerTableau(tableau) {
    const data = JSON.parse(localStorage.getItem('lignesCommande') || '[]');
    const corps = tableau.tBodies[0];
    data.forEach(cellules => {
        const tr = document.createElement('tr');
        tr.innerHTML = cellules.map(c => `<td>${c}</td>`).join('');
        corps.appendChild(tr);
    });
}


function resetTable(tableau) {
    localStorage.removeItem('lignesCommande');
    const corps = tableau.tBodies[0];
    while (corps.firstChild) {
        corps.removeChild(corps.firstChild);
    }
}


function refactoTable(tableau) {
  const tbody = tableau.tBodies[0] || tableau;
  const lignes = Array.from(tbody.rows);

  // Map id -> { libelle, pu, qtte, id }
  const groupes = new Map();

  lignes.forEach(ligne => {
    const cellules = ligne.cells;

    const libelle = cellules[0].textContent.trim();
    const pu = parseFloat(cellules[1].textContent) || 0;
    const qtte = parseInt(cellules[2].textContent, 10) || 0;
    const id = cellules[cellules.length - 1].textContent.trim();

    if (groupes.has(id)) {
      // Id déjà rencontré : on additionne la qtte
      groupes.get(id).qtte += qtte;
    } else {
      // Nouvel id : on initialise le groupe
      groupes.set(id, { libelle, pu, qtte, id });
    }
  });

  // Vide le tableau pour le reconstruire
  tbody.innerHTML = '';

  const lignesConsolidees = [];

  groupes.forEach(groupe => {
    const total = groupe.pu * groupe.qtte;
    lignesConsolidees.push({ ...groupe, total });

    const ligne = document.createElement('tr');
    ligne.innerHTML = `
      <td>${groupe.libelle}</td>
      <td>${groupe.pu}</td>
      <td>${groupe.qtte}</td>
      <td>${total}</td>
      <td>${groupe.id}</td>
    `;
    tbody.appendChild(ligne);
  });

  return lignesConsolidees;
}