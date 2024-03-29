<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

use oxDb;
use oxRegistry;
use oxField;
use OxidEsales\Eshop\Core\Registry;


/**
 * @see OxidEsales\Eshop\Application\Model\Voucher
 */
class Voucher extends Voucher_parent {
	/**
	 * Checks availability for the given user. Returns array with errors.
	 * @see OxidEsales\EshopCommunity\Application\Model\Voucher
	 * @see https://docs.oxid-esales.com/sourcecodedocumentation/6.1.4/class_oxid_esales_1_1_eshop_community_1_1_application_1_1_model_1_1_voucher.html
	 * @param object $oUser user object
	 *
	 * @throws oxVoucherException exception
	 *
	 * @return array
	 */
	public function checkUserAvailability($oUser) {
		$this->_isAvailableWithOtherShippingAddress($oUser);

		// returning true if no exception was thrown
		return parent::checkUserAvailability($oUser);
	}

	/**
	 * Checks availability without user logged in. Returns array with errors.
	 *
	 * @param array  $aVouchers array of vouchers
	 * @param double $dPrice    current sum (price)
	 *
	 * @throws oxVoucherException exception
	 *
	 * @return array
	 */
	public function checkVoucherAvailability($aVouchers, $dPrice) {
		$this->_isAvailableWithSameVoucherSeriesGroup($aVouchers);

		// returning true - no exception was thrown
		return parent::checkVoucherAvailability($aVouchers, $dPrice);
	}

	/**
	 * Should a voucher be converted to regular discount (DB oxorder.oxdiscount)
	 * @return bool
	 */
	public function isDiscountVoucher() {
		$oSeries = $this->getSerie();
		return ($oSeries->oxvoucherseries__gw_voucher_mode->value == 1);
	}

	/**
	 * Add field oxdiscount__gw_dont_apply_for_reduced_articles to discount serie object.
	 *
	 * @return object
	 */
	protected function _getSerieDiscount() {
		$oSeries = $this->getSerie();
		$parent_return = parent::_getSerieDiscount();

		return $parent_return;
	}

	/**
	 * Make function _getSerieDiscount public.
	 * @return object
	 */
	public function getSeriesDiscount() {
		return $this->_getSerieDiscount();
	}

	/**
	 * Check if this voucher is available with same voucher series group
	 *
	 * @param $aVouchers
	 *
	 * @throws oxVoucherException exception
	 *
	 * @return bool
	 */
	protected function _isAvailableWithSameVoucherSeriesGroup($aVouchers) {
		if(is_array($aVouchers)) {
			$oSeries = $this->getSerie();

			// write notice to log file
			$logger = Registry::getLogger();
			$logger->notice("voucher count: " . count($aVouchers) . " erlaubt mit gleicher gutschein gruppe: ". (int)!$oSeries->notAllowedWithSameGroup(). ' gruppenname: '.$oSeries->getGroupName(), []);

			if( count($aVouchers) > 0 && $oSeries->notAllowedWithSameGroup() && $oSeries->getGroupName() != '' ) {
				$usedGroups = $this->_getVoucherSeriesGroupsUsed($aVouchers);
				if( count($usedGroups) > 0 && in_array($oSeries->getGroupName(), $usedGroups) ) {
					// Exception
					$oEx = oxNew(\OxidEsales\Eshop\Core\Exception\VoucherException::class);
					$oEx->setMessage('GW_ERROR_MESSAGE_VOUCHER_CANT_BE_USED_WITH_SAME_SERIES');
					$oEx->setVoucherNr($this->oxvouchers__oxvouchernr->value);
					throw $oEx;
				}
			}
		}
		return true;
	}

	/**
	 * Return array of voucher series already used
	 * (currently checked objects group will not be added to this array)
	 * @param $aVouchers
	 * @return array
	 */
	private function _getVoucherSeriesGroupsUsed($aVouchers) {
		$return_value = array();
		if(count($aVouchers)) {
			foreach ($aVouchers as $voucherId => $voucherNr) {
				if($voucherId != $this->getId()) {
					$oVoucher = oxNew(\OxidEsales\Eshop\Application\Model\Voucher::class);
					$oVoucher->load($voucherId);
					$oSeries = $oVoucher->getSerie();
					if( $oSeries->getGroupName() != '' && !in_array($oSeries->getGroupName(), $return_value) ) {
						$return_value[] = $oSeries->getGroupName();
					}
				}
			}
		}
		return $return_value;
	}

