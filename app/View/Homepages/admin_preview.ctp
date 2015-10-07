<? $this->assign("content", $this->element("../Homepages/view")); ?>
<?= $this->element("../Layouts/components"); # Load header/nav/etc...?>
<?= $this->element("Core.../Layouts/main"); # body + loader for header/nav/etc (minus BODY wrapper) ?>
