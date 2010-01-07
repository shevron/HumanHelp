<?php

class HumanHelp_Model_Comment
{
    const FLAG_APPROVED = 1;
    
    protected $_id = null;
    
    protected $_data = array(
        'author_name'  => null,
        'author_email' => null,
        'created_at'   => null,
        'book'         => null,
        'page'         => null,
        'comment'      => null,
        'flags'        => 0,
        'token'        => null,
    );
    
    public function __construct(array $data = array())
    {
        foreach($data as $key => $value) {
            if (array_key_exists($key, $this->_data)) {
                $this->_data[$key] = $value;
            }
        }
    }

    /**
     * Save object in DB
     * 
     * @return HumanHelp_Model_Comment
     */
    public function save()
    {
        $table = new Zend_Db_Table('comments');
        if (isset($this->_id)) {
            // Update
            $table->update($this->_data, $table->getAdapter()->quoteInto('id = ?', $this->_id));
            
        } else {
            // Insert
            $table->insert($this->_data);
            $this->_id = $table->getAdapter()->lastInsertId('comments', 'id');
        }
        
        return $this;
    }
    
    /**
     * Trap any non-existing get/set calls
     * 
     * @param string $method
     * @param array  $params
     */
    public function __call($method, $params)
    {
        $action = substr($method, 0, 3);
        $datum = substr($method, 3);
        
        if (! ($datum && $action)) {
            throw new BadMethodCallException("Unexpected method call: $method");
        }

        // Convert datum from camelCase to DB-style
        $datum = strtolower(trim(preg_replace('/([A-Z])/', '_\1', $datum), '_'));
        if (! array_key_exists($datum, $this->_data)) {
            throw new BadMethodCallException("Object has no property named '$datum'");
        }
        
        switch ($action) {
            case 'get':
                return $this->_data[$datum]; 
                break;
                
            case 'set':
                if (count($params) < 1) {
                    throw new BadMethodCallException("Setter method for $datum expects data");
                }
                $this->_data[$datum] = $params[0];
                return $this;
                break;
                
            default:
                throw new BadMethodCallException("Unexpected method call: $method");
                break;
        } 
    }

    /**
     * Generate a random (hopefully?) SHA1 token
     * 
     * @return string
     */
    static public function generateToken()
    {
        $data = null;
        if (file_exists('/dev/urandom')) {
            $fp = fopen('/dev/urandom', 'r');
            $data = fgets($fp, 32);
            fclose($fp);
        }
        
        if (! $data) {
            $fData = stat(__FILE__);
            $data = (microtime(true) * rand(1, 1000)) * $fData['ino']; 
        }
        
        return sha1($data);
    }
}