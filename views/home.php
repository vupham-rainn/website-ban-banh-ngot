<?php
    $listProducts = $ProductModel->select_products_limit(8);

    $listCategories = $CategoryModel->select_categories_limit(8);

    $product_limit_3 = $ProductModel->select_products_limit(3);
    $product_order_by = $ProductModel->select_products_order_by(3, 'ASC');
?>

<!-- Banner Section Begin -->
<section class="container my-3">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div id="header-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" style="border-radius: 10px;">
                    <div class="carousel-item active">
                        <img class="img-fluid" src="upload/banner/Banner-6.jpg" alt="Image">

                    </div>
                    <div class="carousel-item">
                        <img class="img-fluid" src="upload/banner/banner-7.jpg" alt="Image">

                    </div>
                    <div class="carousel-item">
                        <img class="img-fluid" src="upload/banner/banner-8.jpg" alt="Image">

                    </div>
                </div>
                <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                    <div class="btn btn-dark" style="width: 45px; height: 45px;">
                        <span class="carousel-control-prev-icon mb-n2"></span>
                    </div>
                </a>
                <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                    <div class="btn btn-dark" style="width: 45px; height: 45px;">
                        <span class="carousel-control-next-icon mb-n2"></span>
                    </div>
                </a>

            </div>
        </div>
        <!-- <div class="col-lg-4">
                <div class="product-offer" >
                    <img class="img-fluid"src="upload/banner_quanao_main4.png" alt="">
                    
                </div>
                <div class="product-offer">
                    <img class="img-fluid" src="upload/banner_quanao_main5.png" alt="">
                    
                </div>
            </div> -->
    </div>
</section>
<!-- Banner Section End -->


<!-- Product Section Begin -->
<section class="product spad" style="background-color: #F4F4F9;">

<style>
    .cate-home {
        padding: 25px;
    }

    .cate-gory {
        cursor: pointer;
        transition: 0.25s ease;
    }

    /* Ảnh danh mục bằng nhau */
    .cate-gory img {
        width: 120px !important;
        height: 120px !important;
        border-radius: 12px;
        object-fit: cover;
        object-position: center;
        display: block;
        margin: 0 auto;
        transition: 0.25s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .cate-gory:hover img {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.18);
    }
