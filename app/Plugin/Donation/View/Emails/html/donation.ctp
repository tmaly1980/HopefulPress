<h2>New Online Donation</h2>

<p>On <?= $this->Time->mondyhm($donation['Donation']['created']); ?>, a <?= $donation['Donation']['recurring'] ? "monthly recurring":"one-time" ?> donation of <?= sprintf("$%.02f", $donation['Donation']['amount']); ?> by <?= $donation['Donation']['name'] ?> (<?= $this->Text->autolink($donation['Donation']['email']) ?>) has been sent through your website:</p>

<p><?= $this->Html->link(Router::url(array('manager'=>null,'user'=>true,'plugin'=>'donation','controller'=>'donations','action'=>'index','full_base'=>true,'#'=>'Donation'.$donation['Donation']['id']))); ?>



