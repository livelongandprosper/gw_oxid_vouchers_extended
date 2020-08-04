[{if $oViewConf->getShowVouchers() && $oxcmp_basket->getVouchers()}]
    [{oxmultilang ident="USED_COUPONS"}]
    [{foreach from=$Errors.basket item=oEr key=key}]
    [{if $oEr->getErrorClassType() == 'oxVoucherException'}]
    <div class="alert alert-danger">
        [{oxmultilang ident="PAGE_CHECKOUT_ORDER_COUPONNOTACCEPTED1"}] [{$oEr->getValue('voucherNr')}] [{oxmultilang ident="PAGE_CHECKOUT_ORDER_COUPONNOTACCEPTED2"}]<br>
        [{oxmultilang ident="REASON"}]
        [{$oEr->getOxMessage()}]
    </div>
    [{/if}]
    [{/foreach}]
    <div class="alert alert-info">
        [{foreach from=$oxcmp_basket->getVouchers() item=sVoucher key=key name=aVouchers}]
        [{$sVoucher->sVoucherNr}]<br>
        [{/foreach}]
    </div>
[{/if}]
