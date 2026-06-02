<?php require "header_new.php"; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {

    const input = document.getElementById("searchInput");

    input.addEventListener("keyup", function() {

        let keyword = this.value.toLowerCase();

        let products = document.querySelectorAll(".pm-product-card");

        products.forEach(function(product) {

            let name = product.querySelector(".pm-product-name")
                              .textContent
                              .toLowerCase();

            if (name.includes(keyword)) {
                product.style.display = "";
            } else {
                product.style.display = "none";
            }

        });

    });

});
</script>
</section>
<div id="breadcrumbs">
    <div class="ctn">
        <div id="crumbs"><a href="<?php echo XC_URL; ?>">Trang chủ</a> <span>/</span> <a class="current">Nhà thuốc</a></div>
    </div>
</div>
<div class="ctn">
    <div class="row">
        <div id="main" class="col-12 clg-12">
            <div class="product-management-wrapper">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                <link rel="stylesheet" href="<?php echo $template_path; ?>/assets/themes/stylepr.css">


                <div class="pm-container">
                    <div class="pm-mobile-category">
                        <div class="pm-mobile-select" onclick="toggleMobileMenu()">
                            <span><i class="fa-solid fa-list-ul"></i> DANH MỤC THUỐC</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="pm-mobile-dropdown-content" id="mobileCatMenu">
                            <a href="<?php echo XC_URL ?>/nha-thuoc.html" class="<?php echo ($category == 'category') ? "" : "active";?>">Tất cả sản phẩm</a>
                            <?php foreach ($product_category as $item) { ?>
                                <a href="<?php echo $this->helper->permalink($item->id,'category_product');?>" class="<?php echo ($item->id == $id_category) ? "active" : "";?>" ><?php echo $item->category_name; ?></a>
                            <?php } ?>
                        </div>
                    </div>

                    <aside class="pm-sidebar">
                        <div class="pm-sidebar-card">
                            <h3 style="margin:0px 0px 10px 0px"><i class="fa-solid fa-list-ul"></i> Danh mục thuốc</h3>
                            <ul class="pm-category-list">
                                <li><a href="<?php echo XC_URL ?>/nha-thuoc.html" class="<?php echo ($category == 'category') ? "" : "active";?>"><i class="fa-solid fa-box"></i> Tất cả sản phẩm</a></li>
                                <?php foreach ($product_category as $item) { ?>
                                    <li ><a class="<?php echo ($item->id == $id_category) ? "active" : "";?>" href="<?php echo $this->helper->permalink($item->id,'category_product'); ?>"><i class="<?php echo $item->category_icon; ?>"></i><?php echo $item->category_name; ?></a></li>

                                <?php } ?>
                            </ul>
                        </div>
                    </aside>

                    <main class="pm-main-content">
                        <div class="pm-header-tools">
                            <h2 style="color: var(--pm-primary)"><?php echo $category_fil->category_name; ?></h2>
                            <div class="pm-search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" placeholder="Tìm kiếm sản phẩm..." id='searchInput'>
                            </div>
                        </div>

                        <div class="pm-product-grid">
                            <?php foreach ($products as $product) { ?>
                                <div class="pm-product-card">
                                    <?php if ($product->product_discount != 0) { ?>
                                        <div class="pm-discount-tag">-<?php echo (int) $product->product_discount; ?>%</div>
                                    <?php } else {
                                        echo '';
                                    } ?>
                                    <div class="pm-img-container">
                                        <img src="<?php echo XC_URL; ?>/uploads/products/<?php echo $product->product_image; ?>"
                                            alt="<?php echo $product->product_name; ?>">
                                    </div>
                                    <div>
                                        <h4 class="pm-product-name"><?php echo $product->product_name; ?></h4>
                                        <span class="pm-price-old"><?php
                                                                    echo ($product->product_discount != 0) ?
                                                                        number_format($product->product_price, 0, ',', '.') . 'đ' : '';

                                                                    ?></span>
                                        <span class="pm-price-new"><?php
                                                                    $price = (float)$product->product_price;
                                                                    $discount = (int)$product->product_discount;

                                                                    $price_after = $price * (100 - $discount) / 100;
                                                                    echo number_format($price_after, 0, ',', '.') . 'đ';

                                                                    ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </main>
                </div>

                <script>
                    function toggleMobileMenu() {
                        const menu = document.getElementById('mobileCatMenu');
                        menu.classList.toggle('show');
                    }

                    // Đóng dropdown khi click ra ngoài
                    window.onclick = function(event) {
                        if (!event.target.closest('.pm-mobile-category')) {
                            const dropdowns = document.getElementsByClassName("pm-mobile-dropdown-content");
                            for (let i = 0; i < dropdowns.length; i++) {
                                dropdowns[i].classList.remove('show');
                            }
                        }
                    }
                </script>

                    

            </div>
            <div class="pagination" style="margin-top:10px;">
                        <div class="paginate_links">
                            <div class="paginate_links">
                                <?php
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    if ($i == $page) {
                                        echo " <span aria-current='page' class='page-numbers current'>$i</span>";
                                    } else {
                                        echo "<a class='page-numbers' href='".XC_URL."/nha-thuoc/page/$i.html'>$i</a>";
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
        </div>
        </article>
        
    </div>
</div>
</div>
</main>
<?php require "footer_new.php"; ?>