<?php
/**
 * Created by PhpStorm.
 * User: switteveen
 * Date: 26-1-2016
 * Time: 11:52
 */


class MonthFilterBehavior extends AppBehavior {

	/**
	 * Calling this method will set default filter values for the month_start/end and year_start/end values.
	 * These values are not likely to be used as a filter directly, but first need to be converted to a start
	 * and end date.
	 *
	 * @param Model $model
	 * @param DateTime $defaultStart
	 * @param DateTime $defaultEnd
	 */
	public function setDefaultFilterMonths(Model $model, DateTime $defaultStart = null, DateTime $defaultEnd = null) {
		$model->filterArgs['year_start']['defaultValue'] =
			is_null($defaultStart) ? date('Y') : $defaultStart->format('Y');
		$model->filterArgs['month_start']['defaultValue'] =
			is_null($defaultStart) ? 1 : $defaultStart->format('m');
		$model->filterArgs['year_end']['defaultValue'] =
			is_null($defaultEnd) ? date('Y') : $defaultEnd->format('Y');
		$model->filterArgs['month_end']['defaultValue'] =
			is_null($defaultEnd) ? 1 : $defaultEnd->format('m');
	}

	/**
	 * @return DateTime
	 */
	public function getDateStart($passedArgs) {
		if ($this->_isNumericKey($passedArgs, 'year_start') && $this->_isNumericKey($passedArgs, 'month_start')) {
			return null;
		}
		return new DateTime(
			date(sprintf('%s-%s-01', $this->request->query('year_start'), $this->request->query('month_start'))));
	}

	/**
	 * @return DateTime
	 */
	public function getDateEnd($passedArgs) {
		if ($this->_isNumericKey($passedArgs, 'year_end') && $this->_isNumericKey($passedArgs, 'month_end')) {
			return null;
		}
		return new DateTime(date('Y-m-t 23:59:59',
			strtotime(sprintf('%s-%s-01', $this->request->query('year_end'), $this->request->query('month_end')))));
	}

	/**
	 * Check whether the key has a numeric value
	 * @param $args
	 * @param $key
	 *
	 * @return bool
	 */
	protected function _isNumericKey($args, $key) {
		return isset($args[$key]) && !empty($args[$key]) && is_numeric($args[$key]);
	}

}