<?php
session_start();
include "./connect_DB/connect_db.php";
$conn = connectData();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang chủ | UNIQ</title>
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/fonts/css/all.min.css" rel="stylesheet">
    <style>
       
        .navbar-brand {
            font-weight: bold;
            color: #007bff;
        }

        .carousel img {
            height: 400px;
            object-fit: cover;
        }

        .product-card img {
            height: 180px;
            object-fit: cover;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 14px;
            padding: 12px;
            transition: 0.3s;
            background: #fff;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: 0 0 18px rgba(0, 0, 0, 0.16);
            transform: translateY(-4px);
        }

        .category-card {
            transition: 0.3s;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.12);
        }

        .product-marquee {
            overflow: hidden;
            position: relative;
            padding: 10px 0 20px;
        }

        .product-marquee::before,
        .product-marquee::after {
            content: "";
            position: absolute;
            top: 0;
            width: 70px;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .product-marquee::before {
            left: 0;
            background: linear-gradient(90deg, #fff 0%, rgba(255, 255, 255, 0) 100%);
        }

        .product-marquee::after {
            right: 0;
            background: linear-gradient(270deg, #fff 0%, rgba(255, 255, 255, 0) 100%);
        }

        .product-marquee-track {
            display: flex;
            gap: 1.5rem;
            width: max-content;
            animation: productMarquee 35s linear infinite;
        }

        .product-marquee:hover .product-marquee-track {
            animation-play-state: paused;
        }

        .marquee-product {
            flex: 0 0 260px;
            max-width: 260px;
        }

        @keyframes productMarquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }
    </style>
</head>

<body>

    <?php
    include "./assets/layout/header/index.php"
    ?>

    <div id="myCarousel" class="carousel slide bg-dark mt-4 mb-4" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner text-center">
            <div class="carousel-item active">
                <img src="./assets/img/post-large-image1.jpg" class="d-block mx-auto" style="height: 500px; object-fit: cover;" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="./assets/img/post-large-image3.jpg" class="d-block mx-auto" style="height: 500px; object-fit: cover;" alt="Slide 2">
            </div>
            <div class="carousel-item">
                <img src="./assets/img/post-large-image2.jpg" class="d-block mx-auto" style="height: 500px; object-fit: cover;" alt="Slide 3">
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

    <!-- Danh mục -->
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Danh mục sản phẩm</h3>
            <a href="sanpham.php" class="btn btn-outline-primary btn-sm">Xem tất cả</a>
        </div>
        <div class="row">
            <?php
            $dm = $conn->query("SELECT * FROM danhmucsanpham LIMIT 4");
            while ($row = $dm->fetch_assoc()):
            ?>
                <div class="col-md-3">
                    <a href="sanpham.php?danhmuc=<?= $row['id_DanhMuc'] ?>" class="text-decoration-none text-dark">
                        <div class="card text-center mb-3 category-card">
                            <div class="card-body">
                                <i class="fas fa-tag fa-2x mb-2 text-primary"></i>
                                <h5 class="card-title mb-0"><?= htmlspecialchars($row['Ten_DanhMuc']) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Sản phẩm mới lướt phải sang trái -->
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-1">Sản phẩm mới nhất</h3>
                <p class="text-muted mb-0">Tự động lướt từ phải sang trái, sắp xếp từ mới đến cũ.</p>
            </div>
            <a href="sanpham.php" class="btn btn-primary btn-sm">Mua sắm ngay</a>
        </div>

        <?php
        $sp = $conn->query("SELECT * FROM sanpham ORDER BY id DESC LIMIT 10");
        $latestProducts = $sp ? $sp->fetch_all(MYSQLI_ASSOC) : [];
        ?>

        <?php if (!empty($latestProducts)): ?>
            <div class="product-marquee" aria-label="Sản phẩm mới nhất">
                <div class="product-marquee-track">
                    <?php for ($loop = 0; $loop < 2; $loop++): ?>
                        <?php foreach ($latestProducts as $item): ?>
                            <div class="marquee-product">
                                <div class="product-card mb-4">
                                    <img src="./assets/img/<?= htmlspecialchars($item['Anh']) ?>" class="w-100 rounded mb-2" alt="<?= htmlspecialchars($item['Ten']) ?>">
                                    <h5 class="text-truncate"><?= htmlspecialchars($item['Ten']) ?></h5>
                                    <p class="text-danger fw-semibold mb-2"><?= number_format($item['Gia'], 0, ',', '.') ?> VNĐ</p>
                                    <a href="detail.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary w-100">Xem chi tiết</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Chưa có sản phẩm để hiển thị.</div>
        <?php endif; ?>
    </div>

    <?php
    include "./assets/layout/footer/index.php"
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>