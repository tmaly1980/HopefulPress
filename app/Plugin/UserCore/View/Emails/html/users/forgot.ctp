<? $userField = Configure::read("User.userField");
if(empty($userField)) { $userField = 'email'; }
?>
<h2>Create a new password</h2>

<p>We received notice that you've lost or forgotten your password.

<p>Use the link below to reset your account:

<p><?= $this->Html->link(Router::url(array('controller'=>'users','action'=>'invite',$user['User'][$userField],$user['User']['invite'], 'full_base'=>true))); ?> 
