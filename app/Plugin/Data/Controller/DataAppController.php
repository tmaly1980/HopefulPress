<?
# Plugin for generic data entries, adding multiple records, inline editing, importing via CSV, etc.
class DataAppController extends AppController
{

	var $delim = ',';

	var $components = array('Paginator','Core.Upload','Csv.Csv');

	public function user_index() {
		$this->redirect(array('user'=>null,'action'=>'index'));
	}

	function index() # Basic list.
	{
		$this->set($this->things(), $this->model()->find('all'));
	}

	function user_import_template()
	{
		header("Content-Type: application/octet-stream");
		$things = strtolower(Inflector::pluralize(Inflector::underscore($this->modelClass)));
		header("Content-Disposition: inline; filename='$things.csv'");
		echo join($this->delim, $this->model()->import_fields)."\n";
		exit(0);
	}

	function user_import() # Delivery details
	{
		# READ FILE...

		if(!empty($this->request->data[$this->modelClass]['file']))
		{
			$this->Upload->content_only(true);
			$content = $this->Upload->upload($this->request->data[$this->modelClass]['file']); # XXX, file upload component...
			$rows = $this->Csv->import($this->request->data[$this->modelClass]['file']['tmp_name'],null,array('delimiter'=>$this->delim));

			$ids = array();

			if(empty($rows))
			{
				return $this->setError("No data found in file provided.");
			}
			$keys = array_keys($rows[0][$this->modelClass]);
			# If any keys don't match field, abort...
			foreach($keys as $key)
			{
				if(!$this->model()->hasField($key))
				{
					return $this->setError("Invalid field '$key'. Improper file format. Use the template link below.");
				}
			}

			# IMPLEMENT ATOMIC IMPORT, so lines before failures ARENT added multiple times!!!!!
			$this->model()->getDataSource()->begin();

			$i = 1;
			foreach($rows as $row)
			{
				$i++;
				$this->model()->create(); # Clear id, so save into new records.
				if(!$this->model()->saveAll($row))
				{
					$this->model()->getDataSource()->rollback();
					return $this->setError("Error Line $i: ".$this->model()->errorString());
				}
				$ids[] = $this->model()->id;
			}

			$this->model()->getDataSource()->commit();
			$this->setSuccess(count($ids)." records successfully imported",array('action'=>'index','added'=>$ids));
		}
	}

	/* NOT DOING ACTUAL INLINE EDITING....
	public function admin_edit($id=null) { return $this->_edit($id); }
	public function user_edit($id=null) { return $this->_edit($id); }

	function _edit($id = null)#inline edit entry...
	{
		if(!empty($this->request->query['id']))
		{
			$id = $this->request->query['id']; # Since we might have slashes in ID...
		}
		if(!empty($this->request->data[$this->modelClass]['id']))  # In case id changes, ie Variety, etc.
		{
			$id = $this->request->data[$this->modelClass]['id'];
		}
		if(!empty($this->request->data[$this->modelClass]))
		{
			$this->model()->id = $id; # In case changes.
			# XXX does this properly change primary key if altered???

			if($this->model()->save($this->request->data))
			{
				$row = $this->model()->read();
				$id = $this->model()->id;
				$this->Json->update("{$this->modelClass}_".$this->model()->id);
				$this->set($this->thingVar(), $row);#[$this->modelClass]);
				$this->Json->script("{$this->modelClass}_$id').fadeIn();");
				$this->Json->render("view_row");
			}
			return $this->Json->error("Could not save");
		}
		$this->request->data  = $this->model()->read(null, $id);
		# HTML rendered, json form post.
	}

	public function admin_add() { return $this->_add(); }
	public function user_add() { return $this->_add(); }

	public function _add() { # XXX ALLOWS MULTIPLE ROWS
		if (!empty($this->request->data[0][$this->modelClass])) { #!empty($this->request->data)) { 
			if ($this->model()->saveAll($this->request->data)) {
				return $this->setSuccess('The records have been added.', array('action'=>'index')); # Assume first in list.
			} else {
				return $this->setError("Could not save ".$this->things().". ".$this->model()->errorString());
			}
		}

	}

	public function admin_delete($id=null) { return $this->_delete($id); }
	public function user_delete($id=null) { return $this->_delete($id); }

	public function _delete($id = null) {
		if(!empty($this->request->query['id']))
		{
			$id = $this->request->query['id']; # Since we might have slashes in ID...
		}

		$this->model()->id = $id;
		$thing = $this->model()->thing();
		if ($this->model()->delete()) {
			$this->setSuccess("The $thing has been deleted.");
		} else {
			$this->setError("The $thing could not be deleted.");
		}
		$this->redirect(array('action'=>'index'));
	}

	function view_row($id)
	{
		$row=$this->model()->read(null,$id);
		$this->set($this->thingVar(), $row);#[$this->modelClass]);
	}
	*/


}