</style>
    <section class="container cate-home" style="background-color: #ffffff; border-radius: 10px;">

        <div class="section-title pt-2" style="margin-bottom: 30px;">
            <h4>Danh mục sản phẩm</h4>
        </div>

        <div class="row g-1 mb-4 mt-2 pb-4">
            <?php foreach ($listCategories as $value) {
                extract($value);
                $link = 'index.php?url=danh-muc-san-pham&id=' .$category_id;
            ?>
            <div class="col-lg-2 col-md-3 col-sm-6 text-center p-1 cate-gory">
                <a href="<?=$link?>"><img style="width: 50%;" src="upload/<?=$image?>" alt=""></a>
                <div class="mt-2">
                    <a class="cate-name text-dark" href="<?=$link?>"><?=$name?></a>
                </div>
            </div>

            <?php
            }
            ?>


        </div>
    </section>
    <!-- CATE END-->


    <div class="container" style="background-color: #ffffff; border-radius: 10px;">

        <div class="row pt-3">
            <div class="col-lg-4 col-md-4">
                <div class="section-title">
                    <h4>Sản phẩm</h4>
                </div>
            </div>

        </div>
        <div class="row property__gallery">
            <?php foreach ($listProducts as $product) {
                extract($product);

                $discount_percentage = $ProductModel->discount_percentage($price, $sale_price);
            ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mix sach-1">
                <div class="product__item sale">
                    <div class="product__item__pic set-bg" data-setbg="upload/<?=$image?>">
                        <!-- <div class="label sale">Sale</div> -->
                        <div class="label_right sale">-<?=$discount_percentage?></div>
                        <ul class="product__hover">
                            <li><a href="upload/<?=$image?>" class="image-popup"><span class="arrow_expand"></span></a>
                            </li>
                            <li>
                                <a href="index.php?url=chitietsanpham&id_sp=<?=$product_id?>&id_dm=<?=$category_id?>">
                                    <span class="icon_search_alt"></span>
                                </a>
                            </li>


                            <li>
                                <?php if(isset($_SESSION['user'])) {?>
                                <form action="index.php?url=gio-hang" method="post">
                                    <input value="<?=$product_id?>" type="hidden" name="product_id">
                                    <input value="<?=$_SESSION['user']['id']?>" type="hidden" name="user_id">
                                    <input value="<?=$name?>" type="hidden" name="name">
                                    <input value="<?=$image?>" type="hidden" name="image">
                                    <input value="<?=$sale_price?>" type="hidden" name="price">
                                    <input value="1" type="hidden" name="product_quantity">
                                    <input value="<?=$image?>" type="hidden" name="image">

                                    <button type="submit" name="add_to_cart" id="toastr-success-top-right">
                                        <a href="#"><span class="icon_bag_alt"></span></a>
                                    </button>
                                </form>
                                <?php }else{?>
                                <button type="submit" onclick="alert('Vui lòng dăng nhập để thực hiện chức năng');"
                                    name="add_to_cart" id="toastr-success-top-right">
                                    <a href="dang-nhap"><span class="icon_bag_alt"></span></a>
                                </button>
                                <?php }?>
                            </li>

                        </ul>

                    </div>
                    <div class="product__item__text">
                        <h6 class="text-truncate-1"><a href=""><?=$name?></a></h6>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="product__price"><?=number_format($sale_price) ."₫"?>
                            <span><?=number_format($price)."đ"?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
            } 
            ?>



            <div class="col-lg-12 text-center mb-4">
                <a href="index.php?url=cua-hang" class="btn btn-outline-primary">Xem tất cả</a>
            </div>
        </div>

    </div>




</section>
<style>
.banner-fixed {
    width: 100%;
    height: 400px;
    background: url("upload/banner/banner-tet3.jpg") center/cover no-repeat;
    border-radius: 15px;
    margin: 40px 0;
}
</style>
<!-- Banner Section Begin -->
<section class="banner-fixed">
<div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8 m-auto">
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->

<!-- Trend Section Begin -->
<style>
.trend__item__pic {
    width: 90px;          /* khung vuông */
    height: 90px;
    overflow: hidden;
    border-radius: 8px;   /* bo góc nhẹ, đẹp */
    display: flex;
    justify-content: center;
    align-items: center;
}

.trend__item__pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;    /* quan trọng: giúp ảnh fill khung mà không méo */
    display: block;
}
</style>
<section class="trend spad">
    <div class="container">
        <div class="row">

            <!-- XU HƯỚNG -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Xu hướng</h4>
                    </div>

                    <?php foreach ($product_limit_3 as $value) { extract($value); ?>
                    <div class="trend__item">

                        <div class="trend__item__pic">
                            <a href="chitietsanpham&id_sp=<?= $product_id ?>&id_dm=<?= $category_id ?>">
                                <img src="upload/<?= $image ?>" alt="">
                            </a>
                        </div>

                        <div class="trend__item__text">
                            <h6>
                                <a href="chitietsanpham&id_sp=<?= $product_id ?>&id_dm=<?= $category_id ?>"
                                   class="text-dark"><?= $name ?></a>
                            </h6>

                            <div class="rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                            </div>

                            <div class="product__price"><?= number_format($sale_price) ?>₫</div>
                        </div>

                    </div>
                    <?php } ?>

                </div>
            </div>

            <!-- BÁN CHẠY -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Bán chạy</h4>
                    </div>

                    <?php foreach ($product_order_by as $value) { extract($value); ?>
                    <div class="trend__item">

                        <div class="trend__item__pic">
                            <a href="chitietsanpham&id_sp=<?= $product_id ?>&id_dm=<?= $category_id ?>">
                                <img src="upload/<?= $image ?>" alt="">
                            </a>
                        </div>

                        <div class="trend__item__text">
                            <h6>
                                <a href="chitietsanpham&id_sp=<?= $product_id ?>&id_dm=<?= $category_id ?>"
                                   class="text-dark"><?= $name ?></a>
                            </h6>

                            <div class="rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                            </div>

                            <div class="product__price"><?= number_format($sale_price) ?>₫</div>
                        </div>

                    </div>
                    <?php } ?>

                </div>
            </div>

            <!-- HOT SALE -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="trend__content">
                    <div class="section-title">
                        <h4>Hot Sale</h4>
                    </div>

                    <?php foreach ($product_limit_3 as $value) { extract($value); ?>
                    <div class="trend__item">

                        <div class="trend__item__pic">
                            <a href="chitietsanpham&id_sp=<?= $product_id ?>&id_dm=<?= $category_id ?>">
                                <img src="upload/<?= $image ?>" alt="">
                            </a>
                        </div>

                        <div class="trend__item__text">
                            <h6>
                                <a href="chitietsanpham&id_sp=<?= $product_id ?>&id_dm=<?= $category_id ?>"
                                   class="text-dark"><?= $name ?></a>
                            </h6>

                            <div class="rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                            </div>

                            <div class="product__price"><?= number_format($sale_price) ?>₫</div>
                        </div>

                    </div>
                    <?php } ?>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Trend Section End -->

