<script src="{{ url('/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ url('/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ url('/sb-admin-2/js/sb-admin-2.min.js') }}"></script>
<script src="{{ url('/sb-admin-2/vendor/chart.js/Chart.min.js') }}"></script>

<script src="{{ url('/sb-admin-2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ url('/assets/lib/cropperjs/js/cropper.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
    function showElement(element, value, display) {
        if (value) {
            $(element).removeClass("d-none")
            $(element).addClass(display)
        } else {
            $(element).removeClass(display)
            $(element).addClass("d-none")
        }
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        number = number.toFixed(decimals);

        var nstr = number.toString();
        nstr += '';
        x = nstr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? dec_point + x[1] : '';
        var rgx = /(\d+)(\d{3})/;

        while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

        return x1 + x2;
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

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>