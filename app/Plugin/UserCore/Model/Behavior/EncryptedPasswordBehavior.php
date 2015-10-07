<?
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class EncryptedPasswordBehavior extends ModelBehavior
{

	function beforeSave(Model $model, $options = array())
	{
		$passwordField = Configure::read("User.passwordField");
		if(empty($passwordField)) { $passwordField = 'password'; }
		if(!empty($model->data[$model->alias][$passwordField]))
		{
			$pwenc = $model->data[$model->alias][$passwordField] = $model->hash($model->data[$model->alias][$passwordField]);
		}
		return true;
	}
	function hash($model, $pw)
	{
		$passwordHasher = new SimplePasswordHasher(array('hashType'=>'sha256'));
		return $passwordHasher->hash($pw);
	}

}
