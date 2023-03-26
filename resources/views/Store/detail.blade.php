@extends('Template.store')
@section('title', 'Claerious - Detail Produk')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<section class="hero hero-normal">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Kategori</span>
                    </div>
                    <ul>

                        <!-- Categories -->
                        <?php for($i = 0 ; $i < sizeof($data["categories"]) ; $i++) { ?>

                            <li><a href="/product?category=<?= urlencode($data["categories"][$i]->name) ?>"><?= $data["categories"][$i]->name ?></a></li>

                        <?php } ?>

                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form" style="width: 100%;">
                        <form action="{{ url('/product') }}" method="POST">
                            @csrf
                            <input type="text" placeholder="Cari nama produk" name="search" id="search" value="<?= $data['search'] ?>">
                            <button type="submit" class="site-btn">CARI</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <img class="product__details__pic__item--large"
                            src="<?= $data['item']->thumbnail ?>" alt="">
                    </div>
                    <div class="product__details__pic__slider owl-carousel">
                        <img data-imgbigurl="<?= $data['item']->thumbnail ?>"
                            src="<?= $data['item']->thumbnail ?>" alt="">
                        <img data-imgbigurl="<?= $data['item']->thumbnail ?>"
                            src="<?= $data['item']->thumbnail ?>" alt="">
                        <img data-imgbigurl="<?= $data['item']->thumbnail ?>"
                            src="<?= $data['item']->thumbnail ?>" alt="">
                        <img data-imgbigurl="<?= $data['item']->thumbnail ?>"
                            src="<?= $data['item']->thumbnail ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?= $data["item"]->name ?></h3>

                    <div class="product__details__rating">

                        <!-- Rating -->
                        <?php if ($data["item"]->rating_count > 0) { ?>

                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <span>(<?= number_format($data["item"]->rating_count) ?> ulasan)</span>

                        <?php } else { ?>

                            <span class="m-0">Belum terulas</span>

                        <?php } ?>

                    </div>

                    <div class="product__details__price">Rp <?= number_format($data["item"]->price,0,",",".") ?></div>
                    <div class="product__details__quantity">
                        <div class="quantity">
                            <div class="pro-qty">
                                <input type="text" value="1" id="productQty">
                            </div>
                        </div>
                    </div>
                    <button class="primary-btn" onclick='addToCart(`<?= $data["item"]->id_product ?>`)'>Tambah ke keranjang</button>
                    <button class="heart-icon" onclick='addWishlist(`<?= $data["item"]->id_product ?>`)' id="iconWishlist"><i class="fa-regular fa-heart"></i></button>
                    <button class="heart-icon" onclick='addFavorite(`<?= $data["item"]->id_product ?>`)' id="iconFavorite"><i class="fa-regular fa-star"></i></i></button>
                    <ul>
                        <li><b>Ketersediaan</b> <span><?= $data["item"]->stock ?> tersisa</span></li>
                        <li><b>Berat</b> <span><?= number_format($data["item"]->weight) ?> gram</span></li>
                        <li><b>Bagikan</b>
                            <div class="share">
                                <a style="cursor: pointer;" onclick="shareWhatsapp()"><i class="fa fa-whatsapp"></i></a>
                                <a style="cursor: pointer;" onclick="copyLink()"><i class="fa-solid fa-link"></i></a>
                            </div>
                        </li>
                        <li class="product__details__store"><h3 style="font-size: 24px; margin: 0;">Penjual</h3></li>
                        <li class="product__details__store" style="border: none; padding: 0; margin: 0;">
                            <img src="<?= $data["store"]->profile_picture ?>" alt="" class="store__image">
                            <div class="store__details">
                                <div class="store__details__name"><a href="../seller/<?= urlencode($data["store"]->name) ?>/<?= urlencode($data["store"]->id_seller) ?>"><?= $data["store"]->name ?></a></div>
                                <div class="store__details__city text-grey"><?= $data["store"]->city_name ?></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#deskripsi" role="tab"
                                aria-selected="true">Deskripsi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#ulasan" role="tab"
                                aria-selected="false">Ulasan <span>(<?= number_format($data["item"]->rating_count) ?>)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#grup" role="tab"
                                aria-selected="false">Grup</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="deskripsi" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Deskripsi Produk</h6>
                                <p><?= $data["item"]->description ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="ulasan" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Ulasan Produk</h6>
                            </div>
                        </div>
                        <div class="tab-pane" id="grup" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Daftar Grup</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $( document ).ready(function() {

        <?php if (isset($_SESSION["user"])) { ?>

            updateCartCount()
            updateWishlistCount()
            updateFavoriteCount()
            checkWishlist()
            checkFavorite()

        <?php } ?>

    })

    function addFavorite(product_id) {

        <?php if (isset($_SESSION["user"])) { ?>

            let data = {
                product_id: product_id
            }

            $.ajax({
                data: data,
                url: "{{ url('favorite/add-favorite') }}",
                method: 'POST',
                success: function(result) {
                    updateFavoriteCount()

                    if (result.message == "Removed from favorite") {
                        toastr.success("Produk dihapus dari favorite")
                    } else {
                        toastr.success("Produk ditambahkan ke favorite")
                    }

                    // Check if function exist
                    if (typeof checkFavorite === "function") {
                        checkFavorite()
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    toastr.error(thrownError)
                }
            })

        <?php } else { ?>

            $("#loginModal").modal("show")

        <?php } ?>

    }

    function checkWishlist() {
        $.ajax({
            data: {
                id_product: "<?= $data["item"]->id_product ?>"
            },
            url: "{{ url('/wishlist/check-wishlist') }}",
            method: 'POST',
            success: function(result) {
                if (result.message == "wishlisted") {
                    $("#iconWishlist").html(`<i class="fa-solid fa-heart"></i>`)
                } else {
                    $("#iconWishlist").html(`<i class="fa-regular fa-heart"></i>`)
                }
            }
        })
    }

    function checkFavorite() {
        $.ajax({
            data: {
                id_product: "<?= $data["item"]->id_product ?>"
            },
            url: "{{ url('/favorite/check-favorite') }}",
            method: 'POST',
            success: function(result) {
                if (result.message == "favorited") {
                    $("#iconFavorite").html(`<i class="fa-solid fa-star"></i>`)
                } else {
                    $("#iconFavorite").html(`<i class="fa-regular fa-star"></i>`)
                }
            }
        })
    }

    function shareWhatsapp() {
        let text = encodeURIComponent("Cek produk ini!\n\n<?= $data["item"]->name ?>\n\nHanya dengan harga Rp <?= number_format($data["item"]->price,0,",",".") ?> saja kamu sudah bisa mendapatkan produk ini. Untuk selengkapnya cek di link berikut:\n<?= url()->current() ?>")

        window.open(`https://wa.me/?text=${text}`)
    }

    function copyLink() {
        navigator.clipboard.writeText("<?= url()->current() ?>")
        toastr.success("Tautan tersalin")
    }
</script>

@endsection