<? $userField = Configure::read("User.userField");
if(empty($userField)) { $userField = 'email'; }
?>
<h2>Reply to <?= $discussion['Discussion']['title'] ?></h2>

<p>A reply has been sent by <?= $message['User']['full_name'] ?>

<p><?= $this->Text->truncate($message['Message']['message']); ?>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>1,'plugin'=>'forum','controller'=>'discussions','action'=>'view',$discussion['Discussion']['id'],'#message_'.$message['Message']['id'],'full_base'=>true))); ?>
