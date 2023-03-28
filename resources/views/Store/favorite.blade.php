@extends('Template.store')
@section('title', 'Claerious - Favorite')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<!-- Hero Section Begin -->
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
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Favorit</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <div class="row">

                    <?php for($i = 0 ; $i < sizeof($data["items"]) ; $i++) { ?>

                        <div class="col-lg-4 col-md-6 col-sm-6 <?= $data['items'][$i]->id_category ?>">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?= $data["items"][$i]->thumbnail ?>">
                                    <ul class="product__item__pic__hover">
                                        <li><a onclick="addToCart(`<?= $data['items'][$i]->id_product ?>`)"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="/product/<?= urlencode($data["items"][$i]->name) ?>"><?= $data["items"][$i]->name ?></a></h6>
                                    <h5>Rp <?= number_format($data["items"][$i]->price,0,",",".") ?></h5>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Section End -->

<script>
    $( document ).ready(function() {

        <?php if (isset($_SESSION["user"])) { ?>

            updateCartCount()
            updateWishlistCount()
            updateFavoriteCount()

        <?php } ?>

    })

    function filter() {
        let minPrice = $("#minPrice").val()
        let maxPrice = $("#maxPrice").val()
        let minStock = $("#minStock").val()
    }
</script>

@endsection