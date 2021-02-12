<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * @see OxidEsales\Eshop\Application\Model\Order
 */
class Order extends Order_parent {
	public function finalizeOrder(\OxidEsales\Eshop\Application\Model\Basket $oBasket, $oUser, $blRecalculatingOrder = false) {
		if(!$blRecalculatingOrder) {
			$orderId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('sess_challenge');
		} else {
			$orderId = $this->getId();
		}
		$saveOrderAtEnd = false;

		// check if there are vouchers to be transformed in regular discount that should be assigned to every article
		if(!$blRecalculatingOrder && $this->_applyDirectOrderArticlePriceDiscountVoucher($orderId, $oBasket, $oUser)) {
			$oBasket->calculateBasket(true);
		}

		$parent_return = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

		if(!$blRecalculatingOrder && parent::ORDER_STATE_OK) {
			// at this point the order was already saved
			$this->load($orderId);

			// get all vouchers applied
 			$vouchers = $oBasket->getVouchers();

			// check if voucher series is discount voucher
			if(count($vouchers)) {
				foreach ($vouchers as $voucherId => $stdObjVoucher) {
					$oVoucher = oxNew(\OxidEsales\Eshop\Application\Model\Voucher::class);
					$oVoucher->load($voucherId);

					if($oVoucher->isDiscountVoucher()) {
						$oDiscount = $oVoucher->getSeriesDiscount();

						// calculating price to apply discount
						$dPrice = 0.0;
						foreach ($this->getOrderArticles(true) as $oOrderArticle) {
							if ($oDiscount->isForBasketItem($oOrderArticle)) {
								$dPrice += $oOrderArticle->oxorderarticles__oxbprice->value;
							}
						}

						// remove voucher discount
						$dVoucherdiscount = $oVoucher->getDiscountValue($dPrice);

						// reduce voucherdiscount
						if($this->oxorder__oxvoucherdiscount->value == $dVoucherdiscount) {
							$this->oxorder__oxvoucherdiscount->value = 0.0;
						} else {
							$this->addVoucherDiscount(-$dVoucherdiscount);
						}

						// add regular discount
						$this->addDiscount($dVoucherdiscount);

						// mark voucher as transformed
						$oVoucher->oxvouchers__gw_transformed_to_discount->setValue(1);
						$oVoucher->save();
						$saveOrderAtEnd = true;
					}
				}
			}
		}

		if($saveOrderAtEnd) {
			$this->save();
		}

		return $parent_return;
	}

	/**
	 * s
	 * @param $oVoucher
	 * @return false if nothing was changed, true else
	 */
	protected function _applyDirectOrderArticlePriceDiscountVoucher($orderId, $oBasket, $oUser) {
		$return_value = false;
		$vouchers = $oBasket->getVouchers();
		if(count($vouchers)) {
			foreach ($vouchers as $voucherId => $stdObjVoucher) {
				$oVoucher = oxNew(\OxidEsales\Eshop\Application\Model\Voucher::class);
				$oVoucher->load($voucherId);

				if($oVoucher->shouldBeAppliedStraigtToOrderArticles()) {
					$oDiscount = $oVoucher->getSeriesDiscount();
					$oBasket->addBasketItemDiscount($oDiscount);
					$oBasket->removeVoucher($oVoucher->getId());
					$oVoucher->markAsUsed($orderId, $oUser->getId(), 0);
					$oVoucher->oxvouchers__gw_applied_to_oxorderarticle->setValue(1);
					$oVoucher->save();
					$return_value = true;
				}
			}
		}

		return $return_value;
	}

	/**
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
