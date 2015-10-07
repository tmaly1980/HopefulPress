<? $p  = $this->request->params; ?>
<? $model = Inflector::singularize(Inflector::classify($p['controller'])); ?>
<? $userField = Configure::read("User.userField");
if(empty($userField)) { $userField = 'email'; }
?>
<h2>A user account has been created for you</h2>

<?= !empty($message) ? "<p>$message" : "" ?>

<p>Sign in to your new account to begin: 

<p><?= $this->Html->link(Router::url(array('admin'=>null,'plugin'=>(!empty($p['plugin']) ? $p['plugin'] : null),'controller'=>$p['controller'],'action'=>'invite',$user[$model][$userField],$user[$model]['invite'],'full_base'=>true))); ?>
