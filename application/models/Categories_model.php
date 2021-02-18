<?php
require_once ("application/core/CRUD_Model.php");

class Categories_model extends CRUD_model
{
    private $page_category;
    public function __construct()
    {
        parent::__construct();
        $this->table = 'categories';
        $this->page_category = "page_category";
    }

    /**
     * Get list categories by its parent id
     * if id = 0 => get list master categories
     * @param int $parent 
     * @return mixed array of object
     */
    public function get_list_cats($parent = 0)
    {
        $cats = $this->db
                ->select("id, name as text, 'root' as type")
                ->where(['parent_id' => $parent])
                ->get($this->table)
                ->result();
        foreach ($cats as &$cat) {
            $hasChild = count($this->db->where(['parent_id' => $cat->id])->get($this->table)->result()) > 0;
            if ($hasChild) {
                $cat->children = true;
                $cat->state = [
                    'opened' => false
                ];
            } else {
                $cat->type = 'file';
            }
        }
        return $cats;
    }

    public function get_cat_by_slug($slug) 
    {
        return $this->db->where("slug", $slug)->get($this->table)->result();
    }

    /**
     * Get all category relate to page
     * @param mixed $page_id 
     * @return mixed 
     */
    public function get_category_by_page($page_id) 
    {
        return 
            $this->db
            ->select("c.*")
            ->where(['pc.page_id' => $page_id])
            ->from($this->table . " as c")
            ->join($this->page_category . " as pc", "pc.category_id=c.id")
            ->get()
            ->result()
        ;
        // return $this->db->last_query();
    }

    /**
     * Get all category relate to page
     * @param mixed $page_id 
     * @return mixed 
     */
    public function add_category_to_page($data_set) 
    {
        if (empty($data_set)) {
            return false;
        }
        return $this->db->insert_batch($this->page_category, $data_set);
    }

    public function delete_category_by_page($page_id) 
    {
        return $this->db->delete($this->page_category, ['page_id' => $page_id]);
    }
}