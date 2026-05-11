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
            border-radius: 10px;
            padding: 10px;
            transition: 0.3s;
        }

        .product-card:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
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
        <h3>Danh mục sản phẩm</h3>
        <div class="row">
            <?php
            $dm = $conn->query("SELECT * FROM danhmucsanpham LIMIT 4");
            while ($row = $dm->fetch_assoc()):
            ?>
                <div class="col-md-3">
                    <div class="card text-center mb-3">
                        <div class="card-body">
                            <i class="fas fa-tag fa-2x mb-2"></i>
                            <h5 class="card-title"><?= htmlspecialchars($row['Ten_DanhMuc']) ?></h5>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Sản phẩm nổi bật -->
    <div class="container mt-4">
        <h3>Sản phẩm nổi bật</h3>
        <div class="row">
            <?php
            $sp = $conn->query("SELECT * FROM sanpham ORDER BY id DESC LIMIT 4");
            while ($item = $sp->fetch_assoc()):
            ?>
                <div class="col-md-3">
                    <div class="product-card mb-4">
                        <img src="./assets/img/<?= htmlspecialchars($item['Anh']) ?>" class="w-100 rounded mb-2">
                        <h5><?= htmlspecialchars($item['Ten']) ?></h5>
                        <p class="text-danger"><?= number_format($item['Gia'], 0, ',', '.') ?> VNĐ</p>
                        <a href="detail.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Xem chi tiết</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php
    include "./assets/layout/footer/index.php"
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>