<!-- Discount Section Begin -->

<section class="discount">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="discount__pic">
                    <img src="upload/banner/banner-tiembanh.jpg" alt="Hình ảnh">
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="discount__text">
                    <div class="discount__text__title">
                        <span>Khuyến mãi</span>
                        <h2>12 - 12</h2>
                        <h5><span>Sale</span> 20%</h5>
                    </div>
                    <div class="discount__countdown" id="countdown-time">
                        <div class="countdown__item">
                            <span>05</span>
                            <p>Ngày</p>
                        </div>
                        <div class="countdown__item">
                            <span>18</span>
                            <p>Giờ</p>
                        </div>
                        <div class="countdown__item">
                            <span>46</span>
                            <p>Phút</p>
                        </div>
                        <div class="countdown__item">
                            <span>05</span>
                            <p>Giây</p>
                        </div>
                    </div>
                    <a href="#">Mua ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Discount Section End -->

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-car"></i>
                    <h6>Miễn phí vận chuyển</h6>
                    <p>Đơn hàng trên 400.000đ</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-money"></i>
                    <h6>Đảm bảo hoàn tiền</h6>
                    <p>Nếu sản phẩm có vấn đề</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-support"></i>
                    <h6>Hỗ trợ trực tuyến 24/7</h6>
                    <p>Hỗ trợ chuyên dụng</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-headphones"></i>
                    <h6>Thanh toán an toàn</h6>
                    <p>Thanh toán an toàn 100%</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CHATBOX HTML -->
<div id="chat-toggle">💬</div>

<div id="chatbox" class="hidden">
    <div id="chat-header">
        Hỗ trợ khách hàng 🍰
        <span id="close-chat">×</span>
    </div>

    <div id="chat-messages"></div>

    <div id="chat-input">
        <input id="userInput" type="text" placeholder="Nhập câu hỏi...">
        <button onclick="sendMessage()">Gửi</button>
    </div>
</div>

<!-- CHATBOX CSS -->
<style>
/* --- Nút chat bong bóng --- */
#chat-toggle {
    width: 60px;
    height: 60px;
    background: #ff5f8c;
    color: white;
    border-radius: 50%;
    position: fixed;
    bottom: 25px;
    right: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 26px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    z-index: 9999;
    transition: 0.3s;
}
#chat-toggle:hover {
    transform: scale(1.1);
}

#chatbox {
    width: 340px;
    height: 460px;
    background: white;
    border-radius: 14px;
    position: fixed;
    bottom: 100px;
    right: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
}
.hidden {
    display: none !important;
}
#chat-header {
    background: #ff5f8c;
    color: white;
    padding: 12px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
