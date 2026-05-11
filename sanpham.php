<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "./connect_DB/connect_db.php";
$conn = connectData();

/* ================= USER ================= */

if (isset($_SESSION['idtk'])) {
    $idtk = $_SESSION['idtk'];

    $sql = "SELECT Ten_user, Anh_user
            FROM users
            WHERE idtk = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idtk);
    $stmt->execute();

    $userInfo = $stmt->get_result()->fetch_assoc();

    if ($userInfo) {
        $_SESSION['Ten_user'] = $userInfo['Ten_user'];
        $_SESSION['Anh_user'] = $userInfo['Anh_user'];
    }
}

/* ================= PHÂN TRANG ================= */

$limit = 4;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

/* ================= DANH MỤC ================= */

$categories = [];
$categoryNames = [];

$sqlDanhMuc = "
    SELECT
        dm.id_DanhMuc,
        dm.Ten_DanhMuc,
        COUNT(sp.id) AS total
    FROM danhmucsanpham dm
    LEFT JOIN sanpham sp
        ON dm.id_DanhMuc = sp.id_DanhMuc
    GROUP BY dm.id_DanhMuc, dm.Ten_DanhMuc
    ORDER BY dm.id_DanhMuc ASC
";

$danhMucResult = $conn->query($sqlDanhMuc);

if ($danhMucResult) {
    while ($row = $danhMucResult->fetch_assoc()) {
        $categoryId = (int)$row['id_DanhMuc'];
        $categories[$categoryId] = [
            'id_DanhMuc' => $categoryId,
            'Ten_DanhMuc' => $row['Ten_DanhMuc'],
            'total' => (int)($row['total'] ?? 0),
        ];
        $categoryNames[$categoryId] = $row['Ten_DanhMuc'];
    }
}

ksort($categories);
$allCategoryProducts = array_sum(array_column($categories, 'total'));

$currentCategory = isset($_GET['danhmuc']) ? (int)$_GET['danhmuc'] : 0;
if ($currentCategory > 0 && !isset($categoryNames[$currentCategory])) {
    $currentCategory = 0;
}

$currentCategoryName = $currentCategory > 0 ? $categoryNames[$currentCategory] : '';

/* ================= TÌM KIẾM ================= */

$search = isset($_GET['query']) ? trim($_GET['query']) : "";
$searchParam = "%$search%";

/* ================= ĐẾM VÀ LẤY SẢN PHẨM ================= */

$products = [];
$productsByCategory = [];
$totalProducts = 0;
$limitSql = (int)$limit;
$offsetSql = (int)$offset;

