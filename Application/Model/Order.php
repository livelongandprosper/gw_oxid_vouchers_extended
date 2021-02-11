<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\Order
 */
class Order extends Order_parent {
	public function finalizeOrder(\OxidEsales\Eshop\Application\Model\Basket $oBasket, $oUser, $blRecalculatingOrder = false) {
		$saveOrderAtEnd = false;
		if(!$blRecalculatingOrder) {
			$orderId = $orderId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('sess_challenge');;
		}

		$parent_return = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

		if(!$blRecalculatingOrder && parent::ORDER_STATE_OK) {
			// at this point the order was already saved
			$this->load($orderId);

			$vouchers = $oBasket->getVouchers();
			// get all vouchers applied
			// check if voucher series

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
							$logger = \OxidEsales\Eshop\Core\Registry::getLogger();
							$logger->error($this->oxorder__oxvoucherdiscount->value ."-". $dVoucherdiscount." = ".($this->oxorder__oxvoucherdiscount->value - $dVoucherdiscount), []);

							if( $this->oxorder__oxvoucherdiscount->value - $dVoucherdiscount >= 0 ) {
								$this->addVoucherDiscount(-1.0 * $dVoucherdiscount);
							}
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

	protected function recalculateOrderByOrderArticleDiscountVoucher() {

	}

	/**
	 * @param float $discountToAdd
	 */
	protected function addDiscount($discountToAdd = 0.0) {
		if($this->oxorder__oxdiscount === false || $this->oxorder__oxdiscount === null) {
			$this->oxorder__oxdiscount = new \OxidEsales\Eshop\Core\Field(0.0, \OxidEsales\Eshop\Core\Field::T_RAW);
		}
		$this->oxorder__oxdiscount->value += (float)$discountToAdd;
	}

	/**
	 * @param float $discountToAdd
	 */
	protected function addVoucherDiscount($discountToAdd = 0.0) {
		if($this->oxorder__oxvoucherdiscount === false || $this->oxorder__oxvoucherdiscount === null) {
			$this->oxorder__oxvoucherdiscount = new \OxidEsales\Eshop\Core\Field(0.0, \OxidEsales\Eshop\Core\Field::T_RAW);
		}
		$this->oxorder__oxvoucherdiscount->value += (float)$discountToAdd;
	}
}
?>
