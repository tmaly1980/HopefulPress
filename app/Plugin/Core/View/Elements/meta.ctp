<? #print_r($this->request->params); ?>
<!-- app context info for javascript to properly call various ajax methods -->

<meta name="plugin" content="<?= !empty($this->request->params['plugin']) ? $this->request->params['plugin'] : "" ?>"/>
<meta name="prefix" content="<?= !empty($this->request->params['prefix']) ? $this->request->params['prefix'] : "" ?>"/>
<meta name="controller" content="<?= $this->request->params['controller'] ?>"/>
<?
$models = !empty($this->request->params['models']) ? array_keys($this->request->params['models']) : array();
?>
<meta name="model" content="<?= !empty($models) ? $models[0] : Inflector::classify($this->request->params['controller']); ?>"/>
<meta name="action" content="<?= $this->request->params['action'] ?>"/>
<meta name="page_id" content="<?= in_array($this->request->params['action'], array('view','admin_view')) ? $this->Form->id() : null ?>"/>

<!-- stuff about the user, for javascript -->
<? /*
<meta name="is_god" content="<?= $this->Admin->is_god(); ?>"/>
<meta name="is_admin" content="<?= $this->Admin->site_admin(); ?>"/>
*/ ?>

<!-- stuff for search engines, etc. -->
<meta name="description" content="<?= !empty($meta_description) ? $meta_description : null ?>"/>
<meta property="og:description" content="<?= !empty($meta_description) ? $meta_description : null ?>"/>
<meta name="keywords" content="<?= !empty($meta_keywords) ? $meta_keywords : null ?>"/>
<?= $this->Html->og_image(); # Print. ?>
