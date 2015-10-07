<?php
App::uses('AppModel', 'Model');
class SiteShare extends AppModel {
	var $actsAs = array('Tracker.Geoip','Tracker.ShareStat'); # Auto assign info...

}
