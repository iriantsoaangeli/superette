/* =================================================================
   achat.js — Superette
   =================================================================
   • changeMax    : limite qté selon stock
   • updateTotal  : recalcule le total affiché en bas
   • showToast    : notification bas-droite
   • cloturerAchat: AJAX POST /achat/cloturer
================================================================= */

/* ── 1. changeMax ─────────────────────────────────────────── */
function changeMax(ref, input) {
    const opt  = ref.options[ref.selectedIndex];
    const qtte = parseInt(opt.getAttribute('qtte'), 10) || 0;

    input.max = qtte;
    if (input.value !== '' && parseInt(input.value, 10) > qtte) input.value = qtte;

    const form = ref.closest('form') || document.getElementById('saisir-achat');
    const submitBtn = document.getElementById('saisir-confirmer');
    if (submitBtn) submitBtn.disabled = (qtte === 0);
}

document.getElementById('produit').addEventListener('change', function () {
    const input = document.getElementById('qtte');
    if (input) changeMax(this, input);
    input.value = '';
});

document.getElementById('saisir-confirmer').addEventListener('click', function (e) {
    e.preventDefault();
    ajouterLigne(
        document.getElementById('table-achat'),
        document.getElementById('produit'),
        document.getElementById('qtte')
    );
});


/* ── 2. Total affiché ──────────────────────────────────────── */
function updateTotal() {
    const tbody = document.querySelector('#table-achat tbody');
    let sum = 0;
    tbody.querySelectorAll('tr').forEach(tr => {
        const cells = tr.cells;
        if (cells.length >= 4) sum += parseFloat(cells[3].textContent) || 0;
    });
    const el = document.getElementById('total-general');
    if (el) el.textContent = sum.toFixed(2);
}

// Observe les mutations du tbody pour mettre à jour le total
const observer = new MutationObserver(updateTotal);
observer.observe(document.getElementById('table-body'), { childList: true, subtree: true, characterData: true });


/* ── 3. Toast ──────────────────────────────────────────────── */
function showToast(message, type = 'error', duree = 5000) {
    const toast = document.getElementById('toast');
    toast.textContent   = message;
    toast.className     = `toast-${type}`;
    toast.style.display = 'block';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => { toast.style.display = 'none'; }, duree);
}


/* ── 4. Lecture du tableau ─────────────────────────────────── */
function lireLignesTableau() {
    const tbody  = document.querySelector('#table-achat tbody');
    const lignes = [];
    tbody.querySelectorAll('tr').forEach(tr => {
        const cells = tr.cells;
        if (cells.length < 5) return;
        lignes.push({
            produit_id: cells[4].textContent.trim(),
            libelle:    cells[0].textContent.trim(),
            pu:         parseFloat(cells[1].textContent) || 0,
            quantite:   parseInt(cells[2].textContent, 10) || 0,
            total:      parseFloat(cells[3].textContent) || 0,
        });
    });
    return lignes;
}

function calculerTotalGeneral(lignes) {
    return Math.round(lignes.reduce((acc, l) => acc + l.total, 0) * 100) / 100;
}


/* ── 5. Clôture AJAX ───────────────────────────────────────── */
document.getElementById('cloturer-achat').addEventListener('click', async function () {
    const lignes = lireLignesTableau();

    if (lignes.length === 0) {
        showToast('⚠️ Le panier est vide. Ajoutez au moins un produit.', 'error');
        return;
    }

    const totalGeneral = calculerTotalGeneral(lignes);

    this.disabled    = true;
    this.textContent = '⏳ Traitement…';

    try {
        const response = await fetch(AJAX_CLOTURER_URL, {
            method:  'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ lignes, totalGeneral }),
        });

        const data = await response.json();

        if (!data.success) {
            showToast('❌ ' + data.message, 'error', 7000);
        } else {
            showToast('✅ Achat clôturé avec succès !', 'success', 2000);
            localStorage.removeItem('lignesCommande');
            // Vide le tableau visuellement
            document.getElementById('table-body').innerHTML = '';
            // Remet les attributs "selected" à 0 sur toutes les options
            document.querySelectorAll('#produit option').forEach(o => o.setAttribute('selected', '0'));
            updateTotal();
            setTimeout(() => { window.location.href = '/achats'; }, 1200);
        }
    } catch (err) {
        showToast('❌ Erreur réseau ou serveur inattendu.', 'error');
        console.error('cloturerAchat:', err);
    } finally {
        this.disabled    = false;
        this.textContent = '💳 Clôturer l\'achat';
    }
});
