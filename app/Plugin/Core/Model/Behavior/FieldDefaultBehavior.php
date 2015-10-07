<?

# If record has default values, populate (on new record).

class FieldDefaultBehavior extends ModelBehavior
{
	function beforeSave(Model $model, $options = array()) 
	{
		#error_log("FIELDDEFAULT {$model->alias}, DATA=".print_r($model->data,true));
		# Seems the issue is that singletons don't need ID's passed around but here does...
		# Maybe singleton behavior can SET id?
		if(empty($model->id) && empty($model->data[$model->alias][$model->primaryKey])) # ADDING
		{
			#error_log("ADDING, D=".print_r($model->data,true));
			$fields = $model->schema_fields();
			foreach($fields as $field)
			{
				if(in_array($fields, 
					array($model->primaryKey, "created", "modified", "updated")))
				{
					continue; # Meta fields
				}

				#error_log("{$model->alias}->$field CHECKING ");

				if(!isset($model->data[$model->alias][$field]) || $model->data[$model->alias][$field] === null || $model->data[$model->alias][$field] === '')
				{
					# Only set if func exists. otherwise leave alone.
					# FIXED leaving '0' bool alone.
					#error_log("   EMPTY $field");
					if(method_exists($model, "default_$field"))
					{
						$value = $model->{"default_$field"}();
						#error_log("    DEFAULT FIELD ($field)=$value");
						$model->data[$model->alias][$field] = $value;
					} else if (isset($model->{"default_$field"})) { # Var
						$value = $model->{"default_$field"};
						#error_log("    DEFAULT FIELD ($field)=$value");
						$model->data[$model->alias][$field] = $value;
					}

				}
				#error_log("{$model->alias}->$field DONE ");
			}

			#error_log("DATA NOW=".print_r($model->data,true));
		}
		return true;
	}

}