if ($currentCategory > 0 && $search !== '') {
    $countSql = "
        SELECT COUNT(*) AS total
        FROM sanpham
        WHERE id_DanhMuc = ?
        AND (Ten LIKE ? OR MoTa LIKE ?)
    ";

    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("iss", $currentCategory, $searchParam, $searchParam);
    $countStmt->execute();
    $totalProducts = (int)($countStmt->get_result()->fetch_assoc()['total'] ?? 0);

    $sql = "
        SELECT
            sp.id,
            sp.Ten,
            sp.MoTa,
            sp.Gia,
            sp.soluong,
            sp.Anh,
            sp.id_DanhMuc,
            dm.Ten_DanhMuc
        FROM sanpham sp
        LEFT JOIN danhmucsanpham dm
            ON sp.id_DanhMuc = dm.id_DanhMuc
        WHERE sp.id_DanhMuc = ?
        AND (sp.Ten LIKE ? OR sp.MoTa LIKE ?)
        ORDER BY sp.id_DanhMuc ASC, sp.id ASC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issii", $currentCategory, $searchParam, $searchParam, $limitSql, $offsetSql);
} elseif ($currentCategory > 0) {
    $countSql = "
        SELECT COUNT(*) AS total
        FROM sanpham
        WHERE id_DanhMuc = ?
    ";

    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("i", $currentCategory);
    $countStmt->execute();
    $totalProducts = (int)($countStmt->get_result()->fetch_assoc()['total'] ?? 0);

    $sql = "
        SELECT
            sp.id,
            sp.Ten,
            sp.MoTa,
            sp.Gia,
            sp.soluong,
            sp.Anh,
            sp.id_DanhMuc,
            dm.Ten_DanhMuc
        FROM sanpham sp
        LEFT JOIN danhmucsanpham dm
            ON sp.id_DanhMuc = dm.id_DanhMuc
        WHERE sp.id_DanhMuc = ?
        ORDER BY sp.id_DanhMuc ASC, sp.id ASC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $currentCategory, $limitSql, $offsetSql);
} elseif ($search !== '') {
    $countSql = "
        SELECT COUNT(*) AS total
        FROM sanpham
        WHERE Ten LIKE ?
        OR MoTa LIKE ?
    ";

    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("ss", $searchParam, $searchParam);
    $countStmt->execute();
    $totalProducts = (int)($countStmt->get_result()->fetch_assoc()['total'] ?? 0);

    $sql = "
        SELECT
            sp.id,
            sp.Ten,
            sp.MoTa,
            sp.Gia,
            sp.soluong,
            sp.Anh,
            sp.id_DanhMuc,
            dm.Ten_DanhMuc
        FROM sanpham sp
        LEFT JOIN danhmucsanpham dm
            ON sp.id_DanhMuc = dm.id_DanhMuc
        WHERE sp.Ten LIKE ?
        OR sp.MoTa LIKE ?
        ORDER BY sp.id_DanhMuc ASC, sp.id ASC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $searchParam, $searchParam, $limitSql, $offsetSql);
} else {
    $countSql = "SELECT COUNT(*) AS total FROM sanpham";
    $countResult = $conn->query($countSql);
    $totalProducts = (int)($countResult->fetch_assoc()['total'] ?? 0);

    $sql = "
        SELECT
            sp.id,
            sp.Ten,
            sp.MoTa,
            sp.Gia,
            sp.soluong,
            sp.Anh,
            sp.id_DanhMuc,
            dm.Ten_DanhMuc
        FROM sanpham sp
        LEFT JOIN danhmucsanpham dm
            ON sp.id_DanhMuc = dm.id_DanhMuc
        ORDER BY sp.id_DanhMuc ASC, sp.id ASC
        LIMIT ? OFFSET ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limitSql, $offsetSql);
}

$totalPages = max(1, (int)ceil($totalProducts / $limit));

if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
    $offsetSql = (int)$offset;

    if (isset($stmt)) {
        $stmt->close();
    }

    if ($currentCategory > 0 && $search !== '') {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issii", $currentCategory, $searchParam, $searchParam, $limitSql, $offsetSql);
    } elseif ($currentCategory > 0) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $currentCategory, $limitSql, $offsetSql);
    } elseif ($search !== '') {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $searchParam, $searchParam, $limitSql, $offsetSql);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limitSql, $offsetSql);
    }
}

if (isset($stmt) && $stmt) {
    $stmt->execute();
    $productResult = $stmt->get_result();

    while ($product = $productResult->fetch_assoc()) {
        $categoryId = (int)$product['id_DanhMuc'];
        $categoryName = $product['Ten_DanhMuc'] ?: ($categoryNames[$categoryId] ?? 'Không rõ');

        if (!isset($productsByCategory[$categoryId])) {
            $productsByCategory[$categoryId] = [
                'id_DanhMuc' => $categoryId,
                'Ten_DanhMuc' => $categoryName,
                'items' => [],
            ];
        }

        $productsByCategory[$categoryId]['items'][] = $product;
        $products[] = $product;
    }
}

ksort($productsByCategory);
$visibleCategories = $categories;

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thương Mại Điện Tử</title>
    <link rel="stylesheet" href="./assets/fonts/css/all.min.css">
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/css/index.css" rel="stylesheet">
    <style>
        html a {
            text-decoration: none;
            color: #333;
        }

        .nav {
            z-index: 10;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px;
        }

        .user-img:hover .box {
            display: block;
        }

        .avatar-img {
            border: 1px solid rgba(0, 0, 0, 0.3);
        }

        .box {
            margin-top: 3px;
            z-index: 100;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #fff;
            width: 180px;
            border-radius: 8px;
            display: none;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            padding: 10px 0;
        }

        .box-name {
            font-weight: 500;
            font-size: 18px;
            margin: 4px 0;
        }

        .box a {
            display: block;
            padding: 8px 15px;
            font-size: 14px;
            color: #333;
            transition: background-color 0.2s;
        }

        .box a:hover {
            background-color: #f1f1f1;
        }

        .logoutbtn:hover {
            color: red;
        }

        .aff {
            position: relative;
        }

        .aff-child::after {
            content: "";
            position: absolute;
            left: 0;
            top: 14px;
            background-color: transparent;
            width: 100%;
            height: 30px;
        }

        .dropdown-divider {
            margin: 0.3rem 0;
            border-top: 1px solid rgb(65, 67, 75);
        }
        .active-category {
    background: #0d6efd;
    border-radius: 6px;
}

