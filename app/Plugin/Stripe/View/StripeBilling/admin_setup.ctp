<? $this->layout = 'plain'; ?>
<? $this->set("content_class", "width500"); ?>
<? $this->assign("title", "Payment Information"); ?>

<?= $this->element("../StripeBilling/admin_edit",array('setup'=>true)); ?>
