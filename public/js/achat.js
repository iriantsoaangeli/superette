/**
 * changeMax(ref, input)
 * ----------------------
 * - ref   : l'élément <select> (la sélection)
 * - input : l'élément <input> dont on veut limiter le max
 *
 * Comportement :
 * 1. Récupère l'option sélectionnée dans "ref"
 * 2. Lit son attribut "qtte" (ex: qtte="2")
 * 3. Met à jour input.max avec cette valeur
 * 4. Remonte jusqu'au <form> parent
 * 5. Trouve le bouton submit du formulaire
 * 6. Bloque (disabled) ce bouton si qtte = 0, sinon le débloque
 */
function changeMax(ref, input) {
    // Option actuellement sélectionnée
    const selectedOption = ref.options[ref.selectedIndex];

    // Lecture de l'attribut "qtte" (string "2" -> nombre 2)
    const qtte = parseInt(selectedOption.getAttribute('qtte'), 10) || 0;

    // Mise à jour du max de l'input
    input.max = qtte;

    // Si la valeur actuelle dépasse le nouveau max, on la corrige
    if (input.value !== '' && parseInt(input.value, 10) > qtte) {
        input.value = qtte;
    }

    // Remontée jusqu'au formulaire parent
    const form = ref.closest('form');

    if (form) {
        // Recherche du bouton submit dans ce formulaire
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');

        if (submitBtn) {
            // Bloque le bouton si qtte = 0, sinon le débloque
            submitBtn.disabled = (qtte === 0);
        }
    }
}

/**
 * Attache le listener "change" sur chaque select concerné.
 * ⚠️ Adapter les sélecteurs ci-dessous (classes/ids) à votre HTML réel :
 *    - ".js-qtte-select" pour le <select> (ref)
 *    - ".js-qtte-input"  pour l'<input> à limiter
 */
document.getElementById('produit').addEventListener('change', function () {
    const input = document.getElementById("qtte");
    if (input) {
        changeMax(this, input);
    }
    input.value = ''; // Réinitialise la quantité à chaque changement de produit
});


document.getElementById("saisir-confirmer").addEventListener("click", function () {
    event.preventDefault(); 
});