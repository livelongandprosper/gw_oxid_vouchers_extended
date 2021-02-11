<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\VoucherSerie
 */
class VoucherSerie extends VoucherSerie_parent {
	/**
	 * @param bool $sanitisize
	 * @return string
	 */
	public function getGroupName($sanitisize = true) {
		if($sanitisize) {
			return (string)trim(strtolower($this->oxvoucherseries__gw_voucher_series_group->value));
		} else {
			return (string)$this->oxvoucherseries__gw_voucher_series_group->value;
		}
	}

	/**
	 * @return bool
	 */
	public function notAllowedWithSameGroup() {
		return (bool)$this->oxvoucherseries__gw_same_group_not_allowed->value;
	}
}
?>
