<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded-top p-4">
        <div class="row">
            <div class="col-12 col-sm-6 text-center text-sm-start">
                &copy; <a href="#"></a>, All Right Reserved.
            </div>
            <div class="col-12 col-sm-6 text-center text-sm-end">
                <br>
                <a class="border-bottom" href="#1" target="_blank"></a>
            </div>
        </div>
    </div>
</div>
</div>
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="public_admin/lib/chart/chart.min.js"></script>
<script src="public_admin/lib/easing/easing.min.js"></script>
<script src="public_admin/lib/waypoints/waypoints.min.js"></script>
<script src="public_admin/lib/owlcarousel/owl.carousel.min.js"></script>
<script src="public_admin/lib/tempusdominus/js/moment.min.js"></script>
<script src="public_admin/lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="public_admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="public_admin/lib/tempusdominus/js/domain.js"></script>
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<script>
// Quản lý DataTables
const tables = ['#categories-list', '#orders-list', '#comments-list', '#post-list', '#users-list', '#khohang-list'];
tables.forEach(id => {
    if (document.querySelector(id)) {
        new DataTable(id, {
            responsive: true,
            searchable: true,
            fixedHeight: false,
            lengthMenu: [5, 10, 15, 20, 25],
            pageLength: 5
        });
    }
});

// KHỞI TẠO CKEDITOR AN TOÀN - FIX LỖI "null to object"
function initEditor(selector) {
    const element = document.querySelector(selector);
    if (element) {
        ClassicEditor
            .create(element)
            .then(editor => { console.log('Đã nạp Editor:', selector); })
            .catch(error => { console.warn('CKEditor lỗi nhẹ:', error); });
    }
}

// Chỉ chạy khi tìm thấy đúng ô textarea trên trang
initEditor('#short_description');
initEditor('#product_details');
</script>

<script>
function confirmDeletion() {
    return confirm("Bạn có chắc muốn xóa? Sau khi xóa sẽ không thể khôi phục!");
}

function confirmDeletionTemp() {
    return confirm("Bạn có chắc muốn đưa sản phẩm vào thùng rác?");
}
</script>

<script src="public_admin/js/main.js"></script>
</body>
</html>