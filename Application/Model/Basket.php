<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * @see OxidEsales\Eshop\Application\Model\Basket
 */
class Basket extends Basket_parent {
    private $_additonalDiscounts = null;

	/**
	 * @return array
	 */
	public function getAdditonalBasketItemDiscount(){
		if(is_array($this->_additonalDiscounts)) {
			return $this->_additonalDiscounts;
		}
		return array();
	}

	/**
	 * @param $oDiscount+
	 */
	public function addBasketItemDiscount($oDiscount) {
		if($this->_additonalDiscounts === null) {
			$this->_additonalDiscounts = array();
		}
		$this->_additonalDiscounts[$oDiscount->getId()] = $oDiscount;
	}
}
?>