.active-category a,
.active-category span {
    color: white !important;
}

        .product-layout {
            align-items: flex-start;
        }

        .category-panel {
            position: sticky;
            top: 92px;
        }

        .category-panel .list-item {
            gap: 8px;
            padding: 10px 12px;
            border-bottom: 1px solid #edf0f2;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .category-product-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            padding: 12px 16px;
            border-left: 5px solid #0d6efd;
            border-radius: 10px;
            background: #f1f7ff;
        }

        .category-product-heading h4 {
            color: #0d6efd;
            font-size: 18px;
            font-weight: 700;
        }

        .category-product-heading span {
            color: #6c757d;
            font-size: 14px;
            white-space: nowrap;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
        }

        .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .product-card .card-body {
            display: flex;
            flex: 1;
            flex-direction: column;
        }

        .product-actions {
            margin-top: auto;
        }

        @media (max-width: 1199.98px) {
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .category-panel {
                position: static;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }
        }

    </style>
</head>

<body>
    <div class="main">

        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow position-fixed nav">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="./">UNIQ</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link active" href="./index.php">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link active" href="./sanpham.php">Sản phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" href="./giohang.php"><i class="fa-solid fa-cart-shopping text-secondary mx-1"></i>Giỏ hàng <span class="badge bg-danger"></span></a></li>
                    </ul>

                    <form class="d-flex me-3" method="GET" action="sanpham.php">
                        <input class="form-control me-2  border border-dark" type="search" name="query" placeholder="Tìm kiếm sản phẩm...">
                        <button class="btn btn-outline-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>

                    <div class="ms-auto">
                        <?php if (isset($_SESSION['idtk'])): ?>
                            <div class="user-img aff d-flex align-items-center justify-content-center position-relative">
                                <img src="./assets/img/<?= $_SESSION['Anh_user'] ?>" alt="Avatar" class="rounded-circle avatar-img" width="40" height="40">
                                <p class="box-name px-2 d-flex align-items-center mb-0 ms-2">
                                    <?= htmlspecialchars($_SESSION['Ten_user']) ?>
                                    <i class="fa-solid fa-sort-down mx-1 mb-1"></i>
                                </p>

                                <div class="box text-start">
                                    <a href="./thongtinuser.php">Thông tin chi tiết</a>
                                    <a href="./donhang.php">Đơn hàng của tôi</a>
                                    <a href="./doimatkhau.php">Đổi mật khẩu</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="./logout.php" class="logoutbtn" onclick="return confirm('Bạn có muốn đăng xuất không?');">Đăng xuất</a>
                                </div>

                                <div class="aff-child"></div>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Đăng nhập</a>
                            <a href="signup.php" class="btn btn-secondary">Đăng ký</a>
                        <?php endif; ?>
                    </div>
                </div>
        </nav>


        <div id="myCarousel" class="carousel slide bg-dark mt-4 mb-4" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner text-center">
                <div class="carousel-item active">
                    <img src="./assets/img/cat-item1.jpg" class="d-block mx-auto" style="height: 500px; object-fit: cover;" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="./assets/img/cat-item2.jpg" class="d-block mx-auto" style="height: 500px; object-fit: cover;" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="./assets/img/cat-item3.jpg" class="d-block mx-auto" style="height: 500px; object-fit: cover;" alt="Slide 3">
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="container mt-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">
                        <?= $currentCategoryName ? 'Sản phẩm ' . htmlspecialchars($currentCategoryName) : 'Tất cả sản phẩm' ?>
                    </h3>
                    <p class="text-muted mb-0">Hiển thị tất cả sản phẩm trong database, sắp xếp và chia nhóm theo id_DanhMuc.</p>
                </div>
                <a href="sanpham.php" class="btn btn-outline-primary btn-sm mt-2 mt-md-0">Xem tất cả</a>
            </div>
            <div class="row g-4 product-layout">
                <aside class="col-12 col-lg-3 col-xl-2">
                    <div class="category-panel">
                        <p class="text-title">Danh mục</p>

                        <ul class="list-group list-cus">

                            <!-- TẤT CẢ -->
                            <li class="list-item d-flex justify-content-between align-items-center
                                <?= $currentCategory == 0 ? 'active-category' : '' ?>">

                                <a href="sanpham.php">Tất cả</a>
                                <span>(<?= $allCategoryProducts ?>)</span>
                            </li>

                            <?php foreach ($visibleCategories as $dm): ?>

                                <li class="list-item d-flex justify-content-between align-items-center
                                    <?= $currentCategory == $dm['id_DanhMuc'] ? 'active-category' : '' ?>">

                                    <a href="sanpham.php?danhmuc=<?= $dm['id_DanhMuc'] ?>">
                                        <?= htmlspecialchars($dm['Ten_DanhMuc']) ?>
                                    </a>

                                    <span>(<?= $dm['total'] ?>)</span>
                                </li>

                            <?php endforeach; ?>

                        </ul>
                    </div>
                </aside>

                <section class="col-12 col-lg-9 col-xl-10">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <p class="text-muted mb-0">
                            Hiển thị <?= count($products) ?> / <?= $totalProducts ?> sản phẩm
                            (trang <?= $page ?> / <?= $totalPages ?>), chia theo id_DanhMuc
                            <?php if ($search !== ''): ?>
                                với từ khóa "<?= htmlspecialchars($search) ?>"
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if (!empty($productsByCategory)): ?>
                        <?php foreach ($productsByCategory as $categoryId => $group): ?>
                            <div class="category-product-section mb-4">
                                <div class="category-product-heading">
                                    <h4 class="mb-0">
                                        id_DanhMuc <?= (int)$categoryId ?> - <?= htmlspecialchars($group['Ten_DanhMuc']) ?>
                                    </h4>
                                    <span><?= count($group['items']) ?> sản phẩm</span>
                                </div>

                                <div class="product-grid">
                                    <?php foreach ($group['items'] as $row): ?>
                                        <article class="product-card">
                                            <a href="./detail.php?id=<?= $row['id'] ?>">
                                                <img src="./assets/img/<?= htmlspecialchars($row['Anh']) ?>" alt="<?= htmlspecialchars($row['Ten']) ?>" onerror="this.onerror=null;this.src='./assets/img/cat-item1.jpg';">
                                            </a>

                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($row['Ten']) ?></h5>
                                                <p class="card-text description-clamp"><?= htmlspecialchars($row['MoTa']) ?></p>
                                                <p class="mb-1">id_DanhMuc: <b><?= (int)$row['id_DanhMuc'] ?></b></p>
                                                <p class="mb-1">Danh mục: <b><?= htmlspecialchars($row['Ten_DanhMuc'] ?: 'Không rõ') ?></b></p>
                                                <p class="mb-1">Tồn kho: <?= (int)$row['soluong'] ?></p>
                                                <p class="text-danger fw-bold">
                                                    Giá: <?= number_format($row['Gia'], 0, ',', '.') ?> VNĐ
                                                </p>

                                                <div class="product-actions d-flex align-items-center gap-2 flex-nowrap">
                                                    <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-detail text-nowrap">
                                                        Chi tiết sản phẩm
                                                    </a>
                                                    <form action="themvaogio.php" method="POST" class="m-0">
                                                        <input type="hidden" name="idsanpham" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="btn btn-success d-flex justify-content-center align-items-center cart-btn">
                                                            <i class="fa-solid fa-cart-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            Không tìm thấy sản phẩm nào trong danh mục này.
                        </div>
                    <?php endif; ?>
                </section>
            </div>

            <div class="mt-4 d-flex flex-wrap justify-content-center gap-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                    $link = "?page=$i";

                    if ($currentCategory > 0) {
                        $link .= "&danhmuc=$currentCategory";
                    }

                    if ($search !== '') {
                        $link .= "&query=" . urlencode($search);
                    }
                    ?>

                    <a href="<?= $link ?>"
                       class="btn <?= $page == $i ? 'btn-primary' : 'btn-outline-primary' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

        </div>

        <?php include "./assets/layout/footer/index.php" ?>
    </div>

    <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
