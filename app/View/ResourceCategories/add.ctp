<?= $this->Form->input("ResourceCategory.title", array('label'=>'Category (optional)', 'label_alt'=>!empty($resourceCategories)?"or <a href='/resource_categories/select' class='json' data-update='ResourceCategory'>Choose an existing category</a>":"")); ?>

