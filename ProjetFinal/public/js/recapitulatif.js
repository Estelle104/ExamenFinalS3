document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-actualiser-recap');
    const content = document.getElementById('recap-content');
    const recapSection = document.getElementById('recapitulatif');
    const baseUrl = recapSection ? recapSection.getAttribute('data-base-url') : '';
    async function chargerRecap() {
        content.innerHTML = '<em>Chargement...</em>';
        try {
            const resp = await fetch(`${baseUrl}/recapitulatif/ajax`);
            const data = await resp.json();
            if (!data.success) {
                content.innerHTML = `<span style='color:#e74c3c;'>${data.message}</span>`;
                return;
            }
            let html = `<table style='width:100%;border-collapse:collapse;'>`;
            html += `<tr><th style='text-align:left;'>Produit</th><th>Total besoin (FCFA)</th><th>Satisfait (FCFA)</th><th>Restant (FCFA)</th></tr>`;
            data.recap.forEach(row => {
                html += `<tr>`;
                html += `<td>${row.produit}</td>`;
                html += `<td>${row.total}</td>`;
                html += `<td>${row.satisfait}</td>`;
                html += `<td>${row.restant}</td>`;
                html += `</tr>`;
            });
            html += `</table>`;
            content.innerHTML = html;
        } catch (e) {
            content.innerHTML = `<span style='color:#e74c3c;'>Erreur lors du chargement.</span>`;
        }
    }
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        chargerRecap();
    });
    chargerRecap();
});
