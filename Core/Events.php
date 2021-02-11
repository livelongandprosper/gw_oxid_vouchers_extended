<?php
	namespace gw\gw_oxid_vouchers_extended\Core;

	use OxidEsales\Eshop\Core\DbMetaDataHandler;
	use OxidEsales\Eshop\Core\DatabaseProvider;

	class Events {
		/**
		 * add_db_key function.
		 *
		 * @access private
		 * @static
		 * @param mixed $table_name
		 * @param mixed $keyname
		 * @param mixed $column_names
		 * @param bool $unique (default: false)
		 * @return void
		 */
		private static function add_db_key($table_name, $keyname, $column_names, $unique=false) {
			// create key
			if($unique) {
				DatabaseProvider::getDb()->execute("
					ALTER TABLE  `$table_name` ADD UNIQUE  `$keyname` (  `".implode('`,`', $column_names)."` );
				");
			} else {
				DatabaseProvider::getDb()->execute("
					ALTER TABLE  `$table_name` ADD INDEX `$keyname` (  `".implode('`,`', $column_names)."` ) ;
				");
			}
		}

		/**
		 * @param $table_name
		 * @param $column_name
		 * @param $datatype
		 */
		private static function add_db_field($table_name, $column_name, $datatype) {
			$gw_head_exists = DatabaseProvider::getDb()->GetOne("SHOW COLUMNS FROM `$table_name` LIKE '$column_name'");
			if(!$gw_head_exists) {
				DatabaseProvider::getDb()->execute(
					"ALTER TABLE `$table_name` ADD `$column_name` $datatype;"
				);
			}
		}


		public static function onActivate() {
			try {
				self::add_db_field('oxvoucherseries', 'gw_only_once_per_shipping_address', "TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL COMMENT 'defines that vouchers of that series are only allowed for a single billing address'");
				self::add_db_field('oxvouchers', 'gw_shipping_address_checksum', "VARCHAR(32) DEFAULT '' NOT NULL COMMENT 'md5 checksum shipping address of the order in which the voucher was used'");
				self::add_db_field('oxvouchers', 'gw_transformed_to_discount', "TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL COMMENT '1 if voucher was transformed to regular discount when order was finalized'");
				self::add_db_field('oxvoucherseries', 'gw_only_not_reduced_articles', "TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL COMMENT 'defines that vouchers of that series are only allowed for not reduced articles'");
				self::add_db_field('oxvoucherseries', 'gw_handle_like_discount', "TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL COMMENT 'vouchers should be handled as discount when order is finished'");
				self::add_db_field('oxvoucherseries', 'gw_voucher_series_group', "VARCHAR(64) DEFAULT '' NOT NULL COMMENT 'used to group voucher series'");
				self::add_db_field('oxvoucherseries', 'gw_same_group_not_allowed', "TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL COMMENT 'vouchers are not allowed with vouchers of same series group'");

				self::add_db_key('oxvoucherseries', 'gw_voucher_series_groups', array("gw_voucher_series_group"));
				self::add_db_key('oxvoucherseries', 'gw_voucher_series_same_group_not_allowed', array("gw_same_group_not_allowed"));
			}	catch (OxidEsales\Eshop\Core\Exception\DatabaseErrorException $e) {
				// do nothing... php will ignore and continue
			}

			$oDbMetaDataHandler = oxNew(DbMetaDataHandler::class);
			$oDbMetaDataHandler->updateViews();
		}
		public static function onDeactivate() {
			$config = \OxidEsales\Eshop\Core\Registry::getConfig();
			DatabaseProvider::getDb()->execute("DELETE FROM oxtplblocks WHERE oxshopid='".$config->getShopId()."' AND oxmodule='gw_oxid_vouchers_extended';");
			DatabaseProvider::getDb()->execute("ALTER TABLE oxvoucherseries DROP INDEX gw_voucher_series_groups;");
			DatabaseProvider::getDb()->execute("ALTER TABLE oxvoucherseries DROP INDEX gw_voucher_series_same_group_not_allowed;");
			exec( "rm -f " .$config->getConfigParam( 'sCompileDir' )."/smarty/*" );
			exec( "rm -Rf " .$config->getConfigParam( 'sCompileDir' )."/*" );
			$oDbMetaDataHandler = oxNew(DbMetaDataHandler::class);
			$oDbMetaDataHandler->updateViews();
		}
	}
?>
