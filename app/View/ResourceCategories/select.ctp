<?= $this->Form->input("Resource.resource_category_id", array('label'=>'Category (optional)', 'empty'=>'- None -','options'=>$resourceCategories, 'label_alt'=>"or <a href='/resource_categories/add' class='json' data-update='ResourceCategory'>Add new category</a>")); ?>
