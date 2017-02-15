<?php
/**
 * Created by PhpStorm.
 * User: Ondra Votava
 * Date: 21.10.2015
 * Time: 11:13
 */

namespace Granam\GpWebPay;

use Granam\GpWebPay\Exceptions\InvalidArgumentException;

/**
 * Class Request
 * @package Granam\GpWebPay
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
class Request
{

	/**
	 * @var  Operation $operation
	 */
	private $operation;

	/**
	 * @var string $url
	 */
	private $url;

	/**
	 * @var  int $merchantNumber
	 */
	private $merchantNumber;

	/**
	 * @var  int $depositFlag
	 */
	private $depositFlag;

	/**
	 * @var array $params
	 */
	private $params;

	/**
	 *
	 * @var array $digestParamsKeys
	 */
	private $digestParamsKeys = array(
		'MERCHANTNUMBER',
		'OPERATION',
		'ORDERNUMBER',
		'AMOUNT',
		'CURRENCY',
		'DEPOSITFLAG',
		'MERORDERNUM',
		'URL',
		'DESCRIPTION',
		'MD',
		'USERPARAM1',
		'FASTPAYID',
		'PAYMETHOD',
		'DISABLEPAYMETHOD',
		'PAYMETHODS',
		'EMAIL',
		'REFERENCENUMBER',
		'ADDINFO',
	);

	/**
	 * @param Operation $operation
	 * @param $merchantNumber
	 * @param $depositFlag
	 * @throws InvalidArgumentException
	 */
	public function __construct(Operation $operation, $merchantNumber, $depositFlag)
	{
		$this->operation = $operation;
		if (!$this->url = $operation->getResponseUrl()) {
			throw new InvalidArgumentException('Response URL in Operation must by set!');
		}

		$this->merchantNumber = $merchantNumber;
		$this->depositFlag = $depositFlag;

		$this->setParams();

	}

	/**
	 * Sets params to array
	 */
	private function setParams()
	{
		$this->params['MERCHANTNUMBER'] = $this->merchantNumber;
		$this->params['OPERATION'] = 'CREATE_ORDER';
		$this->params['ORDERNUMBER'] = $this->operation->getOrderNumber();
		$this->params['AMOUNT'] = $this->operation->getAmount();
		$this->params['CURRENCY'] = $this->operation->getCurrency();
		$this->params['DEPOSITFLAG'] = $this->depositFlag;
		if ($this->operation->getMerOrderNum()) {
			$this->params['MERORDERNUM'] = $this->operation->getMerOrderNum();
		}
		$this->params['URL'] = $this->url;

		if ($this->operation->getDescription()) {
			$this->params['DESCRIPTION'] = $this->operation->getDescription();
		}
		if ($this->operation->getMd()) {
			$this->params['MD'] = $this->operation->getMd();
		}
		if ($this->operation->getLang()) {
			$this->params['LANG'] = $this->operation->getLang();
		}
		if ($this->operation->getUserParam1()) {
			$this->params['USERPARAM1'] = $this->operation->getUserParam1();
		}
		if ($this->operation->getPayMethod()) {
			$this->params['PAYMETHOD'] = $this->operation->getPayMethod();
		}
		if ($this->operation->getDisablePayMethod()) {
			$this->params['DISABLEPAYMETHOD'] = $this->operation->getDisablePayMethod();
		}
		if ($this->operation->getPayMethods()) {
			$this->params['PAYMETHODS'] = $this->operation->getPayMethods();
		}
		if ($this->operation->getEmail()) {
			$this->params['EMAIL'] = $this->operation->getEmail();
		}
		if ($this->operation->getReferenceNumber()) {
			$this->params['REFERENCENUMBER'] = $this->operation->getReferenceNumber();
		}
		if ($this->operation->getFastPayId()) {
			$this->params['FASTPAYID'] = $this->operation->getFastPayId();
		}

	}

	/**
	 * @param string $digest
	 * @internal
	 */
	public function setDigest($digest)
	{
		$this->params['DIGEST'] = $digest;
	}

	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 *
	 * @return array
	 */
	public function getDigestParams()
	{
		return array_intersect_key($this->params, array_flip($this->digestParamsKeys));
	}

}