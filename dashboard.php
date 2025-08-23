<?php
// dashboard.php
session_start();
require_once __DIR__ . '/db.php';

// --- Fetch Stats ---
// Registered vehicles (total)
$totalVehicles = $conn->query("SELECT COUNT(*) as c FROM vehicles")->fetch_assoc()['c'] ?? 0;

// Cars
$totalCars = $conn->query("SELECT COUNT(*) as c FROM vehicles WHERE type='car'")->fetch_assoc()['c'] ?? 0;

// Motorcycles
$totalMotorcycles = $conn->query("SELECT COUNT(*) as c FROM vehicles WHERE type='motorcycle'")->fetch_assoc()['c'] ?? 0;

// Vans
$totalVans = $conn->query("SELECT COUNT(*) as c FROM vehicles WHERE type='van'")->fetch_assoc()['c'] ?? 0;

// Guards available
$availableGuards = $conn->query("SELECT COUNT(*) as c FROM guards WHERE status='available'")->fetch_assoc()['c'] ?? 0;

// --- Fetch Vehicles Table Data ---
$vehicleData = $conn->query("SELECT * FROM vehicles ORDER BY registered_at DESC");

// --- OCR Logs --- 
$sql = "SELECT license_plate, detected_at FROM ocr_logs ORDER BY detected_at DESC LIMIT 20";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Dashboard - Voir</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/dashboard-cards.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="dashboard.php">Voir</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <!-- Dashboard Cards -->
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-danger text-white h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fs-1 fw-bold"><?php echo $totalVehicles; ?></div>
                                            <div class="text-uppercase">Registered Vehicles</div>
                                        </div>
                                        <i class="fas fa-car fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fs-1 fw-bold"><?php echo $totalCars; ?></div>
                                            <div class="text-uppercase">Cars</div>
                                        </div>
                                        <i class="fas fa-car-side fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fs-1 fw-bold"><?php echo $totalMotorcycles; ?></div>
                                            <div class="text-uppercase">Motorcycles</div>
                                        </div>
                                        <i class="fas fa-motorcycle fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-warning text-white h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fs-1 fw-bold"><?php echo $totalVans; ?></div>
                                            <div class="text-uppercase">Vans</div>
                                        </div>
                                        <i class="fas fa-shuttle-van fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fs-1 fw-bold"><?php echo $availableGuards; ?></div>
                                            <div class="text-uppercase">Guards Available</div>
                                        </div>
                                        <i class="fas fa-user-shield fa-4x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicles Table -->
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-table me-1"></i> Registered Vehicles</div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Owner</th>
                                            <th>Vehicle Type</th>
                                            <th>License Plate</th>
                                            <th>Category</th>
                                            <th>SR Code</th>
                                            <th>Registered At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = $vehicleData->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['owner_name']); ?></td>
                                                <td><?php echo htmlspecialchars(ucfirst($row['type'])); ?></td>
                                                <td><?php echo htmlspecialchars($row['license_plate']); ?></td>
                                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                                <td><?php echo htmlspecialchars($row['sr_code']); ?></td>
                                                <td><?php echo htmlspecialchars($row['registered_at']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                         <h2>OCR Detection Logs</h2>
                            <table border="1" cellpadding="10">
                                <tr>
                                    <th>License Plate</th>
                                    <th>Detected At</th>
                                </tr>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['license_plate']; ?></td>
                                    <td><?php echo $row['detected_at']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
<?php $conn->close(); ?>
