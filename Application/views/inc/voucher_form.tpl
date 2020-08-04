[{if $oViewConf->getShowVouchers()}]
    [{assign var="vouchererrors" value=false}]
    [{foreach from=$Errors.basket item=oEr key=key}]
    [{if $oEr->getErrorClassType() == 'oxVoucherException'}]
    [{assign var="vouchererrors" value=true}]
    [{/if}]
    [{/foreach}]

    <div class="card">
        <h3 class="card-title">[{oxmultilang ident="GW_ADD_CODE"}]</h3>
        <div id="basketVoucher">
            <form name="voucher" action="[{$oViewConf->getSelfActionLink()}]" method="post" class="js-oxValidate form-inline" role="form" novalidate="novalidate">
                <div class="couponBox" id="coupon">
                    <div class="hidden">
                        [{$oViewConf->getHiddenSid()}]
                        <input type="hidden" name="cl" value="basket">
                        <input type="hidden" name="fnc" value="addVoucher">
                        <input type="hidden" name="CustomError" value="basket">
                    </div>

                    <div class="form-group">
                        <div class="gw-float-label">
                            <input type="text" name="voucherNr" size="30" class="form-control voucher-code js-oxValidate js-oxValidate_notEmpty empty" id="input_voucherNr" required>
                            <button type="submit" class="btn btn-primary submitButton">[{oxmultilang ident="REDEEM_COUPON"}]</button>
                            <label class="control-label" for="input_voucherNr">[{oxmultilang ident="GW_ENTER_CODE"}]</label>
                        </div>
                        <div class="help-block"></div>
                    </div>

                    [{foreach from=$Errors.basket item=oEr key=key}]
                    [{if $oEr->getErrorClassType() == 'oxVoucherException'}]
                    <div class="alert alert-danger text-left">
                        [{oxmultilang ident="COUPON_NOT_ACCEPTED" args=$oEr->getValue('voucherNr')}]<br>
                        <strong>[{oxmultilang ident="REASON"}]:</strong>
                        [{$oEr->getOxMessage()}]
                    </div>
                    [{/if}]
                    [{/foreach}]
                </div>
            </form>
        </div>
        <br>
    </div>
    [{/if}]
