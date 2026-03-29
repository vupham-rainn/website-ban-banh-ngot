<?php
    $success = '';

    // Đưa vào thùng rác
    if (isset($_GET['xoatam']) && $_GET['xoatam'] > 0) {
        $product_id = intval($_GET['xoatam']);
        $ProductModel->update_product_not_active($product_id);
        $success = '1 sản phẩm đã được thêm vào thùng rác';
    }

    // Khôi phục sản phẩm
    if (isset($_GET['khoiphuc']) && $_GET['khoiphuc'] > 0) {
        $product_id = intval($_GET['khoiphuc']);
        $ProductModel->update_product_active($product_id);
        $success = '1 sản phẩm đã được khôi phục';
    }

    // Xóa vĩnh viễn
    if (isset($_GET['xoa']) && $_GET['xoa'] > 0) {
        $product_id = intval($_GET['xoa']);
        $ProductModel->delete_product($product_id);
        $success = 'Đã xóa thành công 1 sản phẩm';
    }

    // Lấy danh sách trong thùng rác
    $list_products = $ProductModel->select_recycle_products();

    // Thông báo
    $html_alert = $BaseModel->alert_error_success('', $success);
?>

<!-- LIST PRODUCTS -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">
                <a href="index.php?quanli=danh-sach-san-pham" class="link-not-hover">Danh sách sản phẩm</a>
                / Thùng rác
            </h6>
            <a href="index.php?quanli=them-san-pham" class="btn btn-custom">
                <i class="fa fa-plus"></i> Thêm sản phẩm
            </a>
        </div>

        <?php if (count($list_products) > 0) { ?>
            <div class="table-responsive">
                <span class="text-right"><?= $html_alert ?></span>

                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">#</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Giá thường</th>
                            <th scope="col">Giá KM</th>
                            <th scope="col">Chỉnh sửa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 0;
                        foreach ($list_products as $value) {
                            $i++;
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $value['name'] ?></td>
                            <td><img src="../upload/<?= $value['image'] ?>" style="max-width: 50px;" alt=""></td>
                            <td><?= number_format($value['price']) . "đ" ?></td>
                            <td><?= number_format($value['sale_price']) . "đ" ?></td>
                            <td>
                                <a class="btn btn-sm btn-secondary" 
                                   href="index.php?quanli=thung-rac-san-pham&khoiphuc=<?= $value['product_id'] ?>">
                                    <i class="fa fa-undo"></i> Khôi phục
                                </a>
                                <a class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Bạn chắc chắn muốn xóa vĩnh viễn?');" 
                                   href="index.php?quanli=thung-rac-san-pham&xoa=<?= $value['product_id'] ?>">
                                    <i class="fa fa-trash"></i> Xóa
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p class="text-danger">Thùng rác rỗng</p>
        <?php } ?>
    </div>
</div>
<!-- LIST PRODUCTS END -->
