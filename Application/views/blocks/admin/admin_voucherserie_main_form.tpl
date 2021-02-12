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
        <input class="edittext" type="radio" name="editval[oxvoucherseries__gw_voucher_mode]" value='0' [{if $edit->oxvoucherseries__gw_voucher_mode->value == 0}]checked[{/if}] [{$readonly}]>[{oxmultilang ident="GW_STANDARD"}][{oxinputhelp ident="HELP_GW_STANDARD"}]<br>
        <input class="edittext" type="radio" name="editval[oxvoucherseries__gw_voucher_mode]" value='1' [{if $edit->oxvoucherseries__gw_voucher_mode->value == 1}]checked[{/if}] [{$readonly}]>[{oxmultilang ident="GW_HANDLE_LIKE_DISCOUNT"}][{oxinputhelp ident="HELP_GW_HANDLE_LIKE_DISCOUNT"}]<br>
        <input class="edittext" type="radio" name="editval[oxvoucherseries__gw_voucher_mode]" value='2' [{if $edit->oxvoucherseries__gw_voucher_mode->value == 2}]checked[{/if}] [{$readonly}]>[{oxmultilang ident="GW_HANDLE_APPLY_TO_OXORDERARTICLE"}][{oxinputhelp ident="HELP_GW_HANDLE_APPLY_TO_OXORDERARTICLE"}]

    </td>
</tr>


<tr>
    <td class="edittext" width="120" colspan="2" style="border-top: 1px solid gray;padding-top: 5px;">
        <strong>[{oxmultilang ident="GW_VOUCER_SERIES_GROUP_HEAD"}]</strong>
    </td>
</tr>
<tr>
    <td class="edittext" width="120">
        [{oxmultilang ident="GW_VOUCER_SERIES_GROUP"}]
    </td>
    <td class="edittext">
        <input class="edittext" type="text" name="editval[oxvoucherseries__gw_voucher_series_group]" value='[{if $edit->oxvoucherseries__gw_voucher_series_group->value}][{$edit->oxvoucherseries__gw_voucher_series_group->value}][{/if}]' [{$readonly}]>
        [{oxinputhelp ident="HELP_GW_VOUCER_SERIES_GROUP"}]
    </td>
</tr>
<tr>
    <td class="edittext" width="120">
        [{oxmultilang ident="GW_NOT_ALLOWED_WITH_SAME_GROUP"}]
    </td>
    <td class="edittext">
        <input type="hidden" name="editval[oxvoucherseries__gw_same_group_not_allowed]" value='0' [{$readonly}]>
        <input class="edittext" type="checkbox" name="editval[oxvoucherseries__gw_same_group_not_allowed]" value='1' [{if $edit->oxvoucherseries__gw_same_group_not_allowed->value == 1}]checked[{/if}] [{$readonly}]>
        [{oxinputhelp ident="HELP_GW_HANDLE_LIKE_DISCOUNT"}]
    </td>
</tr>



