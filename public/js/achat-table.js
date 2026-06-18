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


/* ─────────────────────────────────────────────────────────────────
   achat.js
   ─────────────────────────────────────────────────────────────────
   Gère :
   • changeMax  — limite la quantité selon le stock disponible
   • Toast      — notification légère en bas à droite
   • cloturerAchat — AJAX POST → /achat/cloturer
                     Vérifie PU × qté === total sur chaque ligne
                     Si KO  → affiche le message d'erreur serveur
                     Si OK  → vide localStorage + redirige saisirAchat
   ───────────────────────────────────────────────────────────────── */

/* ── 1. changeMax ────────────────────────────────────────────── */
function changeMax(ref, input) {
    const selectedOption = ref.options[ref.selectedIndex];
    const qtte = parseInt(selectedOption.getAttribute('qtte'), 10) || 0;

    input.max = qtte;
    if (input.value !== '' && parseInt(input.value, 10) > qtte) {
        input.value = qtte;
    }

    const form = ref.closest('form');
    if (form) {
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitBtn) submitBtn.disabled = (qtte === 0);
    }
}

document.getElementById('produit').addEventListener('change', function () {
    const input = document.getElementById('qtte');
    if (input) changeMax(this, input);
    input.value = '';
});

document.getElementById('saisir-confirmer').addEventListener('click', function (event) {
    event.preventDefault();
});


/* ── 2. Toast ────────────────────────────────────────────────── */
function showToast(message, type = 'error', duree = 5000) {
    const toast = document.getElementById('toast');
    toast.textContent  = message;
    toast.className    = `toast-${type}`;
    toast.style.display = 'block';
    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(() => { toast.style.display = 'none'; }, duree);
}


/* ── 3. Lecture du tableau ───────────────────────────────────── */
/**
 * Retourne un tableau d'objets { produit_id, libelle, pu, quantite, total }
 * en lisant chaque <tr> du tbody.
 * Colonnes supposées : 0-libellé  1-pu  2-quantite  3-total  4-produit_id (caché)
 */
function lireLignesTableau() {
    const tbody  = document.querySelector('#table-achat tbody');
    const lignes = [];

    tbody.querySelectorAll('tr').forEach(tr => {
        const cells = tr.cells;
        if (cells.length < 5) return;                    // ligne incomplète

        lignes.push({
            produit_id : cells[4].textContent.trim(),
            libelle    : cells[0].textContent.trim(),
            pu         : parseFloat(cells[1].textContent) || 0,
            quantite   : parseInt(cells[2].textContent, 10) || 0,
            total      : parseFloat(cells[3].textContent) || 0
        });
    });

    return lignes;
}

/**
 * Calcule la somme des totaux des lignes (arrondi 2 décimales).
 */
function calculerTotalGeneral(lignes) {
    return Math.round(lignes.reduce((acc, l) => acc + l.total, 0) * 100) / 100;
}


/* ── 4. Clôture AJAX ─────────────────────────────────────────── */
document.getElementById('cloturer-achat').addEventListener('click', async function () {


    const lignes = lireLignesTableau();

    if (lignes.length === 0) {
        showToast('Le panier est vide. Ajoutez au moins un produit.', 'error');
        return;
    }

    const totalGeneral = calculerTotalGeneral(lignes);

    // Désactive le bouton le temps de la requête
    this.disabled   = true;
    this.textContent = 'Traitement…';

    try {
        const response = await fetch(AJAX_CLOTURER_URL, {
            method  : 'POST',
            headers : {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ lignes, totalGeneral })
        });

        const data = await response.json();

        if (!data.success) {
            // ── Vérification KO ──────────────────────────────────
            showToast('❌ ' + data.message, 'error');
        } else {
            // ── Vérification OK + Insertion réussie ──────────────
            showToast('✅ Achat clôturé avec succès !', 'success', 2000);

            // Vide le localStorage
            localStorage.removeItem('lignesCommande');

            // Redirige vers la page de saisie après un bref délai
            setTimeout(() => {
                window.location.href = '/achats';
            }, 1200);
            resetTable(document.getElementById('table-achat')); // Vide le tableau immédiatement pour l'utilisateur
        }

    } catch (err) {
        showToast('❌ Erreur réseau ou serveur inattendu.', 'error');
        console.error('cloturerAchat error:', err);
    } finally {
        this.disabled   = false;
        this.textContent = 'Clôturer achat';
    }
});
