<?php
/**
 * @testcase
 */

namespace GPWebPayTests;

use Pixidos\GPWebPay\DI\GPWebPayExtension;
use Nette\Configurator;
use Nette\DI\Container;
use Pixidos\GPWebPay\Operation;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class GPWebPayExtensionTest extends TestCase
{

    /**
     * @var  Container $container
     */
    public $container;

    /**
     * @return Container
     */
    public function setUp()
    {
        $config = new Configurator();
        $config->setTempDirectory(TEMP_DIR);
        $config->addParameters(array('container' => array('class' => 'SystemContainer_' . md5(TEMP_DIR))));

        GPWebPayExtension::register($config);
        $config->addConfig(__DIR__ . '/../webpay.config.neon');


        $this->container = $config->createContainer();

    }

    public function testExtensionCreated()
    {
        $container = $this->container;
        $gpWebPaySettings = $container->getByType('Pixidos\GPWebPay\Settings');

        Assert::notEqual(null, $gpWebPaySettings);

    }

    public function testCreateGPWebPayResponse()
    {
        /**
         * @var \Pixidos\GPWebPay\Provider $gpWebPayProvider
         */
        $gpWebPayProvider = $this->container->getByType('Pixidos\GPWebPay\Provider');
        $operation = new Operation(123456, 100, Operation::CZK);
        $operation->setResponseUrl('http://test.com');
        $request = $gpWebPayProvider->createRequest($operation)->getRequest();
        Assert::type('Pixidos\GPWebPay\Request', $request);
    }



}

$test = new GPWebPayExtensionTest();
$test->run();
