<?php
App::uses('AppModel', 'Model');
class BlogShare extends AppModel {
	var $actsAs = array('Tracker.Geoip','Tracker.ShareStat'); # Auto assign info...

}
