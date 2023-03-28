@extends('Template.store')
@section('title', 'Claerious - Pembayaran')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Produk</th>
                                <th>Harga</th>
                                <th>Kuantitas</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tableCart">
                            <tr>
                                <td class="shoping__cart__item" colspan="5" style="padding: 10px 0;">
                                    <img src="<?= $data["cart"]["profile_picture"] ?>" alt="" class="store__image">
                                    <h5 style="font-size: 20px; font-weight: 700;"><?= $data["cart"]["seller_name"] ?></h5>
                                </td>
                            </tr>
                                        
                            <tr>
                                <td class="shoping__cart__item">
                                    <img src="<?= $data["cart"]["thumbnail"] ?>" alt="" style="max-width: 100px;">
                                    <h5><?= $data["cart"]["name"] ?></h5>
                                </td>
                                <td class="shoping__cart__price" id="price">
                                    Rp <?= number_format($data["price"],0,",",".") ?>
                                </td>
                                <td class="shoping__cart__quantity">
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <input type="text" value='<?= $data["qty"] ?>' onchange='updateCartQty()' id="qty">
                                        </div>
                                    </div>
                                </td>
                                <td class="shoping__cart__total" id="totalPrice">
                                    Rp <?= number_format($data["price"] * $data["qty"],0,",",".") ?>
                                </td>
                                <input type="hidden" value='<?= $data["groupID"] ?>' id="groupID">
                                <input type="hidden" value='<?= $data["leader"] ?>' id="leaderID">
                                <input type="hidden" value='<?= $data["cart"]["id_seller"] ?>' id="sellerID">
                                <input type="hidden" value='<?= $data["cart"]["id_product"] ?>' id="productID">
                                <input type="hidden" value='<?= $data["cart"]["weight"] ?>' id="weight">
                                <input type="hidden" value='<?= $data["group_price"] ?>' id="groupPriceID">
                            </tr>

                            <tr id="shipment-row">
                                <td class="shoping__cart__item" style="padding: 10px 0;">
                                    <h5 style="font-size: 16px; font-weight:700; text-align: left; margin-bottom: 0.5rem;">Jenis Pengiriman</h5>
                                    <select class="form-select shipment-option" id="shipmentProduct" onchange='updateTotalPrice()'>
                                        <option selected disabled>Pilih Pengiriman</option>
                                    </select>
                                </td>
                                <td class="shoping__cart__price"></td>
                                <td class="shoping__cart__quantity" colspan="3">
                                    <h5 style="font-size: 16px; font-weight:700; text-align: left; margin-bottom: 0.5rem;">Kupon Toko</h5>
                                    <div class="transaction-voucher" id="voucher">
                                        <div class="transaction-voucher-button" data-toggle="modal" data-target="#modalVoucher" onclick='loadVouchers("<?= $data["cart"]["id_seller"] ?>")'><span><i class="fas fa-tags"></i> Lebih hemat pakai kupon</span><i class="fas fa-angle-right font-20"></i></div>
                                    </div>
                                    <input type="hidden" class="discount-voucher" id="discountProduct" value="0">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Kupon Potongan</h5>
                        <form action="#">
                            <input type="text" placeholder="Masukkan Kode Kupon">
                            <button type="submit" class="site-btn">Gunakan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__checkout">
                    <h5>Ringkasan Keranjang</h5>
                    <ul>
                        <li>Subtotal <span id="subtotal"></span></li>
                        <li>Diskon Kupon <span id="discount" class="text-danger"></span></li>
                        <li>Biaya Pengiriman <span id="shipment"></span></li>
                        <li>Biaya Layanan <span id="service">Rp 5.000</span></li>
                        <li>Total <span id="total"></span></li>
                    </ul>
                    <a onclick="checkout()" class="primary-btn" style="cursor: pointer; color: white;">LANJUT KE PEMBAYARAN</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalVoucher" tabindex="-1" aria-labelledby="modalVoucher" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-body">
                <div class="modal-header" style="align-items: center; padding: 0 0 12px 0; border: none;">
                    <h3>Daftar Kupon</h3>
                </div>
                <div class="invalid-feedback" id="errorCoupon" style="font-weight: bold;"></div>
                <div id="listVoucher" class="list-voucher">
                    <h4 style='opacity: 0.5; margin-top: 10px; text-transform: none;'>Toko ini tidak memiliki kupon</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="midtrans-script" type="text/javascript" src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" data-environment="sandbox" data-client-key="SB-Mid-client-IRE0d_nhs4Jp2k9o"></script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= config('midtrans.client_key') ?>"></script>

