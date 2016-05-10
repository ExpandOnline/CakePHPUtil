<?php

/**
 * Interface SqlViewTest
 * 
 * This interface should be implemented by any tests that use SqlViewFixtures
 */

interface SqlViewTest {

/**
 * @return array
 * 
 * Must return a list with the SqlViewFixtures that this test uses. Fixture names should always have a
 * prefix (like regular fixtures), e.g. 'app.User' refers to app/Test/Fixtures/UserFixture.php.
 */
	public function getSqlViewFixtures();
	
}