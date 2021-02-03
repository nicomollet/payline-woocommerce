<?php

use Payline\PaylineSDK;

/**
 * Payline module for WooCommerce
 *
 * @class          WC_Payline
 * @package        WooCommerce
 * @category       Payment Gateways
 */
class WC_Gateway_Payline extends WC_Payment_Gateway {

	private $extensionVersion = '1.4.7';
	private $SDK;
	private $disp_errors = "";
	private $testmode;
	private $admin_link = "";
	private $debug = false;
	private $logger = false;
	private $callGetMerchantSettings = true;

	const BAD_CONNECT_SETTINGS_ERR = "Unauthorized";
	const BAD_PROXY_SETTINGS_ERR = "Could not connect to host";

	var $_currencies
		= array(
			'EUR' => '978',
			// Euro
			'AFN' => '971',
			// Afghani
			'ALL' => '8',
			// Lek
			'DZD' => '12',
			// Algerian Dinar
			'USD' => '840',
			// US Dollar
			'AOA' => '973',
			// Kwanza
			'XCD' => '951',
			// East Caribbean Dollar
			'ARS' => '32',
			// Argentine Peso
			'AMD' => '51',
			// Armenian Dram
			'AWG' => '533',
			// Aruban Guilder
			'AUD' => '36',
			// Australian Dollar
			'AZN' => '944',
			// Azerbaijanian Manat
			'BSD' => '44',
			// Bahamian Dollar
			'BHD' => '48',
			// Bahraini Dinar
			'BDT' => '50',
			// Taka
			'BBD' => '52',
			// Barbados Dollar
			'BYR' => '974',
			// Belarussian Ruble
			'BZD' => '84',
			// Belize Dollar
			'XOF' => '952',
			// CFA Franc BCEAO
			'BMD' => '60',
			// Bermudian Dollar (customarily known as Bermuda Dollar)
			'INR' => '356',
			// Indian Rupee
			'BTN' => '64',
			// Ngultrum
			'BOB' => '68',
			// Boliviano
			'BOV' => '984',
			// Mvdol
			'BAM' => '977',
			// Convertible Marks
			'BWP' => '72',
			// Pula
			'NOK' => '578',
			// Norwegian Krone
			'BRL' => '986',
			// Brazilian Real
			'BND' => '96',
			// Brunei Dollar
			'BGN' => '975',
			// Bulgarian Lev
			'BIF' => '108',
			// Burundi Franc
			'KHR' => '116',
			// Riel
			'XAF' => '950',
			// CFA Franc BEAC
			'CAD' => '124',
			// Canadian Dollar
			'CVE' => '132',
			// Cape Verde Escudo
			'KYD' => '136',
			// Cayman Islands Dollar
			'CLP' => '152',
			// Chilean Peso
			'CLF' => '990',
			// Unidades de formento
			'CNY' => '156',
			// Yuan Renminbi
			'COP' => '170',
			// Colombian Peso
			'COU' => '970',
			// Unidad de Valor Real
			'KMF' => '174',
			// Comoro Franc
			'CDF' => '976',
			// Franc Congolais
			'NZD' => '554',
			// New Zealand Dollar
			'CRC' => '188',
			// Costa Rican Colon
			'HRK' => '191',
			// Croatian Kuna
			'CUP' => '192',
			// Cuban Peso
			'CYP' => '196',
			// Cyprus Pound
			'CZK' => '203',
			// Czech Koruna
			'DKK' => '208',
			// Danish Krone
			'DJF' => '262',
			// Djibouti Franc
			'DOP' => '214',
			// Dominican Peso
			'EGP' => '818',
			// Egyptian Pound
			'SVC' => '222',
			// El Salvador Colon
			'ERN' => '232',
			// Nakfa
			'EEK' => '233',
			// Kroon
			'ETB' => '230',
			// Ethiopian Birr
			'FKP' => '238',
			// Falkland Islands Pound
			'FJD' => '242',
			// Fiji Dollar
			'XPF' => '953',
			// CFP Franc
			'GMD' => '270',
			// Dalasi
			'GEL' => '981',
			// Lari
			'GHC' => '288',
			// Cedi
			'GIP' => '292',
			// Gibraltar Pound
			'GTQ' => '320',
			// Quetzal
			'GNF' => '324',
			// Guinea Franc
			'GWP' => '624',
			// Guinea-Bissau Peso
			'GYD' => '328',
			// Guyana Dollar
			'HTG' => '332',
			// Gourde
			'HNL' => '340',
			// Lempira
			'HKD' => '344',
			// Hong Kong Dollar
			'HUF' => '348',
			// Forint
			'ISK' => '352',
			// Iceland Krona
			'IDR' => '360',
			// Rupiah
			'XDR' => '960',
			// SDR
			'IRR' => '364',
			// Iranian Rial
			'IQD' => '368',
			// Iraqi Dinar
			'ILS' => '376',
			// New Israeli Sheqel
			'JMD' => '388',
			// Jamaican Dollar
			'JPY' => '392',
			// Yen
			'JOD' => '400',
			// Jordanian Dinar
			'KZT' => '398',
			// Tenge
			'KES' => '404',
			// Kenyan Shilling
			'KPW' => '408',
			// North Korean Won
			'KRW' => '410',
			// Won
			'KWD' => '414',
			// Kuwaiti Dinar
			'KGS' => '417',
			// Som
			'LAK' => '418',
			// Kip
			'LVL' => '428',
			// Latvian Lats
			'LBP' => '422',
			// Lebanese Pound
			'ZAR' => '710',
			// Rand
			'LSL' => '426',
			// Loti
			'LRD' => '430',
			// Liberian Dollar
			'LYD' => '434',
			// Libyan Dinar
			'CHF' => '756',
			// Swiss Franc
			'LTL' => '440',
			// Lithuanian Litas
			'MOP' => '446',
			// Pataca
			'MKD' => '807',
			// Denar
			'MGA' => '969',
			// Malagascy Ariary
			'MWK' => '454',
			// Kwacha
			'MYR' => '458',
			// Malaysian Ringgit
			'MVR' => '462',
			// Rufiyaa
			'MTL' => '470',
			// Maltese Lira
			'MRO' => '478',
			// Ouguiya
			'MUR' => '480',
			// Mauritius Rupee
			'MXN' => '484',
			// Mexican Peso
			'MXV' => '979',
			// Mexican Unidad de Inversion (UID)
			'MDL' => '498',
			// Moldovan Leu
			'MNT' => '496',
			// Tugrik
			'MAD' => '504',
			// Moroccan Dirham
			'MZN' => '943',
			// Metical
			'MMK' => '104',
			// Kyat
			'NAD' => '516',
			// Namibian Dollar
			'NPR' => '524',
			// Nepalese Rupee
			'ANG' => '532',
			// Netherlands Antillian Guilder
			'NIO' => '558',
			// Cordoba Oro
			'NGN' => '566',
			// Naira
			'OMR' => '512',
			// Rial Omani
			'PKR' => '586',
			// Pakistan Rupee
			'PAB' => '590',
			// Balboa
			'PGK' => '598',
			// Kina
			'PYG' => '600',
			// Guarani
			'PEN' => '604',
			// Nuevo Sol
			'PHP' => '608',
			// Philippine Peso
			'PLN' => '985',
			// Zloty
			'QAR' => '634',
			// Qatari Rial
			'ROL' => '642',
			// Old Leu
			'RON' => '946',
			// New Leu
			'RUB' => '643',
			// Russian Ruble
			'RWF' => '646',
			// Rwanda Franc
			'SHP' => '654',
			// Saint Helena Pound
			'WST' => '882',
			// Tala
			'STD' => '678',
			// Dobra
			'SAR' => '682',
			// Saudi Riyal
			'RSD' => '941',
			// Serbian Dinar
			'SCR' => '690',
			// Seychelles Rupee
			'SLL' => '694',
			// Leone
			'SGD' => '702',
			// Singapore Dollar
			'SKK' => '703',
			// Slovak Koruna
			'SIT' => '705',
			// Tolar
			'SBD' => '90',
			// Solomon Islands Dollar
			'SOS' => '706',
			// Somali Shilling
			'LKR' => '144',
			// Sri Lanka Rupee
			'SDG' => '938',
			// Sudanese Dinar
			'SRD' => '968',
			// Surinam Dollar
			'SZL' => '748',
			// Lilangeni
			'SEK' => '752',
			// Swedish Krona
			'CHW' => '948',
			// WIR Franc
			'CHE' => '947',
			// WIR Euro
			'SYP' => '760',
			// Syrian Pound
			'TWD' => '901',
			// New Taiwan Dollar
			'TJS' => '972',
			// Somoni
			'TZS' => '834',
			// Tanzanian Shilling
			'THB' => '764',
			// Baht
			'TOP' => '776',
			// Pa'anga
			'TTD' => '780',
			// Trinidad and Tobago Dollar
			'TND' => '788',
			// Tunisian Dinar
			'TRY' => '949',
			// New Turkish Lira
			'TMM' => '795',
			// Manat
			'UGX' => '800',
			// Uganda Shilling
			'UAH' => '980',
			// Hryvnia
			'AED' => '784',
			// UAE Dirham
			'GBP' => '826',
			// Pound Sterling
			'USS' => '998',
			// (Same day)
			'USN' => '997',
			// (Next day)
			'UYU' => '858',
			// Peso Uruguayo
			'UYI' => '940',
			// Uruguay Peso en Unidades Indexadas
			'UZS' => '860',
			// Uzbekistan Sum
			'VUV' => '548',
			// Vatu
			'VEB' => '862',
			// Bolivar
			'VND' => '704',
			// Dong
			'YER' => '886',
			// Yemeni Rial
			'ZMK' => '894',
			// Kwacha
			'ZWD' => '716',
			// Zimbabwe Dollar
			'XAU' => '959',
			// Gold
			'XBA' => '955',
			// Bond Markets Units European Composite Unit (EURCO)
			'XBB' => '956',
			// European Monetary Unit (E.M.U.-6)
			'XBC' => '957',
			// European Unit of Account 9(E.U.A.-9)
			'XBD' => '958',
			// European Unit of Account 17(E.U.A.-17)
			'XPD' => '964',
			// Palladium
			'XPT' => '962',
			// Platinum
			'XAG' => '961',
			// Silver
			'XTS' => '963',
			// Codes specifically reserved for testing purposes
			'XXX' => '999',
			// The codes assigned for transactions where no currency is involved
		);