#close-chat {
    cursor: pointer;
    font-size: 20px;
    z-index: 99999999 !important;
    position: relative !important;
}

#chat-messages {
    flex: 1;
    padding: 12px;
    overflow-y: auto;
    font-size: 14px;
}

#chat-input {
    display: flex;
    align-items: center;
    border-top: 1px solid #eee;
    padding: 8px;
    background: #fff;
}

#chat-input input {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    font-size: 14px;
}

#chat-input button {
    margin-left: 10px;
    padding: 10px 14px;
    background: #ff5f8c;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.2s;
}

#chat-input button:hover {
    background: #ff3c6d;
}
</style>

<!-- CHATBOX SCRIPT -->
<script>
const API_KEY = "AIzaSyB2k7Gu_zkcXn9esOZMxp-iiEMRw-C-bd4";

const bakeryData = `
Tiệm Bánh Ngọt Ngào.
Địa chỉ: 123 Nguyễn Trãi, Q.5, TP.HCM.
Giờ mở cửa: 8h - 21h.
Chuyên bánh kem, mousse, tiramisu.
Có giao hàng nội thành từ 20.000đ.
Hotline: 0912 345 678.
`;

document.addEventListener("DOMContentLoaded", () => {

const toggleBtn = document.getElementById("chat-toggle");
const closeBtn = document.getElementById("close-chat");
const chatbox = document.getElementById("chatbox");

// Mở chat
toggleBtn.addEventListener("click", () => {
    chatbox.classList.remove("hidden");
});

// Đóng chat
closeBtn.addEventListener("click", () => {
    chatbox.classList.add("hidden");
});

});

// Gửi tin nhắn
async function sendMessage() {
    let input = document.getElementById("userInput");
    let msg = input.value.trim();
    if (!msg) return;

    addMessage("Bạn", msg);
    input.value = "";

    let reply = await callGemini(msg);
    addMessage("Tiệm bánh", reply);
}

// In tin nhắn
function addMessage(sender, text) {
    let box = document.getElementById("chat-messages");
    box.innerHTML += `<p><strong>${sender}:</strong> ${text}</p>`;
    box.scrollTop = box.scrollHeight;
}

// Gọi API Gemini
async function callGemini(userMessage) {

// 1. Lấy dữ liệu từ MySQL qua API PHP
const bakeryData = await fetch("http://localhost/webbanbanh/models/getBakeryData.php")
    .then(res => res.json());

// 2. Biến dữ liệu thành văn bản AI dễ hiểu
const prompt = `
Bạn là chatbot của ${bakeryData.store.name}.

THÔNG TIN TIỆM:
- Địa chỉ: ${bakeryData.store.address}
- Giờ mở cửa: ${bakeryData.store.open_hours}
- Hotline: ${bakeryData.store.hotline}
- Giới thiệu: ${bakeryData.store.description}

DANH MỤC:
${bakeryData.categories.map(c => "- " + c.name).join("\n")}

SẢN PHẨM HIỆN CÓ:
${bakeryData.products.map(p => 
`• ${p.name} - Giá: ${p.sale_price ?? p.price}đ
Mô tả: ${p.short_description}`
).join("\n\n")}

YÊU CẦU:
- Trả lời ngắn gọn, thân thiện
- Ưu tiên câu trả lời dựa TRỰC TIẾP vào thông tin trên
- Nếu khách hỏi sản phẩm → trả đúng giá + mô tả
- Nếu khách hỏi về cửa hàng → trả thông tin phía trên

Câu hỏi khách: "${userMessage}"
`;

// 3. Gửi sang Gemini
const res = await fetch(
    `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${API_KEY}`,
    {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            contents: [{ role: "user", parts: [{ text: prompt }] }]
        })
    }
);

const data = await res.json();
console.log("AI trả về:", data);

return (
    data?.candidates?.[0]?.content?.parts?.[0]?.text ||
    "Xin lỗi, mình chưa hiểu câu hỏi của bạn!"
);
}

</script>
<!-- Services Section End -->