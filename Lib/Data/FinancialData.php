<?php
App::uses('Data', 'CakePHPUtil.Lib/Data');
App::uses('ExchangeRateCalculator', 'CakePHPExchangeRates.Lib');

/**
 * Class FinancialData
 */
class FinancialData extends Data {

/**
 * @var int
 */
	protected $_moneyInMicros;

/**
 * @var ExchangeRateCalculator
 */
	protected $_exchangeRateCalculator;

/**
 * @var DateTime
 */
	protected $_exchangeDate;

/**
 * @var string
 */
	protected $_currency;

/**
 * @param               $moneyInMicros
 * @param string        $currencyCode
 * @param DateTime|null $exchangeDate
 */
	public function __construct($moneyInMicros, $currencyCode = CIA_APPLICATION_CURRENCY,
		DateTime $exchangeDate = null) {
		if (!is_numeric($moneyInMicros)) {
			throw new InvalidArgumentException('MoneyInMicros must be numeric');
		}
		if (is_null($exchangeDate)) {
			$exchangeDate = new DateTime('now', new DateTimeZone(CIA_APPLICATION_TIMEZONE));
		}
		$this->_exchangeDate = $exchangeDate;
		$this->_moneyInMicros = $moneyInMicros;
		$this->_currency = $currencyCode;
		$this->_exchangeRateCalculator = new ExchangeRateCalculator();
	}

/**
 * @return string
 */
	public function getType() {
		return Data::FINANCIAL;
	}

/**
 * @param string $currencyCode
 *
 * @return float
 */
	public function getMoneyInMicros($currencyCode = CIA_APPLICATION_CURRENCY) {
		if ($currencyCode !== $this->_currency) {
			return $this->_exchangeRateCalculator->convert(
				$this->_moneyInMicros,
				$this->_currency,
				$currencyCode,
				$this->_exchangeDate
			);
		}
		return $this->_moneyInMicros;
	}

/**
 * @param string $currencyCode
 *
 * @return float
 */
	public function getMoney($currencyCode = CIA_APPLICATION_CURRENCY) {
		return $this->getMoneyInMicros($currencyCode) / 1000000;
	}

/**
 * @return DateTime|null
 */
	public function getExchangeDate() {
		return $this->_exchangeDate;
	}

/**
 * @return string
 */
	public function getCurrency() {
		return $this->_currency;
	}
}