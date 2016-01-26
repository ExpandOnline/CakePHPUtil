<?php
/**
 * Created by PhpStorm.
 * User: switteveen
 * Date: 26-1-2016
 * Time: 11:52
 */


class MonthFilterBehavior extends AppBehavior {

	public function setDefaultFilterMonths(Model $model) {
		$model->filterArgs['year_start']['defaultValue'] = date('Y');
		$model->filterArgs['month_start']['defaultValue'] = 1;
		$model->filterArgs['year_end']['defaultValue'] = date('Y');
		$model->filterArgs['month_end']['defaultValue'] = 12;
	}

}