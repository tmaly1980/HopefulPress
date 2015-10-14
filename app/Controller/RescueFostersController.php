<?
App::uses('RescueVolunteersController', 'Controller');

# Inherit this since potential to change either.
class RescueFostersController extends RescueVolunteersController 
{
	var $uses  = array('RescueFoster','Foster','FosterForm','Adoptable');
	var $thing = 'foster';
	var $ucThing = 'Foster';
	var $rescueThing  = 'RescueFoster';

}
