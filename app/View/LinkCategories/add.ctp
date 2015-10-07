<?= $this->Form->input("LinkCategory.title", array('label'=>'Category (optional)', 'label_alt'=>!empty($linkCategories)?"or <a href='/link_categories/select' class='json' data-update='LinkCategory'>Choose an existing category</a>":"")); ?>

