<?php
/**
 * Bake Template for Controller action generation.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.actions
 * @since         CakePHP(tm) v 1.3
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>

	public function <?php echo $admin ?>index() {
		$this-><?php echo $currentModelName ?>->recursive = 1;
		$this->set('<?php echo $pluralName ?>', $this->Paginator->paginate());
	}

	public function <?php echo $admin ?>view($id = null) {
		if (empty($id) || !$this-><?php echo $currentModelName; ?>->exists($id)) {
			return $this->notFound();
		}
		$this->set('<?php echo $singularName; ?>', $this->read($id));
	}

<?php $compact = array(); ?>
	public function <?php echo $admin; ?>edit($id = null) {
		if (!empty($id) && !$this-><?php echo $currentModelName; ?>->exists($id)) {
			return $this->notFound();
		}
		if (!empty($this->request->data)) { 
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
				$this->setSuccess('The <?php echo strtolower($singularHumanName); ?> has been saved.',array('action'=>'index'));
			} else {
				$this->setError('The <?php echo strtolower($singularHumanName); ?> could not be saved.');
			}
		} else if(!empty($id)) {
			$this->request->data = $this->read($id);
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

/**
 * <?php echo $admin ?>delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function <?php echo $admin; ?>delete($id = null) {
		$this-><?php echo $currentModelName; ?>->id = $id;
		if (!$this-><?php echo $currentModelName; ?>->exists()) {
			return $this->notFound();
		}
		#$this->request->onlyAllow('post', 'delete');
		if ($this-><?php echo $currentModelName; ?>->delete()) {
			return $this->setSuccess('The <?php echo strtolower($singularHumanName); ?> has been deleted.', array('action'=>'index'));
		} else {
			return $this->setError('The <?php echo strtolower($singularHumanName); ?> could not be deleted.',array('action'=>'index'));
		}
	}
