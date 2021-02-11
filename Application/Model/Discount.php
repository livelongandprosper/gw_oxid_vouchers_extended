<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * @see OxidEsales\Eshop\Application\Model\Discount
 */
class Discount extends Discount_parent {
	/**
	 * Checks if discount is setup for some basket item
	 *
	 * @param object $oArticle basket item
	 *
	 * @return bool
	 */
	public function isForBasketItem($oArticle) {
		/*
		$logger = Registry::getLogger();
		$logger->error($oArticle->getPrice()->getPrice(), []);
		*/
		if ($this->oxdiscount__gw_dont_apply_for_reduced_articles->value) {
			if(
				(double)$oArticle->getPrice()->getPrice() < (double)$oArticle->getBasePrice() // calculated price is smaller than standard price -> it's reduced
				|| ( method_exists($oArticle, 'getTPrice') && $oArticle->getTPrice() && (double)$oArticle->getPrice()->getPrice() < (double)$oArticle->getTPrice()->getPrice() ) // RRP (UVP) is bigger than price it's
				|| ($oArticle->isOrderArticle() && $oArticle->getArticle()->getTPrice() && (double)$oArticle->getArticle()->getPrice()->getPrice() < (double)$oArticle->getArticle()->getTPrice()->getPrice() ) // RRP (UVP) of OrderArticles Article object is bigger than price it's
				|| ($oArticle->isOrderArticle() && (double)$oArticle->getArticle()->getPrice()->getPrice() < (double)$oArticle->getArticle()->getBasePrice() )  // calculated price of OrderArticles Article object is smaller than standard price -> it's reduced
			) {
				return false;
			} elseif($this->oxdiscount__gw_is_product_voucher->value || $this->oxdiscount__gw_is_category_voucher->value) {
				return parent::isForBasketItem($oArticle);
			} else {
				return true;
			}
		}
		return parent::isForBasketItem($oArticle);
	}
}
?>