	public function __construct() {

		$this->id                 = 'payline';
		$this->icon               = apply_filters( 'woocommerce_payline_icon', WCPAYLINE_PLUGIN_URL . 'assets/images/cards.png' );
		$this->has_fields         = false;
		$this->method_title       = __( 'Payline', 'tmsm-woocommerce-payline' );
		$this->method_description = __( 'Payline by Monext Payment Gateway', 'tmsm-woocommerce-payline' );
		$this->order_button_text  = __( 'Pay via Payline', 'tmsm-woocommerce-payline' );

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();



		// Define user set variables (public data)
		$this->title              = $this->settings['title'];
		$this->description        = $this->settings['description'];
		$this->testmode    = ( isset( $this->settings['ctx_mode'] ) && $this->settings['ctx_mode'] === 'TEST' );
		$this->debug       = ( isset( $this->settings['debug'] ) && $this->settings['debug'] == 'yes' ) ? true : false;

		// The module settings page URL
		$link             = add_query_arg( 'page', 'wc-settings', admin_url( 'admin.php' ) );
		$link             = add_query_arg( 'tab', 'checkout', $link );
		$link             = add_query_arg( 'section', 'payline', $link );
		$this->admin_link = $link;

		// Load the settings.
		$this->init_payline();

		// Actions

		// Reset payline admin form action
		add_action( 'payline_reset_admin_options', array( $this, 'reset_admin_options' ) );

		// Generate form action
		add_action( 'woocommerce_receipt_payline', array( $this, 'generate_payline_form' ) );

		// Update admin form action
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		// Return from payment platform action
		add_action( 'woocommerce_api_wc_gateway_payline', array( $this, 'payline_callback' ) );
	}