<script>

    $( document ).ready(function() {

        <?php if (isset($_SESSION["user"])) { ?>

            updateCartCount()
            updateWishlistCount()
            updateFavoriteCount()
            updateTotalPrice()
            loadShipment()

        <?php } ?>

    })

    function loadShipment() {
        let shipments = $("select.shipment-option")
        let div_shipments = $("div.shipment-option")
        let weight = $("#weight").val() * $("#qty").val()
        let id_seller = ""
        let shipment_result = ""
        let address = "A0001"

        shipments.each(function(index, element) {
            let data = {
                id_address: address,
                id_seller: "<?= $data["cart"]["id_seller"] ?>",
                weight: weight
            }

            $.ajax({
                data: data,
                url: "{{ url('shipment/selectBySeller') }}",
                method: 'POST',
                success: function(result) {
                    shipment_result = result

                    // Change bootstrap select
                    let html = `<option value=null>Pilih Pengiriman</option>`
                    let htmlDiv = ``

                    for(let i = 0 ; i < shipment_result.length ; i++) {
                        let courier = shipment_result[i].details[0].costs

                        for(let j = 0 ; j < courier.length ; j++) {
                            html += `<option value="${shipment_result[i].courier}-${courier[j].service}-${new Intl.NumberFormat(undefined, { 
                                        style: 'currency',
                                        currency: 'IDR',
                                        maximumFractionDigits: 0,
                                        minimumFractionDigits: 0,
                                    }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")}">
                                    ${shipment_result[i].courier} - ${courier[j].service} - ${new Intl.NumberFormat(undefined, { 
                                        style: 'currency',
                                        currency: 'IDR',
                                        maximumFractionDigits: 0,
                                        minimumFractionDigits: 0,
                                    }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")}</option>`

                            htmlDiv += `<li data-value="${shipment_result[i].courier}-${courier[j].service}-${new Intl.NumberFormat(undefined, { 
                                        style: 'currency',
                                        currency: 'IDR',
                                        maximumFractionDigits: 0,
                                        minimumFractionDigits: 0,
                                    }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")}" class="option">
                                    ${shipment_result[i].courier} - ${courier[j].service} - ${new Intl.NumberFormat(undefined, { 
                                    style: 'currency',
                                    currency: 'IDR',
                                    maximumFractionDigits: 0,
                                    minimumFractionDigits: 0,
                                }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")} </option>`
                        }
                    }

                    $(element).html(html)

                    $(div_shipments[index]).children("span").html("Pilih Pengiriman")
                    $(div_shipments[index]).children("ul").html(htmlDiv)
                }
            })
        })
    }

    function loadVouchers(id_seller) {
        let vouchers = $(".voucher-option")
        vouchers.each(function(index, element) {
            if ($(element).is('select')) {
                id_seller = $(element).attr("id").split("-")[1]
            }

            let data = {
                id_seller: id_seller
            }

            $.ajax({
                data: data,
                url: "{{ url('/voucher/selectBySeller') }}",
                method: 'POST',
                success: function(result) {
					$("#listVoucher").html('')

                    let html = ``
					for (let i = 0; i < result.length; i++) {
                        html +=
                            `<div class='list-voucher-item'>
                                <div class='list-voucher-content'>
                                    <div class="voucher-content-name" id="coupon-${result[i].id_voucher}"><b>${result[i].name}</b></div>
                                    <div class="voucher-content-snk">Syarat dan Ketentuan</div>
                                    <div class="voucher-snk-list">
                                        <ol style="padding-left: 16px; font-size: 12px; margin: 0;">`

                        if (result[i].type == 0) {
                            // Voucher percentage
                            html += `<li>
                                        <div class="voucher-snk-item">
                                            Diskon ${result[i].discount_percentage}% maksimal ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].max_discount).replace("IDR", "Rp").replace(/,/g, ".")} dengan minimal pembelian senilai ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].min_purchase).replace("IDR", "Rp").replace(/,/g, ".")}.
                                        </div>
                                    </li>`
                        } else if (result[i].type == 1) {
                            // Voucher fixed amount
                            html += `<li>
                                        <div class="voucher-snk-item">
                                            Potongan harga senilai ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].max_discount).replace("IDR", "Rp").replace(/,/g, ".")} dengan minimal pembelian senilai ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].min_purchase).replace("IDR", "Rp").replace(/,/g, ".")}.
                                        </div>
                                    </li>`
                        }

                        if (result[i].usage_limit > 0) {
                            html += `<li>
                                    <div class="voucher-snk-item">
                                        Berlaku untuk ${result[i].usage_limit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} pengguna pertama.
                                    </div>
                                </li>`
                        }

                        html += `
                                    </ol>
                                </div>
                            </div>
                            <div class='list-voucher-action'>
                                <button id='${result[i].id_voucher}' class='btn btn-primary rounded btn-select-voucher' onclick='chooseVoucher(this.id, "${result[i].id_seller}")'>Pilih</button>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid rgba(0,0,0,0.25); margin: 8px 0;">`
                    }

                    if (result.length == 0) {
                        html += `<h4 style='opacity: 0.5;'>Toko ini tidak memiliki kupon</h4>
                                <hr style="border-top: 1px solid rgba(0,0,0,0.25); margin: 8px 0;">`
                        $("#listVoucher").css({overflow: "hidden"})
                    } else {
                        $("#listVoucher").css({overflow: "auto"})
                    }

                    $("#listVoucher").html(html)
                    $("#listVoucher").children().last().remove()
                }
            })
        })
    }

    function chooseVoucher(id_voucher, id_seller) {
        $.ajax({
            type: 'POST',
            url: '{{ url("voucher/selectById") }}',
            data: {
                'id_voucher': id_voucher
            },
            success: function(result) {
                let subtotal = parseInt($("#subtotal").html().split("&nbsp;")[1].replace(/\./g, ''))
                let discount = 0

                if (result.type == 0) {
                    // Voucher percentage
                    if (subtotal > result.min_purchase) {
                        discount = subtotal * result.discount_percentage / 100

                        if (discount > result.max_discount) {
                            discount = result.max_discount
                        }
                    }
                } else if (result.type == 1) {
                    // Voucher fixed amount
                    if (subtotal > result.min_purchase) {
                        discount = result.max_discount
                    }
                }

                $("#discount-" + id_seller).val(discount)

                let html = `<div class="transaction-voucher-button" onclick="cancelVoucher('${id_seller}')"><div><span>Kamu hemat Rp ${discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span><span style="font-size: 12px; font-weight: 400;">Menggunakan kupon ${result.name}</span></div><i class="fas fa-angle-right font-20"></i></div>`
                $("#voucher-" + id_seller).html(html)
                $("#modalVoucher").modal('hide')
                
                updateTotalPrice()
            }
        })
    }

    function cancelVoucher(id_seller) {
        html = `<div class="transaction-voucher-button" data-toggle="modal" data-target="#modalVoucher" onclick='loadVouchers("${id_seller}")'><span><i class="fas fa-tags"></i> Lebih hemat pakai kupon</span><i class="fas fa-angle-right font-20"></i></div>`
        $("#voucher-" + id_seller).html(html)
        $("#discount-" + id_seller).val(0)
        updateTotalPrice()
    }

    function updateCartQty() {
        updateRowPrice()
        loadShipment()
    }

    function updateRowPrice() {
        let price = parseInt($("#price").html().trim().split(" ")[1].replace(".",""))
        let qty = $("#qty").val()
        let totalPrice = qty * price

        $("#totalPrice").html(new Intl.NumberFormat(undefined, { 
            style: 'currency', 
            currency: 'IDR',
            maximumFractionDigits: 0, 
            minimumFractionDigits: 0, 
        }).format(totalPrice).replace("IDR", "Rp").replace(/,/g, "."))

        updateTotalPrice()
    }

    function updateTotalPrice() {
        let discounts = $(".discount-voucher")
        let shipments = $("select.shipment-option")
        let totalPrice = 0, servicePrice = 5000, totalDiscount = 0, totalShipment = 0

        totalPrice = parseInt($("#totalPrice").html().trim().replace("&nbsp;", " ").split(" ")[1].replace(".", ""))

        discounts.each(function(index, element) {
            totalDiscount+= parseInt($(element).val())
        })

        shipments.each(function(index, element) {
            if ($(element).val() != null && $(element).val() != "null") {
                totalShipment += parseInt($(element).val().split("Rp")[1].substr(1).replace(/\./g, ""))
            }
        })

        $("#subtotal").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(totalPrice).replace("IDR", "Rp").replace(/,/g, "."))

        $("#discount").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(totalDiscount).replace("IDR", "Rp").replace(/,/g, "."))

        $("#shipment").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(totalShipment).replace("IDR", "Rp").replace(/,/g, "."))

        let total = totalPrice + servicePrice + totalShipment - totalDiscount

        $("#total").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(total).replace("IDR", "Rp").replace(/,/g, "."))
    }

    function checkout() {
        let total = parseInt($("#total").text().substr(3).replace(/\./g, ""))
        let coupon = parseInt($("#discount").text().substr(3).replace(/\./g, ""))
        let shipping = parseInt($("#shipment").text().substr(3).replace(/\./g, ""))

        $.ajax({
            data: {
                id_group: $("#groupID").val(),
                id_leader: $("#leaderID").val(),
                id_seller: $("#sellerID").val(),
                id_product: $("#productID").val(),
                qty: $("#qty").val(),
                id_group_price: $("#groupPriceID").val(),
                total: total,
                coupon: coupon,
                shipping: shipping
            },
            url: "{{ url('group/checkout') }}",
            method: 'POST',
            success: function(result) {
                let transID = result.trans_id

                // Trigger snap popup.
                window.snap.pay(result.snap_token, {
                    onSuccess: function(result){
                        $.ajax({
                            data: {
                                trans_id: transID
                            },
                            url: "{{ url('checkout/payment-success') }}",
                            method: 'POST',
                            success: function(result) {
                                
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                toastr.error(thrownError)
                            }
                        })

                        window.location.href = "/"
                    },
                    onPending: function(result){
                        window.location.href = "/"
                    },
                    onError: function(result){
                        $.ajax({
                            data: {
                                id_trans: transID
                            },
                            url: "{{ url('checkout/payment-failed') }}",
                            method: 'POST',
                            success: function(result) {
                                
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                toastr.error(thrownError)
                            }
                        })
                    },
                    onClose: function(){
                        $.ajax({
                            data: {
                                id_trans: transID
                            },
                            url: "{{ url('checkout/payment-failed') }}",
                            method: 'POST',
                            success: function(result) {
                                
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                toastr.error(thrownError)
                            }
                        })
                    }
                })
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }
</script>

@endsection