	/**
	 * Check if this voucher is available with other shipping address
	 *
	 * @param $oUser
	 *
	 * @throws oxVoucherException exception
	 *
	 * @return bool
	 */
	protected function _isAvailableWithOtherShippingAddress($oUser) {
		$oSeries = $this->getSerie();
		if ($oSeries->oxvoucherseries__gw_only_once_per_shipping_address->value) {
			// md5 of shipping address
			$sSippingAddressMD5 = $this->_getShippingAddressMD5($oUser);

			/*
			$logger = Registry::getLogger();
			$logger->error($sSippingAddressMD5, []);
			*/

			$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
			$sSelect = 'select count(*) from ' . $this->getViewName() . ' where gw_shipping_address_checksum = ' . $oDb->quote($sSippingAddressMD5) . ' and gw_shipping_address_checksum != ' . "'' and ";
			$sSelect .= 'oxvoucherserieid = ' . $oDb->quote($this->oxvouchers__oxvoucherserieid->value) . ' and ';
			$sSelect .= '((oxorderid is not NULL and oxorderid != "") or (oxdateused is not NULL and oxdateused != 0)) ';

			if ($oDb->getOne($sSelect)) {
				$oEx = oxNew(\OxidEsales\Eshop\Core\Exception\VoucherException::class);
				$oEx->setMessage('GW_ERROR_MESSAGE_VOUCHER_SAME_ADDRESS');
				$oEx->setVoucherNr($this->oxvouchers__oxvouchernr->value);
				throw $oEx;
			}
		}

		return true;
	}

	/**
	 * marks voucher as used
	 *
	 * @param string $sOrderId  order id
	 * @param string $sUserId   user id
	 * @param double $dDiscount used discount
	 */
	public function markAsUsed($sOrderId, $sUserId, $dDiscount) {
		//saving oxreserved field
		if ($this->oxvouchers__oxid->value) {
			$oUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
			$oUser->load($sUserId);
			if($oUser) {
				$this->oxvouchers__gw_shipping_address_checksum->setValue($this->_getShippingAddressMD5($oUser));
			}
		} else {
			$this->oxvouchers__gw_shipping_address_checksum->setValue("couldnotbesaved");
		}
		parent::markAsUsed($sOrderId, $sUserId, $dDiscount);
	}

	/**
	 * Gets the actual shipping address md5 generated by user billing address or by shipping address of current basket if set
	 * @param $oUser current user object
	 * @return string
	 */
	private function _getShippingAddressMD5($oUser) {
		$myConfig = $this->getConfig();

		// md5 of billing address
		$md5 = md5(
			strtolower(''
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxcompany')?trim($oUser->oxuser__oxcompany->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxfname')?trim($oUser->oxuser__oxfname->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxlname')?trim($oUser->oxuser__oxlname->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxstreet')?trim($oUser->oxuser__oxstreet->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxstreetnr')?trim($oUser->oxuser__oxstreetnr->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxaddinfo')?trim($oUser->oxuser__oxaddinfo->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxzip')?trim($oUser->oxuser__oxzip->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxcity')?trim($oUser->oxuser__oxcity->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxcountryid')?trim($oUser->oxuser__oxcountryid->value):'')
				.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxstateid')?trim($oUser->oxuser__oxstateid->value):'')
			)
		);

		// get shipping address md5
		if ($sAddressId = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('deladrid')) {
			$oDeliveryAddress = oxNew(\OxidEsales\Eshop\Application\Model\Address::class);
			if ($oDeliveryAddress->load($sAddressId)) {
				if ($oDeliveryAddress) {
					$md5 = md5(
						strtolower(''
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxcompany')?trim($oDeliveryAddress->oxaddress__oxcompany->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxfname')?trim($oDeliveryAddress->oxaddress__oxfname->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxlname')?trim($oDeliveryAddress->oxaddress__oxlname->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxstreet')?trim($oDeliveryAddress->oxaddress__oxstreet->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxstreetnr')?trim($oDeliveryAddress->oxaddress__oxstreetnr->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxaddinfo')?trim($oDeliveryAddress->oxaddress__oxaddinfo->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxzip')?trim($oDeliveryAddress->oxaddress__oxzip->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxcity')?trim($oDeliveryAddress->oxaddress__oxcity->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxcountryid')?trim($oDeliveryAddress->oxaddress__oxcountryid->value):'')
							.($myConfig->getConfigParam('gw_oxid_vouchers_extended_oxstateid')?trim($oDeliveryAddress->oxaddress__oxstateid->value):'')
						)
					);
				}
			}
		}

		return $md5;
	}

	/**
	 * @return bool
	 */
	public function isTransformedToDiscount() {
		return (bool)$this->oxvouchers__gw_transformed_to_discount->value;
	}

	/**
	 * @param $value
	 */
	public function setTransformedToDiscount($value) {
		$this->oxvouchers__gw_transformed_to_discount->setValue((int)1);
	}

	/**
	 * @return mixed
	 */
	public function onlyForNotReducedArticles() {
		return $this->getSerie()->onlyForNotReducedArticles();
	}
}
?>
