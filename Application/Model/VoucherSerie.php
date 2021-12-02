<?php
namespace gw\gw_oxid_vouchers_extended\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\VoucherSerie
 */
class VoucherSerie extends VoucherSerie_parent {
	private $_voucherGroupsAvailable = null;

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

	/**
	 * Generate a string that shows all available voucher series groups
	 * @return string
	 */
	public function getAvailableGroupNames() {
		if($this->_voucherGroupsAvailable === null) {
			$this->_voucherGroupsAvailable = "";
			$voucherSeriesGroups = array();
			$resultSet = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->select("
				SELECT DISTINCT
					gw_voucher_series_group
				FROM
					oxvoucherseries
				WHERE
					gw_voucher_series_group <> ''
			");
			//Fetch all at once (beware of big arrays)
			$allResults = $resultSet->fetchAll();
			foreach($allResults as $row) {
				$voucherSeriesGroups[] = $row['gw_voucher_series_group'];
			};
			if(count($voucherSeriesGroups) > 0) {
				$this->_voucherGroupsAvailable = implode(", ", $voucherSeriesGroups);
			}
		}

		return $this->_voucherGroupsAvailable;
	}
}
?>
