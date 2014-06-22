<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Base_model extends CI_Model{

    protected $version = 'BM.v.0.91';
    public $key_field    = 'id';
    public $table    = null;
    protected $fields    = array();
    protected $data = array();
    protected $item        = array();
    protected $return_as_array = FALSE;
    protected $query = FALSE;


    function __construct($table = null, $key_field = null)
    {
        parent::__construct();
        if($table != null)
        {
            $this->init($table, $key_field);
        }

        $this->db->query('SET NAMES utf8');
    }

    private function init($table, $key_field)
    {
        if ($table===null) return;
        $fields = $this->db->list_fields($table);
        foreach ($fields as $field)
        {
            $this->fields[] = $field;
        }
        $this->table = $table;
        $this->test_fields(array($key_field => ''));
        $this->key_field = $key_field;
    }

    /**
     * [as_array description]
     * @param  boolean $value [description]
     * @return [type]         [description]
     */
    public function as_array($value = TRUE)
    {
        $this->return_as_array = TRUE;
        return $this;
    }

    public function select($select_fields = FALSE, $test = TRUE)
    {
        if ($test)
        {
            // This splits the string *only* by commas, regardless of
            // how many spaces there are on either side of any comma.
            $fields_array = preg_split("/[\s]*[,][\s]*/", $select_fields);
            $fields_array = array_combine($fields_array,$fields_array );
            $this->test_fields($fields_array);
            if ( count($this->data) != count($fields_array) )
            {
                show_error('i found strange fields in select query', 500, $this->version);
                die();
            }
        }
        $this->db->select($select_fields);

        return $this;
    }

    public function query($query = FALSE)
    {
        $this->query = $query;
        return $this;
    }

    public function get_row( $id = FALSE )
    {

        $id = (int) $id;
        if($id > 0)
        {
            $this->db->where($this->key_field, $id);
        }

        if($this->query)
        {
            $query = $this->db->query($this->query);
        }
        else
        {
            $query = $this->db->get($this->table);
        }

        if($query->num_rows() < 1)
        {
            return false;
        }
        else if($query->num_rows() > 1)
        {
            show_error('i found multiply entries', 500, $this->version);
            die();
        }
        else
        {
            if ($this->return_as_array)
            {
                $this->return_as_array = FALSE;
                return $query->row_array();
            }
            else
            {
                return $query->row();
            }
        }
    }

    public function where( $searchValues=array() )
    {
        $this->test_fields($searchValues);
        if(count($this->data) >0){
            $this->db->where($this->data);
        }
        return $this;
    }

    public function get( $id = FALSE )
    {

        $id = (int) $id;
        if($id > 0)
        {
            $this->db->where($this->key_field, $id);
        }

        if($this->query)
        {
            $query = $this->db->query($this->query);
        }
        else
        {
            $query = $this->db->get($this->table);
        }


        if($query->num_rows() < 1)
        {
            return false;
        }
        if ($this->return_as_array)
        {
            $this->return_as_array = FALSE;
            return $query->result_array();
        }
        else
        {
            return $query->result();
        }
    }

    public function update(array $data, $id = null)
    {
        if($id === null)
        {
            show_error('i need key field for update', 500, $this->version);
            die();
        }
        $this->test_fields($data);

        $this->db->where($this->key_field, $id);
        return $this->db->update($this->table, $this->data);

    }

    public function delete( $id = FALSE ){
        $id = (int) $id;
        if($id > 0)
        {
            $this->db->where($this->key_field, $id);
        }
        $result = $this->db->delete($this->table);
        return $result;
    }

    public function create(array $data)
    {
        $this->test_fields($data);
        $this->db->set($this->data);
        $this->db->insert($this->table);
        return $this->db->insert_id();
    }


    // @TODO: Заменить trim на регулярку в $this->select
    protected function test_fields(array $fields)
    {
        if($this->table===null)
        {
            show_error('i need table name', 500, $this->version);
            die();
        }

        $this->data = array();

        foreach($fields as $key => $value)
        {
            if(in_array( trim($key) ,$this->fields))
            {
                // сделано для того, чтобы в запрос попадали только поля,
                // существующие в таблице

                $this->data[$key] = trim($value);
            }
        }

    }
}
