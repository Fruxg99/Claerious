@extends('Template.store')
@section('title', 'Claerious - Group Buy')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<!-- Hero Section Begin -->
<section class="hero">
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

                            <li><a href="/product?category=<?= $data["categories"][$i]->id_category ?>"><?= $data["categories"][$i]->name ?></a></li>

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
                <div class="hero__item set-bg" data-setbg="https://static.vecteezy.com/system/resources/previews/006/123/105/original/flash-sale-banner-templete-design-with-dynamic-color-gradient-for-media-promotions-free-vector.jpg">
                    <!-- <div class="hero__text">
                        <span>FRUIT FRESH</span>
                        <h2>Vegetable <br />100% Organic</h2>
                        <p>Free Pickup and Delivery Available</p>
                        <a href="#" class="primary-btn">SHOP NOW</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Categories Section Begin -->
<section class="categories">
    <div class="container">
        <div class="row">
            <div class="categories__slider owl-carousel">

                <?php for($i = 0 ; $i < sizeof($data["categories"]) ; $i++) { ?>

                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="<?= $data["categories"][$i]->thumbnail ?>">
                            <h5><a href="/product?category=<?= $data["categories"][$i]->id_category ?>"><?= $data["categories"][$i]->name ?></a></h5>
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</section>
<!-- Categories Section End -->

<!-- Featured Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Produk Unggulan</h2>
                </div>
                <div class="featured__controls">
                    <ul>
                        <li class="active" data-filter="*">Semua</li>

                        <?php for($i = 0 ; $i < sizeof($data["categories"]) ; $i++) { ?>

                            <li data-filter=".<?= $data["categories"][$i]->id_category ?>"><?= $data["categories"][$i]->name ?></li>

                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter">

            <?php for($i = 0 ; $i < sizeof($data["items"]) ; $i++) { ?>

                <div class="col-lg-3 col-md-4 col-sm-6 mix <?= $data['items'][$i]->id_category ?>">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="<?= $data["items"][$i]->thumbnail ?>">
                            <ul class="featured__item__pic__hover">
                                <li><a onclick="addWishlist(`<?= $data['items'][$i]->id_product ?>`)"><i class="fa fa-heart"></i></a></li>
                                <li><a onclick="addToCart(`<?= $data['items'][$i]->id_product ?>`)"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a href="/product/<?= urlencode($data["items"][$i]->name) ?>"><?= $data["items"][$i]->name ?></a></h6>
                            <h5>Rp <?= number_format($data["items"][$i]->price,0,",",".") ?></h5>
                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>
    </div>
</section>
<!-- Featured Section End -->

<!-- Latest Product Section Begin -->
<section class="latest-product spad pt-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="latest-product__text">
                    <h4>Produk Terbaru</h4>
                    <div class="latest-product__slider owl-carousel">

                        <?php for($i = 0 ; $i < sizeof($data["recent_items"]) ; $i++) { ?>
                            <?php if ($i == 0 || $i == 3 || $i == 6) { ?>

                                <div class="latest-prdouct__slider__item">

                            <?php } ?>

                                    <a href="/product/<?= urlencode($data["recent_items"][$i]->name) ?>" class="latest-product__item">
                                        <div class="latest-product__item__pic">
                                            <img src="<?= $data["recent_items"][$i]->thumbnail ?>" alt="">
                                        </div>
                                        <div class="latest-product__item__text">
                                            <h6><?= $data["recent_items"][$i]->name ?></h6>
                                            <span>Rp <?= number_format($data["recent_items"][$i]->price,0,",",".") ?></span>
                                        </div>
                                    </a>

                            <?php if ($i == 2 || $i == 5 || $i == 8) { ?>

                                </div>

                            <?php } ?>
                        <?php } ?>

                </div>
            </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="latest-product__text">
                    <h4>Produk Ulasan Terbaik</h4>
                    <div class="latest-product__slider owl-carousel">

                        <?php for($i = 0 ; $i < sizeof($data["top_rated"]) ; $i++) { ?>
                            <?php if ($i == 0 || $i == 3 || $i == 6) { ?>

                                <div class="latest-prdouct__slider__item">

                            <?php } ?>

                                <a href="/product/<?= urlencode($data["top_rated"][$i]->name) ?>" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="<?= $data["top_rated"][$i]->thumbnail ?>" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6><?= $data["top_rated"][$i]->name ?></h6>
                                        <span>Rp <?= number_format($data["top_rated"][$i]->price,0,",",".") ?></span>
                                    </div>
                                </a>

                            <?php if ($i == 2 || $i == 5 || $i == 8) { ?>

                                </div>

                            <?php } ?>
                        <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="latest-product__text">
                    <h4>Produk Terlaris</h4>
                    <div class="latest-product__slider owl-carousel">

                        <?php for($i = 0 ; $i < sizeof($data["top_review"]) ; $i++) { ?>
                            <?php if ($i == 0 || $i == 3 || $i == 6) { ?>

                                <div class="latest-prdouct__slider__item">

                            <?php } ?>

                                <a href="/product/<?= urlencode($data["top_review"][$i]->name) ?>" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="<?= $data["top_review"][$i]->thumbnail ?>" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6><?= $data["top_review"][$i]->name ?></h6>
                                        <span>Rp <?= number_format($data["top_review"][$i]->price,0,",",".") ?></span>
                                    </div>
                                </a>

                            <?php if ($i == 2 || $i == 5 || $i == 8) { ?>

                                </div>

                            <?php } ?>

                        <?php } ?>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest Product Section End -->

<script>
    $( document ).ready(function() {

        <?php if (isset($_SESSION["user"])) { ?>

            updateCartCount()
            updateWishlistCount()
            updateFavoriteCount()

        <?php } ?>

    })
</script>

@endsection