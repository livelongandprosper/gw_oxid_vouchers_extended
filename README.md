# Extended Vouchers

Module that extends the voucher system of OXID eShop. Tested with OXID eShop 6.1.3 (should work with v6.x).

## Features
* adds option to voucher series that vouchers of a voucher series can only be used one time per delivery address
    * used vouchers are saved with a md5 checksum which is generated with delivery address fields
    * address fields can be chosen in module options
* add voucher form to last order step
    * add option to hide voucher form in basket

## Install
- This module has to be put to the folder
\[shop root\]**/modules/gw/gw_oxid_vouchers_extended/**

- You also have to create a file
\[shop root\]/modules/gw/**vendormetadata.php**

- add content in composer_add_to_root.json to your global composer.json file and call composer dump-autoload

After you have done that go to shop backend and activate module.

## JavaScript which can be used to make AJAX calls (jQuery required); put these in to your

´´´javascript

const $voucherForm = $("form[name='voucher']");

$voucherForm.on("submit", function(e){

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');

    let $ajaxLoader = $voucherForm.find(".ajax-content-loader");
    // Create a loading circle if this item doesn't have one yet.
    if($ajaxLoader.length === 0) {

        $voucherForm.append("<div class=\"ajax-content-loader\" />");
        $ajaxLoader = $voucherForm.find(".ajax-content-loader");
    }
    $ajaxLoader.fadeIn();

    $.ajax({

        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        success: function(data) {

            const $errors = $(data).find(".alert");

            if($errors.length) {

                $voucherForm.parent().find(".alert").remove();
                $voucherForm.before($errors);

            } else {

                $ajaxLoader.fadeOut(function(){
                    location.reload();
                });

            }

        },

        // Timeout
        error(x, t, m) {

            if(t === "timeout") {

                console.error("Error - Code: T3");
                // location.reload();

            } else {

                console.error("Error - Code: 3");
                // location.reload();

            }

        },

        complete(data) {

            $ajaxLoader.fadeOut();

        }

    });
});

$(document).on("click", ".couponData .removeFn", function (event) {

    const url = $(this).attr("href");
    let $ajaxLoader = $voucherForm.find(".ajax-content-loader");
    // Create a loading circle if this item doesn't have one yet.
    if($ajaxLoader.length === 0) {

        $voucherForm.append("<div class=\"ajax-content-loader\" />");
        $ajaxLoader = $voucherForm.find(".ajax-content-loader");
    }
    $ajaxLoader.fadeIn();

    $.ajax({

        type: "GET",
        url: url,
        success: function (data) {

            const $errors = $(data).find(".alert");

            $ajaxLoader.fadeOut(function () {
                location.reload();
            });

        }

    });
    return false;

});

´´´
