# GPWebPay interface
[![Build Status](https://travis-ci.org/jaroslavtyc/granam-gpwebpay.svg?branch=master)](https://travis-ci.org/jaroslavtyc/granam-gpwebpay)
[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/granam-gpwebpay/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/granam-gpwebpay/coverage)

GPWebPay is a PHP library for online payments via [GPWebPay service](http://www.gpwebpay.cz/en)

If your are using [Nette framework](https://nette.org/en/), you may want
[Pixidos/GPWebPay](https://github.com/Pixidos/GPWebPay) Nette extension instead.

## Quickstart

### Set up & usage

```php
<?php
namespace Foo\Bar;

use Granam\GpWebPay\Settings;
use Granam\GpWebPay\DigestSigner;
use Granam\GpWebPay\Provider;
use Granam\GpWebPay\Codes\CurrencyCodes;
use Alcohol\ISO4217 as IsoCurrencies;
use Granam\GpWebPay\CardPayRequestValues;
use Granam\GpWebPay\Exceptions\Exception as GpWebPayException;

// SET UP
$settings = Settings::createForProduction(
    __DIR__ . '/foo/bar/your_private_key_downloaded_from_gp_web_pay.pem',
    'TopSecretPasswordForPrivateKey',
    __DIR__ . '/foo/bar/gp_web_pay_server_public_key_also_downloaded_from_their_server.pem',
    'https://your.eshop.url/gp_web_pay_response_catcher.php', // response URL
    123456789 // you 'merchant number', also taken from GP WebPay
);
$digestSigner = new DigestSigner($settings);
$provider = new Provider($settings, $digestSigner);
$currencyCodes = new CurrencyCodes(new IsoCurrencies());

// USE
try {
    $cardPayRequestValues = CardPayRequestValues::createFromArray($_POST, $currencyCodes);
    $cardPayRequest = $provider->createCardPayRequest($cardPayRequestValues);
} catch (GpWebPayException $exception) {
    // we are sorry, our payment gateway is temporarily unavailable (log it, solve it)
    exit();
}

?>
<html>
<body>
    <!-- some pretty recapitulation of the order -->
    <form method="post" action="<?= $cardPayRequest->getRequestUrl() ?>">
        <?php foreach ($cardPayRequest as $name => $value) {
            ?><input type="hidden" name="<?= $name ?>" value="<?= $value ?>"
        <?php } ?>
        <input type="submit" value="Confirm order">
   </form>
</body>
</html>

```

### Troubleshooting

Almost all possible error cases are covered clearly by many of exceptions, but some are so nasty so they can not be:
 - after sending a request to GP WebPay you see just a logo and HTTP response code is 401
    - probably the URL for response you provided to GP WebPay in URL parameter is not valid int he point of view of GP WebPay
        - ensure that URL exists and there is **NO redirection**, like https://www.github.com to https://github.com/ with trailing slash
        (don't believe your eyes in a browser address bar, the trailing slash is often hidden there)
        
### Covered functionality

Just a standard card payment is supported. Therefore the *Payment using digital wallet (MasterPass)* is not
possible without a fork / classes overload.

### Installation

```sh
composer require granam/gpwebpay
```
*(requires PHP **7.0+**)*

## Credits
This library originates from [Pixidos/GPWebPay](https://github.com/Pixidos/GPWebPay) library, which has same
functionality but can be used **only** as a [Nette framework](https://nette.org/en/) extension.
All credits belongs to the author Ondra Votava from Pixidos.

Nevertheless I am grateful to him for sharing that library publicly. Please more of such people.
