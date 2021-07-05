* Prevent orders to be cancelled if order already has been paid (for other statuses)

### 1.4.7 - 2021/02/03
* Absolute path for composer autoload

### 1.4.6 - 2020/12/29
* Tweak postmeta meta keys
* Prevent orders made with Payline to interfere with other payment methods
* Changed usedBy name
* Upgraded SDK to 4.59.6
* Updated API version to 22
* Updated fr_FR translation
* Changed log folder (now inside wc-logs)
* Store Payline contract, card, method in order meta
* SDK refactoring
* Use WC_Logger if debug is on

### 1.4.5 - 2019/08/15
* Display other payment gateway names when Payline has an error

### 1.4.4 - 2019/08/15
* Check token existance
* Detailed error message on checkout when payment gateway exception

### 1.4.3 - 2019/08/07
* Log an order note when there is an error generating payline form

### 1.4.2 - 2019/07/03
* Fix version number

### 1.4.1 - 2019/06/27
* Revert fixing php notices

### 1.4.0 - 2019/06/26
* Remove obsolete calls
* Check $_GET vars the right way

### 1.3.9 - 2019/01/14
* Prevent orders to be cancelled if order already has been paid

### 1.3.8 - 2018/11/13
* Remove dots in phone numbers to avoid Payline format error 02305

### 1.3.7 - 2018/11/01
* Fix checking token and expecting token
* Fix encryption key type
* Default icon for credit cards (CB+Visa+Mastercard)
* Fix wrong call to order object

### 1.3.6 - 2018/10/08
* Update vendor folder
* WooCommerce 3.0 compatibility (not compatible anymore with WooCommerce 2.x)
* Transaction ID compatibility
* Code refactoring
* Allow token to be also paylinetoken in some cases
* Fix format error when customer email > 50 chars
* Translate order notes
* Update translations

### 1.3.5 - 2017/04/04
* Feature - send buyer info mandatory for Cetelem 3x / 4x

### 1.3.4 - 2017/02/27
* Fix - languages files

### 1.3.3 - 2016/08/26
* Feature - order/token association. Prevents conflicts between payment sessions.

### 1.3.2 - 2016/08/04
* Fix - Truncate order details product name to 50 characters before send it to Payline.

### 1.3.1 - 2015/12/09
* Feature - compliance with Payline PHP library v4.43

### 1.3.0 - 2015/02/27
* Feature - compliance with wc 2.3 and over