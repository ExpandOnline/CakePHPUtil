<?php

/**
 * @property ExchangeRateCalculator $_exchangeRateCalculator
 */
App::uses('Data', 'CakePHPUtil.Lib/Data');
App::uses('ExchangeRateCalculator', 'CakePHPExchangeRates.Lib');

class FinancialData extends Data {

	protected $_moneyInMicros;

	protected $_exchangeRateCalculator;

	protected $_exchangeDate;

	function __construct($moneyInMicros, DateTime $exchangeDate = null, $currencyCode = CIA_APPLICATION_CURRENCY) {
		if (!is_numeric($moneyInMicros)) {
			throw new InvalidArgumentException('MoneyInMicros must be numeric');
		}
		if (is_null($exchangeDate)) {
			$exchangeDate = new DateTime('now', new DateTimeZone(CIA_APPLICATION_TIMEZONE));
		}
		$this->_exchangeDate = $exchangeDate;
		$this->_micros = $moneyInMicros;
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
		if (!$currencyCode === $this->_currency) {
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
}