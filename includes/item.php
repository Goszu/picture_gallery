<?php

require_once(LIB_PATH.DS.'database.php');

class Item {

	protected static $table_name="items";
	protected static $db_fields = array('id', 'position', 'link_txt', 'link_url', 'item_text', 'filename', 'size');

	public $id;
    public $position;
	public $link_txt;
    public $link_url;
    public $item_text;
    public $filename;
    public $size;

    private $temp_path;
    protected $upload_dir = "images";
    public $errors = array();

    protected $upload_errors = array(
        UPLOAD_ERR_OK 	    	=> "No errors.",
	    UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
        UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
        UPLOAD_ERR_NO_FILE 		=> "No file.",
        UPLOAD_ERR_NO_TMP_DIR   => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE   => "Can't write to disk.",
        UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
    );


    // Pass in $_FILE(['uploaded_file']) as an argument
    public function attach_file($file) {
		// Perform error checking on the form parameters
		if(!$file || empty($file) || !is_array($file)) {
		    // error: nothing uploaded or wrong argument usage
		    $this->errors[] = "No file was uploaded.";
		    return false;
		} elseif($file['error'] != 0) {
		    // error: report what PHP says went wrong
		    $this->errors[] = $this->upload_errors[$file['error']];
		    return false;
		} else {
			// Set object attributes to the form parameters.
		    $this->temp_path  = $file['tmp_name'];
		    $this->filename   = basename($file['name']);
		    $this->size       = $file['size'];
			return true;
		}
	}

	public function save() {
		// A new record won't have an id yet.
		if(isset($this->id)) {
			// Really just to text info
			$this->update();
		} else {
			// Make sure there are no errors
			// Can't save if there are pre-existing errors
		    if(!empty($this->errors)) { return false; }

		    // Can't save without filename and temp location
		    if(empty($this->filename) || empty($this->temp_path)) {
		        $this->errors[] = "The file location was not available.";
		        return false;
		    }

			// Determine the target_path
		    $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;

		    // Make sure a file doesn't already exist in the target location
		    if(file_exists($target_path)) {
		        $this->errors[] = "The file {$this->filename} already exists.";
		        return false;
		    }

			// Attempt to move the file
			if(move_uploaded_file($this->temp_path, $target_path)) {
		  	// Success
				// Save a corresponding entry to the database
				if($this->create()) {
					// We are done with temp_path, the file isn't there anymore
					unset($this->temp_path);
					return true;
				}
			} else {
				// File was not moved.
		        $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
		        return false;
			}
		}
	}

	public function destroy() {
		// First remove the database entry
		if($this->delete()) {
			// then remove the file
			$target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
	}
	public function image_path() {
	    return $this->upload_dir.DS.$this->filename;
	}

	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}

	// Common Database Methods
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY position");
    }

    public static function find_by_id($id=0) {
        $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_sql($sql="") {
        global $database;
        $result_set = $database->query($sql);
        $object_array = array();
        while ($row = $database->fetch_array($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

	private static function instantiate($record) {
        $object = new self;

		foreach($record as $attribute=>$value){
		    if($object->has_attribute($attribute)) {
		        $object->$attribute = $value;
		    }
		}
		return $object;
	}

	private function has_attribute($attribute) {
	    // Will return true or false
	    return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() {
		// return an array of attribute names and their values
	    $attributes = array();
	    foreach(self::$db_fields as $field) {
	        if(property_exists($this, $field)) {
	            $attributes[$field] = $this->$field;
	        }
	    }
	    return $attributes;
	}

	protected function sanitized_attributes() {
	    global $database;
	    $clean_attributes = array();
	    foreach($this->attributes() as $key => $value){
	        $clean_attributes[$key] = $database->escape_value($value);
	    }
	    return $clean_attributes;
	}

	public function create() {
		global $database;
		$attributes = $this->sanitized_attributes();
	    $sql = "INSERT INTO ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
	    $sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
	    if($database->query($sql)) {
	        $this->id = $database->insert_id();
	        return true;
	    } else {
	        return false;
	    }
	}

	public function update() {
	    global $database;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
		    $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
	    $database->query($sql);
	    return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;
	    $sql = "DELETE FROM ".self::$table_name;
	    $sql .= " WHERE id=". $database->escape_value($this->id);
	    $sql .= " LIMIT 1";
	    $database->query($sql);
	    return ($database->affected_rows() == 1) ? true : false;
	}
}

?>