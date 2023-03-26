@extends('Template.store')
@section('title', 'Claerious - Produk')

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
                        <h4>Kategori</h4>
                        <ul>

                            <!-- Categories -->
                            <li><a class="sidebar-category-item" id="category-all" onclick='setCategory(`all`)' style="cursor: pointer;">Semua Kategori</a></li>
                            
                            <?php for($i = 0 ; $i < sizeof($data["categories"]) ; $i++) { ?>

                                <li><a class="sidebar-category-item" id="category-<?= $data["categories"][$i]->id_category ?>" onclick='setCategory(`<?= $data["categories"][$i]->id_category ?>`)' style="cursor: pointer;"><?= $data["categories"][$i]->name ?></a></li>

                            <?php } ?>

                        </ul>
                    </div>
                    <div class="sidebar__item">
                        <h4>Harga</h4>
                        <div class="price-range-wrap">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Minimal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="minPrice-addon" style="font-weight: 600;">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" id="minPrice" aria-describedby="minPrice-addon1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12 mb-0">
                                    <label>Maksimal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="maxPrice-addon" style="font-weight: 600;">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" id="maxPrice" aria-describedby="maxPrice-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__item">
                        <h4>Stok</h4>
                        <div class="price-range-wrap">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Minimal</label>
                                    <input type="text" class="form-control form-control-user" id="minStock">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__item">
                        <div class="row justify-content-end" style="padding: 0 0.75em">
                            <button type="button" class="btn btn-primary" onclick="filter()">Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <div class="filter__item pt-0" style="border-top: none;">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="filter__sort">
                                <span>Urutkan</span>
                                <select id="sortOption">
                                    <option value="0">Paling Sesuai</option>
                                    <option value="1">Harga Terendah</option>
                                    <option value="2">Harga Tertinggi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5"></div>
                        <div class="col-lg-4 col-md-4">
                            <div class="filter__found">
                                <h6><span id="productCount"><?= sizeof($data["items"]) ?></span> Produk ditemukan</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="productList">

                    <?php for($i = 0 ; $i < sizeof($data["items"]) ; $i++) { ?>

                        <div class="col-lg-4 col-md-6 col-sm-6 <?= $data['items'][$i]->id_category ?>">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?= $data["items"][$i]->thumbnail ?>">
                                    <ul class="product__item__pic__hover">
                                        <li><a onclick="addWishlist(`<?= $data['items'][$i]->id_product ?>`)"><i class="fa fa-heart"></i></a></li>
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
    let category = "", products = JSON.parse(`<?= $data['items'] ?>`)

    function setCategory(selectedCategory) {
        if (selectedCategory == "all") {
            category = null
        } else {
            category = selectedCategory
        }

        let categoriesElm = $(".sidebar-category-item")
        // Reset style
        categoriesElm.each(function (index) {
            $(this).css("font-weight", "400")
            $(this).css("color", "#1c1c1c")
        })
        // Set selected category style
        $("#category-" + selectedCategory).css("font-weight", "700")
        $("#category-" + selectedCategory).css("color", "#4179e8")
    }

    function filter() {
        // Reset sort option
        $("#sortOption").val(0)
        $('select').niceSelect('update');

        let minPrice = $("#minPrice").val() == "" ? 0 : $("#minPrice").val().replace(/\./g, '')
        let maxPrice = $("#maxPrice").val() == "" ? 0 : $("#maxPrice").val().replace(/\./g, '')
        let minStock = $("#minStock").val() == "" ? 0 : $("#minStock").val().replace(/\./g, '')

        $.ajax({
            data: {
                id_category: category,
                min_price: minPrice,
                max_price: maxPrice,
                min_stock: minStock
            },
            url: "{{ url('product/filter') }}",
            method: 'POST',
            success: function(result) {
                products = result.message

                $("#productCount").html(products.length)
                showList(products)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    $("#sortOption").on("change", function() {
        console.log(products)
        
        // Sort By
        if ($("#sortOption").val() == 0) {          // Paling Sesuai
            products = products.sort((a, b) => {
                if (a.id < b.id) {
                    return -1;
                }
            })

            showList(products)
        } else if ($("#sortOption").val() == 1) {   // Harga Terendah
            products = products.sort((a, b) => {
                if (a.price < b.price) {
                    return -1;
                }
            })

            showList(products)
        } else {                                    // Harga Tertinggi
            products = products.sort((a, b) => {
                if (a.price > b.price) {
                    return -1;
                }
            })

            showList(products)
        }
    })

    function showList(productList) {
        let html = ``
        for(let i = 0 ; i < productList.length ; i++) {
            html += `<div class="col-lg-4 col-md-6 col-sm-6 ${productList[i]["id_category"]}">
                        <div class="product__item">
                            <div class="product__item__pic set-bg" data-setbg="${productList[i]["thumbnail"]}" style="background-image: url('${productList[i]["thumbnail"]}')">
                                <ul class="product__item__pic__hover">
                                    <li><a onclick="addWishlist('${productList[i]["id_product"]}')"><i class="fa fa-heart"></i></a></li>
                                    <li><a onclick="addToCart('${productList[i]["id_product"]}')"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <div class="product__item__text">
                                <h6><a href="/product/${encodeURIComponent(productList[i]["name"])}">${productList[i]["name"]}</a></h6>
                                <h5>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(productList[i]["price"])}</h5>
                            </div>
                        </div>
                    </div>`
        }

        $("#productList").html(html)
    }

    $("#minPrice").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#minPrice").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })
    $("#maxPrice").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#maxPrice").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })
    $("#minStock").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#minStock").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })
</script>

@endsection