	function get_icon() {
		$icon = $this->icon ? '<img src="' . WC_HTTPS::force_https_url( $this->icon ) . '" alt="' . $this->title . '" />' : '';

		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}

	public function admin_options() {
		global $woocommerce;


		if ( key_exists( 'reset', $_REQUEST ) && $_REQUEST['reset'] === 'true' ) {
			do_action( 'payline_reset_admin_options' );
		}
		?>
		<img src="<?php echo WCPAYLINE_PLUGIN_URL . 'assets/images/payline.png'; ?>" alt="Payline" />

		<?php
		if ( ! empty( $woocommerce->session->payline_reset ) ) {
			unset( $woocommerce->session->payline_reset );
			echo "<div class='notice notice-success'><p>" . sprintf( __( 'Your %s configuration parameters are reset.', 'tmsm-woocommerce-payline' ),
					'Payline' ) . "</p></div>";
		}
		$this->disp_errors = "";

		if ( $this->settings['merchant_id'] == null || strlen( $this->settings['merchant_id'] ) == 0 ) {
			$this->callGetMerchantSettings = false;
			$this->disp_errors             .= "<p>" . sprintf( __( '%s is mandatory', 'tmsm-woocommerce-payline' ),
					__( 'Merchant ID', 'tmsm-woocommerce-payline' ) ) . "</p>";
		}
		if ( $this->settings['access_key'] == null || strlen( $this->settings['access_key'] ) == 0 ) {
			$this->callGetMerchantSettings = false;
			$this->disp_errors             .= "<p>" . sprintf( __( '%s is mandatory', 'tmsm-woocommerce-payline' ),
					__( 'Access Key', 'tmsm-woocommerce-payline' ) ) . "</p>";
		}

		if ( $this->settings['main_contract'] == null || strlen( $this->settings['main_contract'] ) == 0 ) {
			$this->callGetMerchantSettings = false;
			$this->disp_errors             .= "<p>" . sprintf( __( '%s is mandatory', 'tmsm-woocommerce-payline' ),
					__( 'Main contract number', 'tmsm-woocommerce-payline' ) ) . "</p>";
		}
		if ( $this->callGetMerchantSettings ) {

			if(empty($this->SDK)){
				$this->disp_errors .= "<p>" . __( 'Settings are incomplete', 'tmsm-woocommerce-payline' ) . "<p>";
			}
			else{
				$res = $this->SDK->getEncryptionKey( array() );
				if ( $res['result']['code'] == '00000' ) {
					echo "<div class='notice notice-success'>";
					echo "<p>" . __( 'Your settings is correct, connexion with Payline is established', 'tmsm-woocommerce-payline' ) . "</p>";
					if ( $this->settings['environment'] == PaylineSDK::ENV_HOMO ) {
						echo "<p>" . __( 'You are in homologation mode, payments are simulated !', 'tmsm-woocommerce-payline' ) . "<p>";
					}
					echo "</div>";
				} else {
					if ( strcmp( WC_Gateway_Payline::BAD_CONNECT_SETTINGS_ERR, $res['result']['longMessage'] ) == 0 ) {
						$this->disp_errors .= "<p>" . sprintf( __( 'Unable to connect to Payline, check your %s', 'tmsm-woocommerce-payline' ),
								__( 'Gateway Access', 'tmsm-woocommerce-payline' ) ) . "</p>";
					} elseif ( strcmp( WC_Gateway_Payline::BAD_PROXY_SETTINGS_ERR, $res['result']['longMessage'] ) == 0 ) {
						$this->disp_errors .= "<p>" . sprintf( __( 'Unable to connect to Payline, check your %s', 'tmsm-woocommerce-payline' ),
								__( 'Proxy Settings', 'tmsm-woocommerce-payline' ) ) . "</p>";
					} else {
						$this->disp_errors .= "<p>" . sprintf( __( 'Unable to connect to Payline (code %s : %s)', 'tmsm-woocommerce-payline' ),
								$res['result']['code'], $res['result']['longMessage'] ) . "</p>";
					}
				}
			}


		}

		if ( $this->disp_errors != "" ) {
			echo '<div class="notice notice-error">' . esc_html( $this->disp_errors ) . '</div>';
		}

		?>

		<table class="form-table">
			<?php
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			?>
		</table>

		<?php
		// Reset settings URL
		$resetLink = add_query_arg( 'reset', 'true', $this->admin_link );
		$resetLink = wp_nonce_url( $resetLink, 'payline_reset' );
		?>

		<a href="<?php echo $resetLink; ?>"><?php _e( 'Reset configuration', 'tmsm-woocommerce-payline' ); ?></a>

		<?php
	}

