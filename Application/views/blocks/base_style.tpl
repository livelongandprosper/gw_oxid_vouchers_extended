[{$smarty.block.parent}]
[{assign var="config" value=$oViewConf->getConfig()}]
[{if $config->getConfigParam('gw_oxid_vouchers_extended_hide_form_in_basket')}]
    <style>.cl-basket .basket-vouchers{display:none;}</style>
[{/if}]
