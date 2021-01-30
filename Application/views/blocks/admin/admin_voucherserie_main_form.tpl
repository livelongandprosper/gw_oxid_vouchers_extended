[{$smarty.block.parent}]
<tr>
    <td class="edittext" width="120">
        [{oxmultilang ident="GW_ONLY_ONCE_PER_SHIPPING_ADDRESS"}]
    </td>
    <td class="edittext">
        <input type="hidden" name="editval[oxvoucherseries__gw_only_once_per_shipping_address]" value='0' [{$readonly}]>
        <input class="edittext" type="checkbox" name="editval[oxvoucherseries__gw_only_once_per_shipping_address]" value='1' [{if $edit->oxvoucherseries__gw_only_once_per_shipping_address->value == 1}]checked[{/if}] [{$readonly}]>
        [{oxinputhelp ident="HELP_GW_ONLY_ONCE_PER_SHIPPING_ADDRESS"}]
    </td>
</tr>
<tr>
    <td class="edittext" width="120">
        [{oxmultilang ident="GW_ONLY_NOT_REDUCED_ARTICLES"}]
    </td>
    <td class="edittext">
        <input type="hidden" name="editval[oxvoucherseries__gw_only_not_reduced_articles]" value='0' [{$readonly}]>
        <input class="edittext" type="checkbox" name="editval[oxvoucherseries__gw_only_not_reduced_articles]" value='1' [{if $edit->oxvoucherseries__gw_only_not_reduced_articles->value == 1}]checked[{/if}] [{$readonly}]>
        [{oxinputhelp ident="HELP_GW_ONLY_NOT_REDUCED_ARTICLES"}]
    </td>
</tr>
<tr>
    <td class="edittext" width="120">
        [{oxmultilang ident="GW_HANDLE_LIKE_DISCOUNT"}]
    </td>
    <td class="edittext">
        <input type="hidden" name="editval[oxvoucherseries__gw_handle_like_discount]" value='0' [{$readonly}]>
        <input class="edittext" type="checkbox" name="editval[oxvoucherseries__gw_handle_like_discount]" value='1' [{if $edit->oxvoucherseries__gw_handle_like_discount->value == 1}]checked[{/if}] [{$readonly}]>
        [{oxinputhelp ident="HELP_GW_HANDLE_LIKE_DISCOUNT"}]
    </td>
</tr>


