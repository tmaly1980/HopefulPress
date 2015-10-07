<? $p  = $this->request->params; ?>
<? $model = Inflector::singularize(Inflector::classify($p['controller'])); ?>
<? $userField = Configure::read("User.userField");
if(empty($userField)) { $userField = 'email'; }
?>
<h2>A webmail account has been created for you</h2>

<p>Your email address: <?= $username  ?>@<?= $domain ?>

<p>You can send/receive email using our webmail interface at:

<p><?= $this->Html->link("http://mail.$domain/"); ?>
