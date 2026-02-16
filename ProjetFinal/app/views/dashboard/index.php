<?php
// dashboard/index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard BNGRC - Suivi des collectes et distributions</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .stat-box h3 {
            color: #667eea;
            font-size: 2em;
            margin: 10px 0;
        }

        .stat-box p {
            color: #666;
            font-size: 0.9em;
        }

        .content {
            padding: 30px 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background: #f8f9fa;
            border-bottom: 3px solid #667eea;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
        }

        table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.3s;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        table td {
            padding: 15px;
            color: #555;
        }

        .ville-name {
            font-weight: 600;
            color: #667eea;
            font-size: 1.05em;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .badge.satisfait {
            background: #d4edda;
            color: #155724;
        }

        .badge.partiel {
            background: #fff3cd;
            color: #856404;
        }

        .badge.attente {
            background: #f8d7da;
            color: #721c24;
        }

        .badge.na {
            background: #e2e3e5;
            color: #383d41;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin: 8px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .no-data p {
            font-size: 1.2em;
        }

        .actions {
            padding: 20px;
            background: #f8f9fa;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📊 Dashboard BNGRC</h1>
            <p>Suivi des collectes et distributions de dons pour les sinistrés</p>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-box">
                <p>Nombre de villes</p>
                <h3><?php echo $totalVilles; ?></h3>
            </div>
            <div class="stat-box">
                <p>Total des besoins</p>
                <h3><?php echo $totalBesoins; ?></h3>
            </div>
            <div class="stat-box">
                <p>Total des dons</p>
                <h3><?php echo $totalDons; ?></h3>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <?php if (!empty($dashboard)): ?>
                <h2>📋 Récapitulatif par ville</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Ville</th>
                            <th>Besoins</th>
                            <th>Quantité nécessaire</th>
                            <th>Quantité reçue</th>
                            <th>Progression</th>
                            <th>État</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dashboard as $item): ?>
                            <tr>
                                <td class="ville-name"><?php echo htmlspecialchars($item['ville']['nom']); ?></td>
                                <td><?php echo $item['totalBesoins']; ?></td>
                                <td><?php echo $item['totalBesoinsQuantite']; ?> unités</td>
                                <td><?php echo $item['totalDonsQuantite']; ?> unités</td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo min($item['pourcentage'], 100); ?>%"></div>
                                    </div>
                                    <small><?php echo $item['pourcentage']; ?>%</small>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = 'na';
                                    if (strpos($item['etat'], 'Satisfait') !== false) {
                                        $badgeClass = 'satisfait';
                                    } elseif (strpos($item['etat'], 'Partiel') !== false) {
                                        $badgeClass = 'partiel';
                                    } elseif (strpos($item['etat'], 'attente') !== false) {
                                        $badgeClass = 'attente';
                                    }
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $item['etat']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>📭 Aucune donnée disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="<?php echo Flight::get('flight.base_url'); ?>/villes" class="btn btn-secondary">🏘️ Gérer les villes</a>
            <a href="<?php echo Flight::get('flight.base_url'); ?>/besoins" class="btn btn-secondary">📌 Gérer les besoins</a>
            <a href="<?php echo Flight::get('flight.base_url'); ?>/dons" class="btn btn-primary">🎁 Ajouter un don</a>
        </div>
    </div>
</body>
</html>
