<?php
// dashboard.php
session_start();
require_once __DIR__ . '/db.php';

// Optional: gate by login/role if your old backend sets these
// if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$vehicleCounts = ['car' => 0, 'motorcycle' => 0, 'van' => 0];
$guards = [];
$error = null;

// --- Fetch vehicle counts grouped by type ---
try {
    $sql = "SELECT type, COUNT(*) AS cnt FROM vehicles GROUP BY type";
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $type = strtolower($row['type'] ?? '');
            if (isset($vehicleCounts[$type])) {
                $vehicleCounts[$type] = (int)$row['cnt'];
            }
        }
        $res->free();
    } else {
        $error = "Failed to fetch vehicle counts.";
    }
} catch (Throwable $t) {
    $error = "Error fetching vehicles: " . $t->getMessage();
}

// Compute "registered" same as Flutter: sum of all types
$registered = $vehicleCounts['car'] + $vehicleCounts['motorcycle'] + $vehicleCounts['van'];

// --- Fetch on-duty guards (adjust if your schema differs) ---
try {
    // If your table uses `status='available'`, switch WHERE on_duty = 1 to WHERE status = 'available'
    $stmt = $conn->prepare("SELECT name FROM guards WHERE on_duty = 1 ORDER BY name");
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $guards[] = $row['name'];
    }
    $stmt->close();
} catch (Throwable $t) {
    $error = "Error fetching guards: " . $t->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Voir — Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background:#f7f7f7; }
        .card-red { background:#dc3545; color:#fff; }
        .icon-lg { font-size:2rem; }
        .stat { font-weight:700; font-size:1.25rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <span class="navbar-brand mb-0 h1 text-danger">Voir</span>
        <div class="d-flex align-items-center gap-3">
            <!-- Optional role/user display -->
            <?php if (!empty($_SESSION['role'])): ?>
                <span class="badge text-bg-light">Role: <?= htmlspecialchars($_SESSION['role']) ?></span>
            <?php endif; ?>
            <!-- <a class="btn btn-outline-secondary btn-sm" href="logout.php">Logout</a> -->
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php if ($error): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Registered Count -->
    <div class="card card-red mb-4">
        <div class="card-body d-flex align-items-center">
            <div class="me-3">
                <i class="bi bi-people-fill icon-lg"></i>
            </div>
            <div>
                <div class="stat"><?= (int)$registered ?> registered</div>
                <div class="small opacity-75">Total vehicles (car + motorcycle + van)</div>
            </div>
        </div>
    </div>

    <!-- Vehicle Icons Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="text-danger icon-lg">🚗</span>
                    <div>
                        <div class="fw-semibold">Cars</div>
                        <div class="stat text-danger"><?= (int)$vehicleCounts['car'] ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="text-danger icon-lg">🏍️</span>
                    <div>
                        <div class="fw-semibold">Motorcycles</div>
                        <div class="stat text-danger"><?= (int)$vehicleCounts['motorcycle'] ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="text-danger icon-lg">🚐</span>
                    <div>
                        <div class="fw-semibold">Vans</div>
                        <div class="stat text-danger"><?= (int)$vehicleCounts['van'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Guards -->
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            Available Guards (<?= count($guards) ?>)
        </div>
        <div class="card-body p-0">
            <?php if (empty($guards)): ?>
                <div class="p-3 text-muted fst-italic">No guards on duty.</div>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($guards as $g): ?>
                        <li class="list-group-item"><?= htmlspecialchars($g) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Bootstrap JS + Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
</body>
</html>