	function reset_admin_options() {
		global $woocommerce;

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'payline_reset' ) ) {
			die( 'Security check' );
		}

		@ob_clean();
		delete_option( 'woocommerce_payline_settings' );

		$woocommerce->session->payline_reset = true;

		wp_redirect( $this->admin_link );
		die();
	}

	function get_supported_languages( $all = false ) {
		$langs = array();
		if ( $all ) {
			$langs[''] = __( 'All', 'tmsm-woocommerce-payline' );
		}

		return $langs;
	}

	function get_supported_card_types() {
		$cards = array( '' => __( 'All', 'tmsm-woocommerce-payline' ) );

		return $cards;
	}

	function init_form_fields() {

		$this->form_fields = array();

		/*
		 * Base settings
		 */
		$this->form_fields['base_settings'] = array(
			'title' => __( 'Base settings', 'tmsm-woocommerce-payline' ),
			'type'  => 'title',
		);
		$this->form_fields['enabled']       = array(
			'title'   => __( 'Status', 'tmsm-woocommerce-payline' ),
			'type'    => 'checkbox',
			'label'   => sprintf( __( 'Enable %s', 'tmsm-woocommerce-payline' ), 'Payline' ),
			'default' => 'yes',
		);
		$this->form_fields['title']         = array(
			'title'       => __( 'Title', 'tmsm-woocommerce-payline' ),
			'type'        => 'text',
			'description' => __( 'This controls the title which the user sees during checkout.', 'tmsm-woocommerce-payline' ),
			'default'     => 'Payline',
		);
		$this->form_fields['description']   = array(
			'title'       => __( 'Description', 'tmsm-woocommerce-payline' ),
			'type'        => 'textarea',
			'description' => __( 'This controls the description which the user sees during checkout.', 'tmsm-woocommerce-payline' ),
			'default'     => sprintf( __( 'You will be redirected on %s secured pages at the end of your order.', 'tmsm-woocommerce-payline' ),
				'Payline' ),
		);
		$this->form_fields['debug']         = array(
			'title'       => __( 'Debug logging', 'tmsm-woocommerce-payline' ),
			'type'        => 'checkbox',
			'label'       => __( 'Enable', 'tmsm-woocommerce-payline' ),
			'default'     => 'no',
			'description' => sprintf( __( 'Log %s events, such as requests, inside <code>%s</code> folder',
				'tmsm-woocommerce-payline' ), 'Payline', 'uploads/wc-logs' ),
		);

		/*
		 * Connexion
		 */
		$this->form_fields['payline_gateway_access'] = array(
			'title' => __( 'Gateway Access', 'tmsm-woocommerce-payline' ),
			'type'  => 'title',
		);
		$this->form_fields['merchant_id']            = array(
			'title'       => __( 'Merchant ID', 'tmsm-woocommerce-payline' ),
			'type'        => 'text',
			'default'     => '',
			'description' => __( 'Your Payline account identifier', 'tmsm-woocommerce-payline' ),
		);
		$this->form_fields['access_key']             = array(
			'title'       => __( 'Access key', 'tmsm-woocommerce-payline' ),
			'type'        => 'text',
			'default'     => '',
			'description' => sprintf( __( 'Password used to call %s web services (available in the %s administration center)',
				'tmsm-woocommerce-payline' ), 'Payline', 'Payline' ),
		);
		$this->form_fields['environment']            = array(
			'title'       => __( 'Target environment', 'tmsm-woocommerce-payline' ),
			'type'        => 'select',
			'default'     => 'Homologation',
			'options'     => array(
				PaylineSDK::ENV_HOMO => __( 'Homologation', 'tmsm-woocommerce-payline' ),
				PaylineSDK::ENV_PROD => __( 'Production', 'tmsm-woocommerce-payline' ),
			),
			'description' => __( 'Payline destination environement of your requests', 'tmsm-woocommerce-payline' ),
		);

		/*
		 * Proxy Settings
		 */
		$this->form_fields['proxy_settings'] = array(
			'title' => __( 'Proxy Settings', 'tmsm-woocommerce-payline' ),
			'type'  => 'title',
		);
		$this->form_fields['proxy_host']     = array(
			'title' => __( 'Host', 'tmsm-woocommerce-payline' ),
			'type'  => 'text',
		);
		$this->form_fields['proxy_port']     = array(
			'title' => __( 'Port', 'tmsm-woocommerce-payline' ),
			'type'  => 'text',
		);
		$this->form_fields['proxy_login']    = array(
			'title' => __( 'Login', 'tmsm-woocommerce-payline' ),
			'type'  => 'text',
		);
		$this->form_fields['proxy_password'] = array(
			'title' => __( 'Password', 'tmsm-woocommerce-payline' ),
			'type'  => 'text',
		);

		/*
		 * Payment Settings
		 */
		$this->form_fields['payment_settings']    = array(
			'title' => __( 'PAYMENT SETTINGS', 'tmsm-woocommerce-payline' ),
			'type'  => 'title',
		);
		$this->form_fields['language']            = array(
			'title'       => __( 'Default language', 'tmsm-woocommerce-payline' ),
			'type'        => 'select',
			'default'     => '',
			'options'     => array(
				''   => __( 'Based on browser', 'tmsm-woocommerce-payline' ),
				'fr' => 'fr',
				'en' => 'en',
				'pt' => 'pt',
			),
			'description' => __( 'Language used to display Payline web payment pages', 'tmsm-woocommerce-payline' ),
		);
		$this->form_fields['payment_action']      = array(
			'title'       => __( 'Payment action', 'tmsm-woocommerce-payline' ),
			'type'        => 'select',
			'default'     => '',
			'options'     => array(
				'100' => __( 'Authorization', 'tmsm-woocommerce-payline' ),
				'101' => __( 'Authorization + Capture', 'tmsm-woocommerce-payline' ),
			),
			'description' => __( 'Type of transaction created after a payment', 'tmsm-woocommerce-payline' ),
		);
		$this->form_fields['custom_page_code']    = array(
			'title'       => __( 'Custom page code', 'tmsm-woocommerce-payline' ),
			'type'        => 'text',
			'description' => __( 'Code of payment page customization created in Payline Administration Center', 'tmsm-woocommerce-payline' ),
		);
		$this->form_fields['main_contract']       = array(
			'title'       => __( 'Main contract number', 'tmsm-woocommerce-payline' ),
			'type'        => 'text',
			'description' => __( 'Contract number that determines the point of sale used in Payline', 'tmsm-woocommerce-payline' ),
		);
		$this->form_fields['primary_contracts']   = array(
			'title'       => __( 'Primary contracts', 'tmsm-woocommerce-payline' ),
			'type'        => 'text',
			'description' => __( 'Contracts displayed on web payment page - step 1. Values must be separated by ;', 'tmsm-woocommerce-payline' ),
		);
		$this->form_fields['secondary_contracts'] = array(
			'title'       => __( 'Secondary contracts', 'tmsm-woocommerce-payline' ),
			'type'        => 'text',
			'description' => __( 'Contracts displayed for payment retry. Values must be separated by ;', 'tmsm-woocommerce-payline' ),
		);
	}

	function validate_multiselect_field( $key, $value = '' ) {
		$newValue = $_POST[ $this->plugin_id . $this->id . '_' . $key ];
		if ( isset( $newValue ) && is_array( $newValue ) && in_array( '', $newValue ) ) {
			return array( '' );
		} else {
			return parent::validate_multiselect_field( $key, $value );
		}
	}

	function is_available() {
		return parent::is_available();
	}

	function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		$redirect = null;
		$redirect = $this->generate_payline_form($order_id, true);

		return array(
			'result'   => 'success',
			'redirect' => $redirect,
		);
	}


	/**
	 * @param      $order_id
	 * @param bool $return_url true if return only the redirect url
	 *
	 * @return mixed
	 * @throws Exception
	 */
	function generate_payline_form( $order_id, $return_url = false ) {
		$order = wc_get_order( $order_id );

		if ( empty( $order ) ) {
			exit;
		}
		if ( empty( $this->SDK ) ) {
			exit;
		}

		$doWebPaymentRequest                              = array();
		$doWebPaymentRequest['version']                   = 22;
		$doWebPaymentRequest['payment']['amount']         = round( $order->get_total() * 100 );
		$doWebPaymentRequest['payment']['currency']       = $this->_currencies[ $order->get_currency() ];
		$doWebPaymentRequest['payment']['action']         = $this->settings['payment_action'];
		$doWebPaymentRequest['payment']['mode']           = 'CPT';
		$doWebPaymentRequest['payment']['contractNumber'] = $this->settings['main_contract'];

		/**
		 * Order data
		 * @link https://docs.payline.com/display/DT/Object+-+order
		 */
		$doWebPaymentRequest['order']['ref']      = $order->get_id();
		$doWebPaymentRequest['order']['country']  = $order->get_billing_country();
		$doWebPaymentRequest['order']['taxes']    = round( $order->get_total_tax() );
		$doWebPaymentRequest['order']['amount']   = $doWebPaymentRequest['payment']['amount'];
		$doWebPaymentRequest['order']['date']     = date( 'd/m/Y H:i' );
		$doWebPaymentRequest['order']['currency'] = $doWebPaymentRequest['payment']['currency'];

		// BUYER
		$doWebPaymentRequest['buyer']['lastName']    = $order->get_billing_last_name();
		$doWebPaymentRequest['buyer']['firstName']   = $order->get_billing_first_name();
		$doWebPaymentRequest['buyer']['customerId']  = substr( $order->get_billing_email(), 0, 50 );
		$doWebPaymentRequest['buyer']['email']       = substr( $order->get_billing_email(), 0, 150 );
		$doWebPaymentRequest['buyer']['ip']          = $_SERVER['REMOTE_ADDR'];
		$doWebPaymentRequest['buyer']['mobilePhone'] = preg_replace( "/[^0-9]/", '', $order->get_billing_phone() );

		// BILLING ADDRESS
		$doWebPaymentRequest['billingAddress']['name'] = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
		if ( $order->get_billing_company() != null && strlen( $order->get_billing_company() ) > 0 ) {
			$doWebPaymentRequest['billingAddress']['name'] .= ' (' . $order->get_billing_company() . ')';
		}
		$doWebPaymentRequest['billingAddress']['firstName'] = $order->get_billing_first_name();
		$doWebPaymentRequest['billingAddress']['lastName']  = $order->get_billing_last_name();
		$doWebPaymentRequest['billingAddress']['street1']   = $order->get_billing_address_1();
		$doWebPaymentRequest['billingAddress']['street2']   = $order->get_billing_address_2();
		$doWebPaymentRequest['billingAddress']['cityName']  = $order->get_billing_city();
		$doWebPaymentRequest['billingAddress']['zipCode']   = $order->get_billing_postcode();
		$doWebPaymentRequest['billingAddress']['country']   = $order->get_billing_country();
		$doWebPaymentRequest['billingAddress']['phone']     = preg_replace( "/[^0-9]/", '', $order->get_billing_phone() );

		// SHIPPING ADDRESS
		$doWebPaymentRequest['shippingAddress']['name'] = $order->get_shipping_first_name() . " " . $order->get_shipping_last_name();
		if ( ! empty( $order->get_shipping_company() ) ) {
			$doWebPaymentRequest['shippingAddress']['name'] .= ' (' . $order->get_shipping_company() . ')';
		}
		$doWebPaymentRequest['shippingAddress']['firstName'] = $order->get_shipping_first_name();
		$doWebPaymentRequest['shippingAddress']['lastName']  = $order->get_shipping_last_name();
		$doWebPaymentRequest['shippingAddress']['street1']   = $order->get_shipping_address_1();
		$doWebPaymentRequest['shippingAddress']['street2']   = $order->get_shipping_address_2();
		$doWebPaymentRequest['shippingAddress']['cityName']  = $order->get_shipping_city();
		$doWebPaymentRequest['shippingAddress']['zipCode']   = $order->get_shipping_postcode();
		$doWebPaymentRequest['shippingAddress']['country']   = $order->get_shipping_country();
		$doWebPaymentRequest['shippingAddress']['phone']     = preg_replace( "/[^0-9]/", '', $order->get_billing_phone() );

		// ORDER DETAILS
		$items = $order->get_items();
		foreach ( $items as $item ) {

			$this->SDK->addOrderDetail( array(
				'ref'      => substr( str_replace( array( "\r", "\n", "\t" ), array( '', '', '' ), $item['name'] ), 0, 50 ),
				'price'    => round( $item['line_total'] * 100 ),
				'quantity' => $item['qty'],
				'comment'  => '',
			) );
		}

		// TRANSACTION OPTIONS
		$doWebPaymentRequest['notificationURL']       = add_query_arg( 'wc-api', 'WC_Gateway_Payline', home_url( '/' ) );
		$doWebPaymentRequest['returnURL']             = $doWebPaymentRequest['notificationURL'];
		$doWebPaymentRequest['cancelURL']             = $doWebPaymentRequest['notificationURL'];
		$doWebPaymentRequest['languageCode']          = $this->settings['language'];
		$doWebPaymentRequest['customPaymentPageCode'] = $this->settings['custom_page_code'];

		// PRIMARY CONTRACTS
		if ( $this->settings['primary_contracts'] != null && strlen( $this->settings['primary_contracts'] ) > 0 ) {
			$contracts                        = explode( ";", $this->settings['primary_contracts'] );
			$doWebPaymentRequest['contracts'] = $contracts;
		}

		// SECONDARY CONTRACTS
		if ( $this->settings['secondary_contracts'] != null && strlen( $this->settings['secondary_contracts'] ) > 0 ) {
			$secondContracts                        = explode( ";", $this->settings['secondary_contracts'] );
			$doWebPaymentRequest['secondContracts'] = $secondContracts;
		}


		// Find other payment gateways
		$gateways = WC()->payment_gateways()->get_available_payment_gateways();
		$other_gateways = [];
		if( !empty($gateways )) {
			foreach( $gateways as $gateway ) {
				if ( $gateway->is_available() && $gateway->method_title !== __( 'Payline', 'tmsm-woocommerce-payline' )) {
					$other_gateways[] = $gateway->method_title;
				}
			}
		}

		// Execute payment
		try {
			$result = $this->SDK->doWebPayment( $doWebPaymentRequest );

			if ( $result['result']['code'] == '00000' ) {
				$order->add_meta_data('_payline_token', $result['token']);

				if ( $return_url ) {
					return $result['redirectURL'];
				} else {
					wp_redirect( $result['redirectURL'] );
				}
			} else {

				$order->add_order_note( sprintf( __( 'Can\'t redirect to payment page (error code %s: %s)',
					'tmsm-woocommerce-payline' ), $result['result']['code'], $result['result']['longMessage'] ) );

				$response = array(
					'result'   => 'failure',
					'messages' => '<div class="woocommerce-error">'.sprintf( __( 'You can\'t be redirected to payment page (error code %s: %s). Please contact us.',
							'tmsm-woocommerce-payline' ), $result['result']['code'], $result['result']['longMessage'] ).(count($other_gateways) > 0 ? '<br>'. sprintf( __( 'Or use our other payment gateways: %s', 'tmsm-woocommerce-payline' ), join(', ', $other_gateways) ) : '').'</div>' ,
				);

				wp_send_json( $response );

			}

		} catch ( Exception $e ) {

			$order->add_order_note( sprintf( __( 'Can\'t redirect to payment page (error code %s: %s)',
				'tmsm-woocommerce-payline' ), PaylineSDK::ERR_CODE, $e->getMessage() ) );

			$response = array(
				'result'   => 'failure',
				'messages' => '<div class="woocommerce-error">'.sprintf( __( 'You can\'t be redirected to payment page (error code %s: %s). Please contact us.', 'tmsm-woocommerce-payline' ), PaylineSDK::ERR_CODE, $e->getMessage() ) . (count($other_gateways) > 0 ? '<br>'. sprintf( __( 'Or use our other payment gateways: %s', 'tmsm-woocommerce-payline' ), join(', ', $other_gateways) ) : '').'</div>',
			);

			wp_send_json( $response );

		}

		exit;
	}

	/**
	 * Payline Callback
	 * @throws Exception
	 */
	function payline_callback() {
		if ( isset( $_GET['order_id'] ) ) {
			$this->generate_payline_form( $_GET['order_id'] );
			exit;
		}

		if ( isset( $_GET['token'] ) ) {
			$token = esc_html( wp_unslash( $_GET['token'] ) );
		}
		if ( isset( $_GET['paylinetoken'] ) ) {
			$token = esc_html( wp_unslash( $_GET['paylinetoken'] ) );
		}
		if ( empty( $token ) ) {
			exit;
		}

		if(empty($this->SDK)){
			exit;
		}
		$res = $this->SDK->getWebPaymentDetails( array( 'token' => $token, 'version' => '2' ) );
		if ( $res['result']['code'] == PaylineSDK::ERR_CODE ) {
			$this->log( sprintf(__( 'Unable to call Payline for token %s', 'tmsm-woocommerce-payline' ), $token), 1);
			exit;
		} else {
			$orderId       = $res['order']['ref'];
			$order         = wc_get_order( $orderId );
			if ( $order->get_payment_method() !== 'payline' ) {
				$order->add_order_note( __( 'Payment method is different than Payline', 'tmsm-woocommerce-payline' ) );
				exit;
			}

			$expected_order_token = $order->get_meta('_payline_token', true);

			if($expected_order_token !== $token){
				$message       = sprintf( __( 'Token %s does not match expected %s for order %s, updating order anyway', 'tmsm-woocommerce-payline' ),
					$token,
					$expected_order_token, $orderId );
				$order->add_order_note( $message );
			}

			if ( $res['result']['code'] === '00000' ) {

				$this->log( sprintf(__( 'Order %s was a success', 'tmsm-woocommerce-payline' ), $order->get_id()), 0);

				// Store transaction details
				if(isset($res['payment']['method'])){
					update_post_meta( (int) $orderId, '_payline_method', $res['payment']['method'] );
				}
				if(isset($res['payment']['cardBrand'])){
					update_post_meta( (int) $orderId, '_payline_cardbrand', $res['payment']['cardBrand'] );
				}
				if(isset($res['payment']['contractNumber'])){
					update_post_meta( (int) $orderId, '_payline_contract', $res['payment']['contractNumber'] );
				}

				$order->add_order_note( __( 'Payment successful', 'tmsm-woocommerce-payline' ) );
				$order->payment_complete( $res['transaction']['id'] );
				wp_safe_redirect( $this->get_return_url( $order ) );
				die();
			} elseif ( $res['result']['code'] === '04003' ) {
				$order->update_status( 'on-hold', __( 'Fraud alert. See details in Payline administration center.', 'tmsm-woocommerce-payline' ) );
				wp_safe_redirect( $this->get_return_url( $order ) );
				die();
			} elseif ( $res['result']['code'] === '02319' ) {
				$order->update_status( 'cancelled', __( 'Buyer cancelled his payment', 'tmsm-woocommerce-payline' ) );
				wp_safe_redirect( $order->get_cancel_order_url() );
				die();
			} elseif ( $res['result']['code'] === '02304' || $res['result']['code'] === '02324' ) {
				if( ! $order->is_paid()){
					$order->update_status( 'cancelled', __( 'Payment session expired without transaction', 'tmsm-woocommerce-payline' ) );
				}
				wp_safe_redirect( $order->get_cancel_order_url() );
				die();
			} elseif ( $res['result']['code'] === '02534' || $res['result']['code'] === '02324' ) {
				if( ! $order->is_paid()){
					$order->update_status( 'cancelled', __( 'Payment session expired with no redirection on payment page', 'tmsm-woocommerce-payline' ) );
				}
				wp_safe_redirect( $order->get_cancel_order_url() );
				die();
			} elseif ( $res['result']['code'] === '02306' || $res['result']['code'] === '02533' ) {
				$order->add_order_note( __( 'Payment in progress', 'tmsm-woocommerce-payline' ) );
				die( 'Payment in progress' );
			} else {
				$order->update_status( 'failed', sprintf( __( 'Payment refused (code %s): %s', 'tmsm-woocommerce-payline' ), $res['result']['code'],
					$res['result']['longMessage'] ) );
				wp_safe_redirect( $this->get_return_url( $order ) );
				die();
			}
		}


	}


	/**
	 * Init Payline SDK
	 */
	protected function init_payline()
	{
		if(
			!empty($this->settings['merchant_id'])
			&& !empty($this->settings['access_key'])
			&& !empty($this->settings['environment'])
			&& !empty($this->settings['environment'])
			&& !empty($this->settings['main_contract'])
		){

			$this->SDK = new PaylineSDK(
				$this->settings['merchant_id'],
				$this->settings['access_key'],
				$this->settings['proxy_host'],
				$this->settings['proxy_port'],
				$this->settings['proxy_login'],
				$this->settings['proxy_password'],
				$this->settings['environment'],
				WC_LOG_DIR. '/payline-'.wp_hash('payline').'-'
			);
			$this->SDK->usedBy( 'TMSM WooCommerce Payline' . $this->extensionVersion );
		}
		$this->logger = new WC_Logger();
	}

	/**
	 * Log error with WC_Logger
	 *
	 * @param $message
	 */
	protected function log($message, $is_error)
	{
		if ( empty($message) || empty( $this->logger ) ) {
			return;
		}
		if($this->debug){
			$this->logger->add('tmsm-woocommerce-payline', $message, ($is_error ? WC_Log_Levels::ERROR : WC_Log_Levels::DEBUG));
		}

	}
}