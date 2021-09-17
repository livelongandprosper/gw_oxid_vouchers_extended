<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * @see OxidEsales\Eshop\Application\Model\Order
 */
class Order extends Order_parent {

	/**
	 * Extend function so that
	 * - discount voucher will be converted to discounts
	 * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket
	 * @param $oUser
	 * @param false $blRecalculatingOrder
	 * @return mixed
	 */
	public function finalizeOrder(\OxidEsales\Eshop\Application\Model\Basket $oBasket, $oUser, $blRecalculatingOrder = false) {
		if(!$blRecalculatingOrder) {
			$orderId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('sess_challenge');
		} else {
			$orderId = $this->getId();
		}
		$saveOrderAtEnd = false;

		// !finalize order must not be moved to end of this function
		$parent_return = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

		if(parent::ORDER_STATE_OK) {
			// at this point the order was already saved by the parent method finalizeOrder
			$this->load($orderId);

			// get all vouchers applied to this order
 			$vouchers = $oBasket->getVouchers();

			// check if voucher series is discount voucher
			if(count($vouchers)) {
				foreach ($vouchers as $voucherId => $stdObjVoucher) {
					$oVoucher = oxNew(\OxidEsales\Eshop\Application\Model\Voucher::class);
					$oVoucher->load($voucherId);

					/**
					 * check if voucher should be converted to regular discount
					 * and check if voucher was already transformed to discount (prevent multiple transformations)
					 */
					if(
						$oVoucher->isDiscountVoucher() && !$oVoucher->isTransformedToDiscount() // dont convert voucher to discount twice
						|| $blRecalculatingOrder && $oVoucher->isDiscountVoucher() // convert again if order is recalculated
					) {
						// remove voucher discount
						$dVoucherdiscount = $oVoucher->oxvouchers__oxdiscount->value; // the discount value should be calculated at this point, so we directly take the discount value of the voucher and don't calculate it again

						// write notice to log file
						$logger = Registry::getLogger();
						$logger->notice("transform voucher " . $stdObjVoucher->sVoucherNr . " to regular discount. voucher discount value: ".$dVoucherdiscount, []);

						// reduce voucher discount
						if($this->oxorder__oxvoucherdiscount->value == $dVoucherdiscount) {
							$this->oxorder__oxvoucherdiscount->value = 0.0;
						} else {
							$this->addVoucherDiscount(-$dVoucherdiscount);
						}

						// add as regular discount
						$this->addDiscount($dVoucherdiscount);

						// mark voucher as transformed
						$oVoucher->setTransformedToDiscount(1);
						$oVoucher->save();
						$saveOrderAtEnd = true;
					}
				}
			}
		}

		if($saveOrderAtEnd) {
			$this->save();
		}

		// write notices
		if($this->oxorder__oxvoucherdiscount->value || $this->oxorder__oxdiscount->value) {
			$logger = Registry::getLogger();

			// write notice to log file
			$logger->notice($this->oxorder__oxordernr->value . " total order voucher value: ".$this->oxorder__oxvoucherdiscount->value);

			// write notice to log file
			$logger->notice($this->oxorder__oxordernr->value . " total order discount value: ".$this->oxorder__oxdiscount->value);
		}

		return $parent_return;
	}

	/**
	 * Add a std discount value to the total order discount value.
	 * @param float $discountToAdd
	 */
	protected function addDiscount($discountToAdd = 0.0) {
		if($this->oxorder__oxdiscount === false || $this->oxorder__oxdiscount === null) {
			$this->oxorder__oxdiscount = new \OxidEsales\Eshop\Core\Field(0.0, \OxidEsales\Eshop\Core\Field::T_RAW);
		}
		if(($this->oxorder__oxdiscount->value + (float)$discountToAdd) >= 0) {
			$this->oxorder__oxdiscount->value += (float)$discountToAdd;
		}
	}

	/**
	 * Add a voucher discount value to the total order voucher discount value.
	 * @param float $discountToAdd
	 */
	protected function addVoucherDiscount($discountToAdd = 0.0) {
		if($this->oxorder__oxvoucherdiscount === false || $this->oxorder__oxvoucherdiscount === null) {
			$this->oxorder__oxvoucherdiscount = new \OxidEsales\Eshop\Core\Field(0.0, \OxidEsales\Eshop\Core\Field::T_RAW);
		}
		if(($this->oxorder__oxvoucherdiscount->value + (float)$discountToAdd) >= 0) {
			$this->oxorder__oxvoucherdiscount->value += (float)$discountToAdd;
		}
	}
}
?>
