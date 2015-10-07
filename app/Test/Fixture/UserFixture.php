<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'site_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => true),
		'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'first' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 24, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'last' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 24, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'site_id' => 1,
			'email' => 'Lorem ipsum dolor sit amet',
			'first' => 'Lorem ipsum dolor sit ',
			'last' => 'Lorem ipsum dolor sit ',
			'created' => '2014-11-25 01:18:51',
			'modified' => '2014-11-25 01:18:51'
		),
	);

}
