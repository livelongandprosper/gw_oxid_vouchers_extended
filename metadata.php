<?php
/**
 * @abstract
 * @author 	Gregor Wendland <gregor@gewend.de>
 * @copyright Copyright (c) 2019, Gregor Wendland
 * @package gw
 * @version 2019-07-01
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2'; // see https://docs.oxid-esales.com/developer/en/6.0/modules/skeleton/metadataphp/version20.html

/**
 * Module information
 */
$aModule = array(
    'id'           => 'gw_oxid_vouchers_extended',
    'title'        => 'Erweiterte Gutscheinserien',
//     'thumbnail'    => 'out/admin/img/logo.jpg',
    'version'      => '1.0.0',
    'author'       => 'Gregor Wendland',
    'email'		   => 'oxid@gregor-wendland.de',
    'url'		   => 'https://www.gregor-wendland.de',
    'description'  => array(
    	'de'		=> 'Erweitert die Möglichkeiten von Gutscheinserien in OXID eShop
							<ul>
								<li>Ermöglicht, dass Gutscheine einer Gutscheinserien nur einmal pro Kunde verwendet werden können</li>
							</ul>
						',
    ),
    'extend'       => array(
		OxidEsales\Eshop\Application\Model\VoucherSerie::class => gw\gw_oxid_vouchers_extended\Application\Model\VoucherSerie::class,

    ),
    'settings'		=> array(
    ),
    'files'			=> array(
    ),
	'blocks' => array(
		// backend
		array(
			'template' => 'voucherserie_main.tpl',
			'block' => 'admin_voucherserie_main_form',
			'file' => 'Application/views/blocks/admin/admin_voucherserie_main_form.tpl'
		),
	),
	'events'       => array(
		'onActivate'   => '\gw\gw_oxid_vouchers_extended\Core\Events::onActivate',
		'onDeactivate' => '\gw\gw_oxid_vouchers_extended\Core\Events::onDeactivate'
	),
	'controllers'  => [
	],
	'templates' => [
	]
);
?>
