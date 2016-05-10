<?php

/**
 * Interface SqlViewFixture
 *
 * This interface should be implemented by any fixtures that refer to MySQL Views.
 *
 */

interface SqlViewFixture {

	/**
	 * @return array
	 *
	 * Must return an array with the fixtures for the tables that this view depends on.
	 */
	public function getTableFixtureDependencies();

	/**
	 * @return array
	 *
	 * Must return an array with the SqlViewFixtures for the views that this view depends on.
	 */
	public function getViewFixtureDependencies();

	/**
	 * @return String
	 *
	 * Must return the exact name of the View in the database.
	 *
	 */
	public function getViewName();

	/**
	 * @return String
	 * Must return the name of the datasource that contains the view. Usually this will be 'default'.
	 */
	public function getDataSourceName();
}