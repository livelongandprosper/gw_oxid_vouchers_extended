<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * @see OxidEsales\Eshop\Application\Model\DiscountList
 */
class DiscountList extends DiscountList_parent {
	/**
	 * Returns array of discounts that can be applied for individual basket item
	 *
	 * @param mixed                                      $oArticle article object or article id (according to needs)
	 * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket  array of basket items containing article id, amount and price
	 * @param \OxidEsales\Eshop\Application\Model\User   $oUser    user object (optional)
	 *
	 * @return array
	 */
	public function getBasketItemDiscounts($oArticle, $oBasket, $oUser = null) {
		$aDiscountsToAdd = array();
		$aAdditionalBasketItemDiscounts = $oBasket->getAdditonalBasketItemDiscount();
		if($aAdditionalBasketItemDiscounts && count($aAdditionalBasketItemDiscounts) > 0) {
			foreach($aAdditionalBasketItemDiscounts as $oDiscount) {
				if($oDiscount->isForBasketItem($oArticle) && $oDiscount->isForBasketAmount($oBasket)) {
					$aDiscountsToAdd[] = $oDiscount;
				}
			}
		}
		return array_merge($aDiscountsToAdd, parent::getBasketItemDiscounts($oArticle, $oBasket, $oUser));
	}
}
?>
