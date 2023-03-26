<!-- Js Plugins -->
<script src="{{ url('ogani/js/bootstrap.min.js') }}"></script>
<script src="{{ url('ogani/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ url('ogani/js/jquery-ui.min.js') }}"></script>
<script src="{{ url('ogani/js/jquery.slicknav.js') }}"></script>
<script src="{{ url('ogani/js/mixitup.min.js') }}"></script>
<script src="{{ url('ogani/js/owl.carousel.min.js') }}"></script>
<script src="{{ url('ogani/js/main.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>

    <?php if (isset($_SESSION["user"])) { ?>

        function updateCartCount() {
            $.ajax({
                url: "{{ url('cart/cart-count') }}",
                method: 'POST',
                success: function(result) {
                    $(".cartCount").text(result.message)
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError)
                }
            })
        }

        function updateWishlistCount() {
            $.ajax({
                url: "{{ url('wishlist/wishlist-count') }}",
                method: 'POST',
                success: function(result) {
                    $(".wishlistCount").text(result.message)
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError)
                }
            })
        }

        function updateFavoriteCount() {
            $.ajax({
                url: "{{ url('favorite/favorite-count') }}",
                method: 'POST',
                success: function(result) {
                    $(".favoriteCount").text(result.message)
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError)
                }
            })
        }

    <?php } ?>
    
    function addToCart(product_id, qty = 1) {

        <?php if (isset($_SESSION["user"])) { ?>

            let data = {
                product_id: product_id,
                qty: $("#productQty").val() ? $("#productQty").val() : qty 
            }

            $.ajax({
                data: data,
                url: "{{ url('cart/add-to-cart') }}",
                method: 'POST',
                success: function(result) {
                    updateCartCount()

                    toastr.success("Produk ditambahkan ke keranjang belanja")
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    toastr.error(thrownError)
                }
            })

        <?php } else { ?>

            $("#loginModal").modal("show")

        <?php } ?>

    }

    function addWishlist(product_id) {

        <?php if (isset($_SESSION["user"])) { ?>

            let data = {
                product_id: product_id
            }

            $.ajax({
                data: data,
                url: "{{ url('wishlist/add-wishlist') }}",
                method: 'POST',
                success: function(result) {
                    updateWishlistCount()

                    if (result.message == "Removed from wishlist") {
                        toastr.success("Produk dihapus dari wishlist")
                    } else {
                        toastr.success("Produk ditambahkan ke wishlist")
                    }

                    // Check if function exist
                    if (typeof checkWishlist === "function") {
                        checkWishlist()
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

    function convertToNumberFormat(rawValue) {
        var formattedValue = rawValue.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        var result = formattedValue.split(',').join('.');
        return result;
    }

    function autoNumeric(value) {
        var rawValue = null;

        if(value.includes(".")) {
            rawValue = value.split('.').join('');
        } else {
            rawValue = value;
        }

        return convertToNumberFormat(rawValue);
    }

    function checkNumericInput(keyCode) {
        if(keyCode === 8) {
            return true;
        }
        
        if((keyCode > 47 && keyCode < 58) || (keyCode > 95 && keyCode < 106)) {
            return true;
        } else {
            return false;
        }
    }

    function checkNumericInputWithZero(keyCode, value) {
        if(checkNumericInput(keyCode)) {
            if(value[0] != 0) {
                return true;
            } else {
                if(keyCode === 8) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
</script>