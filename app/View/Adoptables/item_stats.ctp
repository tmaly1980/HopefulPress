		<?
			$stats = array();
			if(!empty($adoptable['Adoptable']['breed'])) { 
				$breed = $adoptable['Adoptable']['breed'];
				if(!empty($adoptable['Adoptable']['breed2'])) {
					$breed .= "/".$adoptable['Adoptable']['breed2'];
				} else if (!empty($adoptable['Adoptable']['mixed_breed'])) { 
					$breed .= " (mix)";
				}
				$stats[] = $breed;
			}
			if($gender = $adoptable['Adoptable']['gender']) {
				if(!empty($adoptable['Adoptable']['neutered_spayed'])) {
					$gender .= ($gender == 'Male' ? "/Neutered" : "/Spayed");
				}
				$stats[] = $gender;
			}
			if(!empty($adoptable['Adoptable']['birthdate'])) {
				$stats[] = $this->Time->age($adoptable['Adoptable']['birthdate']);
			}
		?>
		<?= join(", ", $stats); ?>

