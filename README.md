# Extended Vouchers

Module that extends the voucher system of OXID eShop. Tested with OXID eShop 6.1.3 (should work with v6.x).

## Features
* adds option to voucher series that vouchers of a voucher series can only be used one time per delivery address
    * used vouchers are saved with a md5 checksum which is generated with delivery address fields
    * address fields can be chosen in module options

## Install
- This module has to be put to the folder
\[shop root\]**/modules/gw/gw_oxid_vouchers_extended/**

- You also have to create a file
\[shop root\]/modules/gw/**vendormetadata.php**

- add content in composer_add_to_root.json to your global composer.json file and call composer dump-autoload

After you have done that go to shop backend and activate module.
