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
    'version'      => '1.3.0',
    'author'       => 'Gregor Wendland',
    'email'		   => 'oxid@gregor-wendland.de',
    'url'		   => 'https://www.gregor-wendland.com',
    'description'  => array(
    	'de'		=> 'Erweitert die Möglichkeiten von Gutscheinserien in OXID eShop
							<ul>
								<li>Ermöglicht, dass Gutscheine einer Gutscheinserien nur einmal pro Liefer-Adresse verwendet werden können</li>
								<li>Ermöglicht, dass Gutscheine auch im letzten Bestellschritt eingegeben werden können (dabei findet eine Weiterleitung auf die Warenkorb-Seite statt, was aber durch ein einfaches JavaScript, dass den Prozess per AJAX realsiiert, für die Benutzer-Ansicht umgegangen werden kann)</li>
								<li>Ermöglicht, dass Gutscheine auch im vorletzten Bestellschritt (Zahlung und Versand) eingegeben werden können</li>
								<li>Ermöglicht, dass Gutscheine auch im letzten Bestellschritt (Besellung überprüfen) eingegeben werden können</li>
								<li>Gutscheinserien können so eingestellt werden, dass sie nicht auf rabattierte Artikel (UVP > Preis) angewendet werden.</li>
								<li>Gutscheine können am Ende der Bestellung als Rabatt verrechnet werden.</li>
							</ul>
						',
    ),
    'extend'       => array(
		OxidEsales\Eshop\Application\Model\Discount::class => gw\gw_oxid_vouchers_extended\Application\Model\Discount::class,
		OxidEsales\Eshop\Application\Model\Voucher::class => gw\gw_oxid_vouchers_extended\Application\Model\Voucher::class,
		OxidEsales\Eshop\Application\Model\VoucherSerie::class => gw\gw_oxid_vouchers_extended\Application\Model\VoucherSerie::class,
    ),
    'settings'		=> array(
    	// Allgemein
		array('group' => 'gw_oxid_vouchers_extended', 'name' => 'gw_oxid_vouchers_extended_hide_form_in_basket', 'type' => 'bool', 'value' => '1'),

		// Adressfelder, die zur Berechnung der Checksumme herangezogen werden sollen
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxcompany', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxfname', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxlname', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxstreet', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxstreetnr', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxaddinfo', 'type' => 'bool', 'value' => '0'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxzip', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxcity', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxcountryid', 'type' => 'bool', 'value' => '1'),
		array('group' => 'gw_oxid_vouchers_extended_address_fields', 'name' => 'gw_oxid_vouchers_extended_oxstateid', 'type' => 'bool', 'value' => '0'),
    ),
    'files'			=> array(
    ),
	'blocks' => array(
		// frontend
		array(
			'template' => 'page/checkout/order.tpl',
			'block' => 'checkout_order_vouchers',
			'file' => 'Application/views/blocks/checkout_order_vouchers.tpl'
		),
		array(
			'template' => 'page/checkout/payment.tpl',
			'block' => 'checkout_payment_main',
			'file' => 'Application/views/blocks/checkout_payment_vouchers.tpl'
		),
		array(
			'template' => 'layout/base.tpl',
			'block' => 'base_style',
			'file' => 'Application/views/blocks/base_style.tpl'
		),

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
