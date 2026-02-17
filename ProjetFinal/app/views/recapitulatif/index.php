<section id="recapitulatif" data-base-url="<?php echo Flight::get('flight.base_url'); ?>">
    <h2>Récapitulatif des besoins</h2>
    <button id="btn-actualiser-recap" type="button" style="margin-bottom:1rem;">Actualiser</button>
    <div id="recap-content">
        <em>Chargement...</em>
    </div>
</section>
<style>
    /* ===== RÉCAPITULATIF BNGRC ===== */
#recapitulatif {
    max-width: 1100px;
    margin: 2rem auto;
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 10px 30px -5px rgba(30, 58, 138, 0.15), 0 8px 15px -6px rgba(0, 0, 0, 0.05);
    padding: 2rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

#recapitulatif:hover {
    box-shadow: 0 20px 40px -10px rgba(30, 58, 138, 0.25);
}

#recapitulatif h2 {
    margin-bottom: 1.5rem;
    color: #1e3a8a;
    font-size: 1.8rem;
    font-weight: 700;
    letter-spacing: -0.025em;
    position: relative;
    display: inline-block;
    padding-left: 1rem;
}

#recapitulatif h2::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 70%;
    background: linear-gradient(180deg, #fbbf24, #f59e0b);
    border-radius: 4px;
}

/* Bouton d'actualisation */
#btn-actualiser-recap {
    background: linear-gradient(135deg, #1e3a8a, #2d4ec0);
    color: #fff;
    border: none;
    padding: 0.7rem 1.8rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 6px -2px rgba(30, 58, 138, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

#btn-actualiser-recap::before {
    content: '↻';
    font-size: 1.2rem;
    transition: transform 0.5s ease;
}

#btn-actualiser-recap:hover {
    background: linear-gradient(135deg, #2d4ec0, #1e3a8a);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -5px rgba(30, 58, 138, 0.4);
}

#btn-actualiser-recap:hover::before {
    transform: rotate(360deg);
}

#btn-actualiser-recap:active {
    transform: translateY(0);
    box-shadow: 0 4px 6px -2px rgba(30, 58, 138, 0.3);
}

#btn-actualiser-recap:focus-visible {
    outline: 2px solid #fbbf24;
    outline-offset: 2px;
}

/* Conteneur du contenu */
#recap-content {
    margin-top: 2rem;
    border-radius: 0.75rem;
    overflow: hidden;
}

/* Tableau */
#recap-content table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.95rem;
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

#recap-content th {
    padding: 1rem 1.25rem;
    background: #f8fafc;
    color: #1e3a8a;
    font-weight: 700;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid #fbbf24;
    white-space: nowrap;
}

#recap-content td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    transition: background 0.2s ease;
}

#recap-content tbody tr {
    transition: all 0.2s ease;
}

#recap-content tbody tr:last-child td {
    border-bottom: none;
}

#recap-content tbody tr:hover {
    background: #f0f4ff;
    transform: scale(1.002);
    box-shadow: 0 2px 8px rgba(30, 58, 138, 0.08);
}

#recap-content tbody tr:hover td {
    background: transparent;
}

/* Types de colonnes */
#recap-content td:first-child {
    font-weight: 600;
    color: #1e3a8a;
}

#recap-content td:nth-child(2) {
    font-weight: 600;
    color: #059669;
}

#recap-content td:nth-child(3) {
    font-weight: 600;
    color: #b45309;
}

/* Ligne de total ou pied de tableau */
#recap-content tfoot td {
    background: #f8fafc;
    padding: 1rem 1.25rem;
    font-weight: 700;
    color: #1e3a8a;
    border-top: 2px solid #e2e8f0;
    border-bottom: none;
}

/* Indicateurs de statut */
.recap-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.recap-badge--complet {
    background: #d1fae5;
    color: #065f46;
}

.recap-badge--partiel {
    background: #fef3c7;
    color: #92400e;
}

.recap-badge--urgent {
    background: #fee2e2;
    color: #b91c1c;
}

/* Barre de progression dans le tableau */
.recap-progress {
    width: 100px;
    height: 6px;
    background: #e2e8f0;
    border-radius: 9999px;
    overflow: hidden;
}

.recap-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #fbbf24, #f59e0b);
    border-radius: 9999px;
    transition: width 0.3s ease;
}

/* Message de chargement */
.recap-loading {
    padding: 3rem;
    text-align: center;
    color: #64748b;
}

.recap-loading::after {
    content: '';
    display: inline-block;
    width: 1rem;
    height: 1rem;
    margin-left: 0.5rem;
    border: 2px solid #e2e8f0;
    border-top-color: #1e3a8a;
    border-radius: 50%;
    animation: recaps-spin 0.6s linear infinite;
}

@keyframes recaps-spin {
    to { transform: rotate(360deg); }
}

/* Message d'absence de données */
.recap-empty {
    padding: 3rem;
    text-align: center;
    background: #f8fafc;
    border-radius: 0.75rem;
    color: #64748b;
    border: 2px dashed #e2e8f0;
}

.recap-empty::before {
    content: '📊';
    display: block;
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Version mobile */
@media (max-width: 768px) {
    #recapitulatif {
        margin: 1rem;
        padding: 1.5rem;
    }
    
    #recapitulatif h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    #btn-actualiser-recap {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
    }
    
    #recap-content {
        margin-top: 1.5rem;
        overflow-x: auto;
    }
    
    #recap-content table {
        min-width: 650px;
    }
    
    #recap-content th,
    #recap-content td {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    #recap-content td:first-child {
        white-space: nowrap;
    }
}

@media (max-width: 480px) {
    #recapitulatif {
        margin: 0.5rem;
        padding: 1rem;
    }
    
    #recapitulatif h2 {
        font-size: 1.3rem;
    }
    
    #btn-actualiser-recap {
        width: 100%;
        justify-content: center;
    }
}

/* Option d'impression */
@media print {
    #recapitulatif {
        box-shadow: none;
        margin: 0;
        padding: 1rem;
    }
    
    #btn-actualiser-recap {
        display: none;
    }
    
    #recap-content th {
        background: #f8f9fa !important;
        color: #000 !important;
        border-bottom: 2px solid #000;
    }
    
    #recap-content tbody tr:hover {
        background: none;
    }
}
</style>
<script nonce="<?php echo Flight::get('csp_nonce'); ?>" src="<?php echo Flight::get('flight.base_url'); ?>/public/js/recapitulatif.js"></script>
