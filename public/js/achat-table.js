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

  // Libellé = texte de l'option
  const libelle = selectedOption.text;

  // Prix lu sur l'attribut "prix" de l'option (ex: prix="10")
  const prix = parseFloat(selectedOption.getAttribute('prix')) || 0;

  console.log(`Libellé: ${libelle}, Prix: ${prix}, Quantité: ${qtte.value}`);
  if(prix === 0) {
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
  // Calcul du total pour cette ligne
  const total = prix * quantite;

  // Création de la ligne et de ses cellules
  const ligne = document.createElement('tr');

  const tdLibelle = document.createElement('td');
  tdLibelle.textContent = libelle;

  const tdPrix = document.createElement('td');
  tdPrix.textContent = prix;

  const tdQtte = document.createElement('td');
  tdQtte.textContent = quantite;

  const tdTotal = document.createElement('td');
  tdTotal.textContent = total;

  ligne.appendChild(tdLibelle);
  ligne.appendChild(tdPrix);
  ligne.appendChild(tdQtte);
  ligne.appendChild(tdTotal);

  // Ajout dans le <tbody> s'il existe, sinon directement dans le tableau
  const corps = (tableau.tBodies && tableau.tBodies[0]) ? tableau.tBodies[0] : tableau;
  corps.appendChild(ligne);
}