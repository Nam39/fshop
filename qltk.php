<?php
include "./connect_DB/connect_db.php";

$conn = connectData();
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $currentStatus = isset($_GET['currentStatus']) ? (int)$_GET['currentStatus'] : null;

    $stmt = $conn->prepare("SELECT trangthai FROM taikhoan WHERE idtk = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultStatus = $stmt->get_result()->fetch_assoc();
    $current = $resultStatus['trangthai'];

    if ($currentStatus !== null) {
        $current = $currentStatus;
    }

    $newStatus = ($current == 1) ? 0 : 1;
    $stmtUpdate = $conn->prepare("UPDATE taikhoan SET trangthai = ? WHERE idtk = ?");
    $stmtUpdate->bind_param("ii", $newStatus, $id);
    $stmtUpdate->execute();

    if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        echo json_encode(['status' => 'success', 'newStatus' => $newStatus, 'id' => $id]);
        exit;
    }

    echo "<script>window.location.href='qltk.php';</script>";
    exit;
}

$limit = 8;
$page = isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0 ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$totalQuery = "SELECT COUNT(*) AS total FROM taikhoan";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit);

if (isset($_GET['queryid']) && !empty($_GET['queryid'])) {
    $search = trim($_GET['queryid']);
    $sql = "
        SELECT tk.*, r.Ten 
        FROM taikhoan tk 
        LEFT JOIN role r ON tk.roleId = r.roleId 
        WHERE tk.idtk LIKE ? 
        LIMIT $limit OFFSET $offset
    ";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "
        SELECT tk.*, r.Ten
        FROM taikhoan tk 
        LEFT JOIN role r ON tk.roleId = r.roleId 
        LIMIT $limit OFFSET $offset
    ";
    $result = $conn->query($sql);
}
?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản</title>
    <link rel="stylesheet" href="./assets/fonts/css/all.min.css">
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mb-5 mt-4">
        <h2 class="text-center mb-4">Quản lý tài khoản</h2>

        <div class="row g-2 mb-4">
            <div class="">
                <div class="d-flex flex-wrap">
                    <div class="col-12 col-md-6">
                        <form action="" method="GET" class="d-flex" style="max-width: 400px;">
                            <input type="text" name="queryid" class="form-control me-2" placeholder="Tìm theo ID..." style="width: 150px;">
                            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i> Tìm theo ID</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover border align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID tài khoản</th>
                        <th>Tên</th>
                        <th>Mật khẩu</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Thời gian tạo</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['idtk'] ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['password']) ?></td>
                                <td><?= $row['Ten'] ?? 'Không rõ' ?></td>
                                <td>
                                    <a href="#"
                                        class="btn btn-sm toggle-status <?= $row['trangthai'] == 1 ? 'btn-success' : 'btn-secondary' ?>"
                                        data-id="<?= $row['idtk'] ?>"
                                        data-status="<?= $row['trangthai'] ?>"
                                        id="status-<?= $row['idtk'] ?>"
                                        onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái tài khoản này?')">
                                        <?= $row['trangthai'] == 1 ? 'Đang hoạt động' : 'Bị khóa' ?>
                                    </a>
                                </td>

                                <td><?= $row['thoigiantao'] ?></td>
                                <td><a href="./ad/suataikhoan.php?id=<?= $row['idtk'] ?>" class="btn btn-warning btn-sm">Sửa</a></td>
                                
                                <td><a href="./ad/xoatk.php?idtk=<?= $row['idtk'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa không?');">Xóa</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Không tìm thấy tài khoản nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end mb-4">
            <a href="./ad/themsanpham.php" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        <?php include "./assets/layout/navigation/navigation.php" ?>
    </div>

    <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khi click vào nút thay đổi trạng thái
            $(".toggle-status").click(function(e) {
                e.preventDefault(); // Ngăn không cho reload trang

                var id = $(this).data("id"); // Lấy ID từ data-id
                var currentStatus = $(this).data("status"); // Lấy trạng thái hiện tại

                $.ajax({
                    url: 'qltk.php', // Gọi lại chính trang này
                    method: 'GET',
                    data: {
                        toggle: 1, // Để xác định yêu cầu thay đổi trạng thái
                        id: id, // Gửi ID của tài khoản
                        currentStatus: currentStatus // Gửi trạng thái hiện tại
                    },
                    success: function(response) {
                        // Sau khi thay đổi trạng thái thành công
                        if (response.status == 'success') {
                            // Cập nhật lại nút trạng thái
                            var newStatus = response.newStatus == 1 ? 'Đang hoạt động' : 'Bị khóa';
                            var newClass = response.newStatus == 1 ? 'btn-success' : 'btn-secondary';

                            // Cập nhật lại nội dung và class
                            $("#status-" + id).text(newStatus).removeClass('btn-success btn-secondary').addClass(newClass);
                            // Cập nhật data-status của nút
                            $(".toggle-status[data-id='" + id + "']").data('status', response.newStatus);
                        }
                    }
                });
            });
        });
    </script>



</body>

</html>