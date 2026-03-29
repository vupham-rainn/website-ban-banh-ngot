<?php
    if(isset($_GET['id']) && $_GET['id'] > 0) {
        $order_id = $_GET['id'];
    } else {
        // Tránh lỗi nếu không có ID
        die("Không tìm thấy mã đơn hàng!");
    }

    $order_details = $OrderModel->getFullOrderInformation($order_id);

    // KIỂM TRA: Nếu không có dữ liệu đơn hàng thì dừng lại để tránh lỗi extract
    if (!$order_details || count($order_details) == 0) {
        die("Đơn hàng không tồn tại!");
    }

    // Lấy thông tin chung của đơn hàng từ phần tử đầu tiên
    $first_item = $order_details[0];
    extract($first_item); 
    // Sau khi extract dòng này, bạn sẽ có sẵn các biến: $status, $order_date, $full_name, $total, v.v.

    // Trạng thái đơn hàng (Sửa lỗi dùng biến $status chưa định nghĩa)
    $order_status = 'Chờ xác nhận';
    if(isset($status)) {
        if($status == 2) {
            $order_status = 'Đã xác nhận';
        } elseif($status == 3) {
            $order_status = 'Đang giao';
        } elseif($status == 4) {
            $order_status = 'Giao thành công';
        }
    }

    // Định dạng ngày (Sửa lỗi dùng $order_date chưa định nghĩa)
    $date_formated = isset($order_date) ? $BaseModel->date_format($order_date, '') : 'N/A';

    // Cập nhật trạng thái
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status_order"])) {
        $status_new = $_POST["status"];
        $order_id_post = $_POST["order_id"];
        $OrderModel->update_status_order($status_new, $order_id_post);
        header("Location: index.php?quanli=cap-nhat-don-hang&id=$order_id_post");
        exit();
    }
?>

<div class="container pt-4">
    <article class="card">
        <header class="card-header text-dark">
            <h6> 
                <a href="index.php?quanli=danh-sach-don-hang" class="link-not-hover">Đơn hàng</a> 
                / Chi tiết đơn hàng
            </h6>
        </header>
        <div class="card-body mt-2">
            <ul class="row">
                <?php foreach ($order_details as $value): extract($value); ?>
                <li class="col-md-4">
                    <figure class="itemside mb-3">
                        <div class="aside"><img src="../upload/<?=$product_image?>" class="img-sm border"></div>
                        <figcaption class="info align-self-center">
                            <p class="title"><?=$product_name?> <br> </p> 
                            <span class="text-danger"><?=number_format($price)?>₫ </span><span>x<?=$quantity?></span>
                        </figcaption>
                    </figure>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="row">
                <div class="col-lg-6">
                    <div class="bg-custom rounded border" style="background-color: #ffff;">
                        <div class="p-4">
                            <h6 class="mb-4">
                                Trạng thái đơn hàng: <span class="text-danger"><?=$order_status?></span>
                            </h6>

                            <?php
                                if (!function_exists('getStatusName')) {
                                    function getStatusName($statusValue) {
                                        switch ($statusValue) {
                                            case 1: return 'Chờ xác nhận';
                                            case 2: return 'Đã xác nhận';
                                            case 3: return 'Đang giao';
                                            case 4: return 'Giao thành công';
                                            default: return 'Không xác định';
                                        }
                                    }
                                }
                            ?>

                            <form action="" method="post">
                                <div class="form-floating mb-3">
                                    <select name="status" class="form-select" id="floatingSelect">
                                        <?php
                                        $status_options = [1, 2, 3, 4];
                                        foreach ($status_options as $option_value) {
                                            $selected = (isset($status) && $option_value == $status) ? 'selected' : '';
                                            echo "<option value='$option_value' $selected>" . getStatusName($option_value) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="floatingSelect">Trạng thái</label>
                                </div>
                                <input type="hidden" name="order_id" value="<?=$order_id?>">
                                <h6 class="mb-4">
                                    <input type="submit" name="update_status_order" value="Cập nhật" class="btn btn-primary">
                                </h6>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-4 bg-custom" style="background-color: #ffff;">
                        <div class="card-body text-dark">
                            <div class="row mb-3">
                                <div class="col-sm-4"><p class="mb-0">Khách hàng</p></div>
                                <div class="col-sm-8"><p class="mb-0 text-end fw-bold"><?=$full_name ?? 'N/A'?></p></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><p class="mb-0">Số điện thoại</p></div>
                                <div class="col-sm-8"><p class="mb-0 text-end"><?=$order_phone ?? 'N/A'?></p></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><p class="mb-0">Địa chỉ</p></div>
                                <div class="col-sm-8"><p class="mb-0 text-end"><?=$order_address ?? 'N/A'?></p></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><p class="mb-0">Thời gian</p></div>
                                <div class="col-sm-8"><p class="mb-0 text-end"><?=$date_formated?></p></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4"><p class="mb-0">Thành tiền</p></div>
                                <div class="col-sm-8">
                                    <p style="font-size: 1.5rem;" class="mb-0 text-end text-danger fw-bold">
                                        <?=number_format($total ?? 0)?>₫
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>