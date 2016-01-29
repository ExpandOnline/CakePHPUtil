<?php
App::uses('AppBehavior', 'Model/Behavior');

/**
 * Class MonthFilterBehavior
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
		if (!property_exists($model, 'filterArgs')) {
			$model->filterArgs = [];
		}
		$model->filterArgs['year_start']['defaultValue'] =
			is_null($defaultStart) ? date('Y') : $defaultStart->format('Y');
		$model->filterArgs['month_start']['defaultValue'] =
			is_null($defaultStart) ? 1 : $defaultStart->format('m');
		$model->filterArgs['year_end']['defaultValue'] =
			is_null($defaultEnd) ? date('Y') : $defaultEnd->format('Y');
		$model->filterArgs['month_end']['defaultValue'] =
			is_null($defaultEnd) ? 12 : $defaultEnd->format('m');
	}

	/**
	 * @return DateTime
	 */
	public function getDateStart($model, $passedArgs) {
		if (!$this->_isNumericKey($passedArgs, 'year_start') || !$this->_isNumericKey($passedArgs, 'month_start')) {
			return null;
		}
		$date = new DateTime(sprintf('%s-%s-01', $passedArgs['year_start'], $passedArgs['month_start']));

		unset($model->filterArgs['month_start']);
		unset($model->filterArgs['year_start']);
		return $date;
	}

	/**
	 * @return DateTime
	 */
	public function getDateEnd($model, $passedArgs) {
		if (!$this->_isNumericKey($passedArgs, 'year_end') || !$this->_isNumericKey($passedArgs, 'month_end')) {
			return null;
		}
		$date = new DateTime(sprintf('%s-%s-01 23:59:59', $passedArgs['year_end'], $passedArgs['month_end']));

		unset($model->filterArgs['month_end']);
		unset($model->filterArgs['year_end']);
		return $date;
	}

	/**
	 * @return array
	 */
	public function getMonthFilterPeriod($model, $args) {
		$period = array(
			'start' => $this->getDateStart($model, $args),
			'end' => $this->getDateEnd($model, $args),
		);
		return $period;
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