[{$smarty.block.parent}]
<tr>
    <td class="edittext" width="120">
        [{oxmultilang ident="GW_ONLY_ONCE_PER_USER"}]
    </td>
    <td class="edittext">
        <input type="hidden" name="editval[oxvoucherseries__gw_only_once_per_user]" value='0' [{$readonly}]>
        <input class="edittext" type="checkbox" name="editval[oxvoucherseries__gw_only_once_per_user]" value='1' [{if $edit->oxvoucherseries__gw_only_once_per_user->value == 1}]checked[{/if}] [{$readonly}]>
        [{oxinputhelp ident="HELP_GW_ONLY_ONCE_PER_USER"}]
    </td>
</tr>
