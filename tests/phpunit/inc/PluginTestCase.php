

<?php
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;

/**
 * An abstraction over WP_Mock to do things fast
 * It also uses the snapshot trait
 */
class PluginTestCase extends TestCase {
	/**
	 * Setup which calls \WP_Mock setup
	 *
	 * @return void
	 */
	use MockeryPHPUnitIntegration;
	public function setUp(): void {
		parent::setUp();
        Monkey\setUp();
	}

	/**
	 * Teardown which calls \WP_Mock tearDown
	 *
	 * @return void
	 */
	public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}


