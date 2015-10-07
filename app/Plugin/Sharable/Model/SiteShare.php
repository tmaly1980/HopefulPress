<?php
App::uses('AppModel', 'Model');
class SiteShare extends AppModel {
	var $actsAs = array('Geoip','ShareStat'); # Auto assign info...

}
