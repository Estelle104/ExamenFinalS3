// JS interactif pour le formulaire d'achat

document.addEventListener('DOMContentLoaded', function () {
    const produitSelect = document.getElementById('id_produit');
    const quantiteInput = document.getElementById('quantite');
    const villeSelect = document.getElementById('id_ville');
    const dateInput = document.getElementById('date');
    const fraisInput = document.querySelector('input[disabled][value$="%"]');
    const form = document.querySelector('form.loginAdmin-form');
    const baseUrl = form ? form.getAttribute('data-base-url') : '';

    // Zone de feedback (réutilise si déjà présent)
    let feedback = document.getElementById('achat-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = 'achat-feedback';
        feedback.style.margin = '10px 0';
        form.insertBefore(feedback, form.querySelector('button'));
    }

    // Récupère le taux de frais depuis l'input désactivé
    function getTauxFrais() {
        if (!fraisInput) return 0;
        return parseFloat(fraisInput.value.replace('%', '').replace(',', '.')) || 0;
    }

    // Met à jour le feedback interactif
    async function updateFeedback() {
        feedback.innerHTML = '';
        const id_produit = produitSelect.value;
        const id_ville = villeSelect.value;
        const quantite = quantiteInput.value;
        if (!id_produit || !quantite) return;
        // Appel AJAX pour obtenir prix unitaire, besoins restants, argent dispo
        try {
            const params = new URLSearchParams({
                id_produit,
                id_ville,
                quantite
            });
            const resp = await fetch(`${baseUrl}/achat/recap?${params.toString()}`);
            const data = await resp.json();
            if (!data.success) {
                feedback.innerHTML = `<span style='color: #e74c3c;'>${data.message}</span>`;
                return;
            }
            let html = `<b>Prix unitaire :</b> ${data.prix_unitaire} FCFA<br>`;
            html += `<b>Besoins restants :</b> ${data.besoins_restants} unités<br>`;
            html += `<b>Argent disponible :</b> ${data.argent_disponible} FCFA<br>`;
            html += `<b>Montant achat :</b> ${data.montant} FCFA<br>`;
            html += `<b>Frais (${getTauxFrais()}%) :</b> ${data.frais} FCFA<br>`;
            html += `<b>Montant total :</b> <span style='color: #2980b9;'>${data.montant_total} FCFA</span><br>`;
            if (data.montant_total > data.argent_disponible) {
                html += `<span style='color: #e74c3c;'>Fonds insuffisants pour cet achat.</span>`;
            } else if (quantite > data.besoins_restants) {
                html += `<span style='color: #e67e22;'>Quantité supérieure aux besoins restants.</span>`;
            } else {
                html += `<span style='color: #27ae60;'>Achat possible.</span>`;
            }
            feedback.innerHTML = html;
        } catch (e) {
            feedback.innerHTML = `<span style='color: #e74c3c;'>Erreur lors de la récupération des infos.</span>`;
        }
    }

    async function updateProduitsByVille() {
        const id_ville = villeSelect.value;
        try {
            const params = new URLSearchParams({ id_ville });
            const resp = await fetch(`${baseUrl}/achat/produits?${params.toString()}`);
            const data = await resp.json();
            if (!data.success) {
                return;
            }
            const current = produitSelect.value;
            produitSelect.innerHTML = '<option value="">Sélectionner un produit</option>';
            if (!data.produits || data.produits.length === 0) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Aucun produit disponible pour cette ville';
                produitSelect.appendChild(opt);
                updateFeedback();
                return;
            }
            data.produits.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = `${p.nom} (${p.quantite_restante_totale} restants)`;
                if (current && String(current) === String(p.id)) {
                    opt.selected = true;
                }
                produitSelect.appendChild(opt);
            });
            updateFeedback();
        } catch (e) {
            // silencieux
        }
    }

    produitSelect.addEventListener('change', updateFeedback);
    quantiteInput.addEventListener('input', updateFeedback);
    villeSelect.addEventListener('change', updateProduitsByVille);

    // Initialiser la liste selon la ville sélectionnée
    updateProduitsByVille();

    // Validation finale avant soumission
    form.addEventListener('submit', async function (e) {
        await updateFeedback();
        if (feedback.innerText.includes('Fonds insuffisants') || feedback.innerText.includes('Quantité supérieure')) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs avant de valider l\'achat.');
        }
    });
});
