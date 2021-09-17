<?php
	namespace gw\gw_oxid_vouchers_extended\Core;
	use OxidEsales\Eshop\Application\Model\Voucher;

	/**
	 * Check if the voucher is a discount voucher or directly to order articles applied voucher.
	 * @see OxidEsales\Eshop\Core\ViewConfig
	 */
	class ViewConfig extends ViewConfig_parent {
		public function isDiscountVoucher($voucherId) {
			$oVoucher = oxNew(Voucher::class);
			$oVoucher->load($voucherId);
			return $oVoucher->isDiscountVoucher();
		}
	}
?>
