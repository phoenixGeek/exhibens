<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Dashboard
 */
class Hella extends CI_Controller
{

  public $data = [];

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library(['ion_auth', 'form_validation']);
    $this->load->helper(['url', 'language']);
    
    $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    $this->lang->load('auth');

    if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
      echo "You are not authorized to access.";
      die();
    }
  }
  public function get_user_info($uid)
  {
    $user_data = $this->ion_auth->user($uid)->row();
    $user_data->groups = $this->ion_auth->get_users_groups($uid)->result();
    echo json_encode($user_data);
  }

  public function delete_user($uid)
  {
    if ($uid) {
      $this->ion_auth->delete_user($uid);
      echo json_encode([
        "success" => $uid,
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
      die();
    }
  }

  public function edit_user($uid)
  {

    $old_user_info = $this->ion_auth->user($uid)->row();

    $email            = $this->input->post("edit_email");
    $username         = $this->input->post("edit_username");
    $first_name       = $this->input->post("edit_first_name");
    $last_name        = $this->input->post("edit_last_name");
    $active           = $this->input->post("edit_active");
    $password         = $this->input->post("edit_password");
    $password_confirm = $this->input->post("edit_password_confirm");
    $u_gids           = $this->input->post('user_gid');



    if ($email !== $old_user_info->email) {
      $this->form_validation->set_rules('edit_email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email|is_unique[users.email]');
    } else {
      $this->form_validation->set_rules('edit_email', $this->lang->line('create_user_validation_email_label'), 'trim|valid_email');
    }

    if ($username !== $old_user_info->username) {
      $this->form_validation->set_rules('edit_username', $this->lang->line('create_user_validation_identity_label'), 'trim|is_unique[users.username]');
    } else {
      $this->form_validation->set_rules('edit_username', $this->lang->line('create_user_validation_identity_label'), 'trim');
    }

    if ($password) {
      $this->form_validation->set_rules('edit_password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[edit_password_confirm]');
      $this->form_validation->set_rules('edit_password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
    }

    $this->form_validation->set_rules('edit_first_name', $this->lang->line('create_user_validation_fname_label'), 'trim');
    $this->form_validation->set_rules('edit_last_name', $this->lang->line('create_user_validation_lname_label'), 'trim');

    if ($this->form_validation->run() === TRUE) {

      $data = array();

      if ($email) $data["email"] = $email;
      if ($username) $data["username"] = $username;
      if ($first_name) $data["first_name"] = $first_name;
      if ($last_name) $data["last_name"] = $last_name;
      if ($password) $data["password"] = $password;

      $data["active"] = $active;

      if ($this->ion_auth->update_user($uid, $data)) {
        $old_groups_ids = $this->ion_auth->get_users_groups($uid)->result();
        foreach ($old_groups_ids as $key => $g) {
          if (!in_array($g->id, $u_gids)) {
            $this->ion_auth->remove_from_group($g->id, $uid);
          }
        }
        foreach ($u_gids as $key => $gid) {
          if ($gid !== -1 && $gid !== "-1") {
            if (!$this->ion_auth->in_group($gid, $uid))
              $this->ion_auth->add_to_group($gid, $uid);
          }
        }


        $reg_data = array(
          'userdata' =>  $this->ion_auth->user($uid)->row(),
          'success' => $this->ion_auth->messages(),
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($reg_data);
      } else {
        $errors = $this->ion_auth->errors();
        echo json_encode([
          'error' => $errors,
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        ]);
      }
    } else {
      $errors = validation_errors();
      echo json_encode([
        'error' => $errors,
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
    }
  }

  public function create_user()
  {

    // validate form input
    $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
    $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
    $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[users.username]');
    $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[users.email]');


    $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
    $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');


    if ($this->form_validation->run() === TRUE) {
      $email = strtolower($this->input->post('email'));
      $username = $this->input->post('username');
      $password = $this->input->post('password');

      $u_gid = $this->input->post('user_gid');
      $additional_data = [
        'first_name' => $this->input->post('first_name'),
        'last_name' => $this->input->post('last_name')
      ];
      $uid = $this->ion_auth->register($username, $password, $email, $additional_data);

      if ($uid) {

        $reg_data = array(
          'success' => 'User added successfully.',
          'uid' => $uid,
          'username' => $username,
          'email' => $email,
          'first_name' => $additional_data["first_name"],
          'last_name' => $additional_data["last_name"],
          'active' => $this->is_user_activated($uid),
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        );

        if ($u_gid !== -1 && $u_gid !== "-1") {
          $this->ion_auth->add_to_group($u_gid, $uid);
        }

        echo json_encode($reg_data);
      } else {

        $errors = $this->ion_auth->errors();
        echo json_encode([
          'error' => $errors,
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        ]);
      }
    } else {

      $errors = validation_errors();
      echo json_encode([
        'error' => $errors,
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
    }
  }
  public function is_user_activated($uid)
  {
    return $this->ion_auth->user($uid)->row()->active;
  }
  public function create_group()
  {
    $this->form_validation->set_rules('groupname', $this->lang->line('create_group_validation_name_label'), 'trim|required|is_unique[groups.name]');
    $this->form_validation->set_rules('groupdescription', $this->lang->line('create_group_validation_desc_label'), 'trim|required');

    if ($this->form_validation->run() === TRUE) {

      $groupname = $this->input->post('groupname');
      $groupdescription = $this->input->post('groupdescription');
      $group = $this->ion_auth->create_group($groupname, $groupdescription);

      if ($group) {

        $reg_data = array(
          'success' => 'User group added successfully.',
          'gid' => $group,
          'groupname' => $groupname,
          'description' => $groupdescription,
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($reg_data);
      } else {
        $errors = $this->ion_auth->messages();
        echo json_encode([
          'error' => $errors,
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        ]);
      }
    } else {
      $errors = validation_errors();
      echo json_encode([
        'error' => $errors,
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
    }
  }

  public function delete_group($gid)
  {
    if ($gid) {
      $this->ion_auth->delete_group($gid);
      echo json_encode([
        "success" => $gid,
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
      die();
    }
  }
  public function get_g($gid)
  {
    $this->ion_auth->update_group($gid, "editor", "no desc", array());
  }

  public function edit_group($gid)
  {

    $old_group = $this->ion_auth->group($gid)->row();
    $groupname = $this->input->post('groupname');

    if ($old_group->name !== $groupname) {
      $this->form_validation->set_rules('groupname', $this->lang->line('create_group_validation_name_label'), 'trim|required|is_unique[groups.name]');
    }

    $this->form_validation->set_rules('groupdescription', $this->lang->line('create_group_validation_desc_label'), 'trim|required');

    if ($this->form_validation->run() === TRUE) {

      $groupdescription = $this->input->post('groupdescription');

      $additional_data = array(
        'description' => $groupdescription
      );

      $group_update = $this->ion_auth->update_group($gid, $groupname, $additional_data);

      if ($group_update) {

        $reg_data = array(
          'num_of_users' => count($this->ion_auth->users($gid)->result()),
          'success' => $this->ion_auth->messages(),
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($reg_data);
      } else {
        $errors = $this->ion_auth->messages();
        echo json_encode([
          'error' => $errors,
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        ]);
      }
    } else {
      $errors = validation_errors();
      echo json_encode([
        'error' => $errors,
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
    }
  }

  public function remove_user_from_group()
  {

    $uid = $this->input->post("uid");
    $gid = $this->input->post("gid");


    if ($this->ion_auth->remove_from_group($gid, $uid)) {

      $reg_data = array(
        'num_of_users' => count($this->ion_auth->users($gid)->result()),
        'success' => "removed",
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      );

      echo json_encode($reg_data);
    } else {
      $errors = $this->ion_auth->messages();
      echo json_encode([
        'error' => $errors,
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
    }
  }

  public function add_user_to_group()
  {

    $gid = $this->input->post("gid");
    $uid = $this->input->post("uid");

    $user = $this->ion_auth->user($uid)->row();



    if (!$this->ion_auth->in_group($gid, $uid)) {
      if ($this->ion_auth->add_to_group($gid, $uid)) {

        $reg_data = array(
          'num_of_users' => count($this->ion_auth->users($gid)->result()),
          'user' => $user,
          'success' => "added",
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        );

        echo json_encode($reg_data);
      } else {

        $errors = $this->ion_auth->messages();
        echo json_encode([
          'error' => $errors,
          'csrfName' => $this->security->get_csrf_token_name(),
          'csrfHash' => $this->security->get_csrf_hash()
        ]);
      }
    } else {
      echo json_encode([
        'error' => "This user is already added to the Group",
        'csrfName' => $this->security->get_csrf_token_name(),
        'csrfHash' => $this->security->get_csrf_hash()
      ]);
    }
  }

  public function create_page(){

    $this->check_request_method('post');
    $this->load->model("page_model");
    $this->form_validation->set_rules('page_title', "Title", 'trim|required|max_length[255]');
    $this->form_validation->set_rules('status', "status", 'trim|required|alpha|in_list[publish,peding,draft,trash,private]');
    $this->form_validation->set_rules('slug', "slug", 'trim|required|is_unique[pages.slug]|max_length[255]');
    
    if ($this->form_validation->run() === TRUE) {
      
      $data = array(
        "page_title"       => $this->input->post("page_title"),
        "page_description" => $this->input->post("page_description"),
        "type"             => $this->input->post("type"),
        "status"           => $this->input->post("status"),
        "slug"             => $this->input->post("slug"),
        "authorid"         => $this->ion_auth->user()->row()->id,
        "content"          => "assets/pagebuilder/new-page-blank-template.html",
        
      );
      $id = $this->page_model->create($data);
      if($id){
        $data["id"] = $id;
        $data["success"] = "added";
        $cats = (array) $this->input->post('categories');
        $page_cats = [];
        foreach ($cats as $cat) {
          $page_cats[] = [
            'page_id' => $id,
            'category_id' => $cat
          ];
        }
        $this->load->model("Categories_model", "category");
        $this->category->add_category_to_page($page_cats);
        $this->output([
          'id' => $id,
          'success' => 'added'
        ], 200);
      } else {
        $this->output([
          'error' => 'Can not create page. Please try again later'
        ], 400);
      }
    
    } else {

      $this->output([
        'error' => validation_errors()
      ], 400);

    }

  }

  public function quick_edit_page() {

    $this->check_request_method('post');
    $page_id          = $this->input->post('id');
    $page_title       = $this->input->post('page_title');
    $page_description = $this->input->post('page_description');
    $status           = $this->input->post('status');
    $slug             = $this->input->post('slug');

    $this->form_validation->set_message('edit_unique', 'This {field} is already taken');
    $this->form_validation->set_rules('page_title', "Title", 'trim|required|max_length[255]');
    $this->form_validation->set_rules('status', "Status", 'trim|required|alpha|in_list[publish,peding,draft,trash,private]');
    $this->form_validation->set_rules('slug', "Slug", 'trim|required|max_length[255]');

    if (!$this->form_validation->run()) {
      
      $this->output([
        'error' => validation_errors()
      ], 400);

    }

    $slug = $this->slugify($slug, 'pages', 'slug', $page_id);
    
    $this->load->model("page_model");
    $page = $this->page_model->get_by_field('id', $page_id);
    
    if (empty($page)) {
      $this->output([
        'error' => 'Content not found'
      ], 404);
    }
      
    $data = array(
      "page_title"       => $page_title,
      "page_description" => $page_description,
      "status"           => $status,
      "slug"             => $slug,
    );
    
    $is_updated = $this->page_model->update(['id' => $page_id], $data);
    
    if ($is_updated){

      $this->load->model("Categories_model", "category");
      $this->category->delete_category_by_page($page_id);
      $cats      = (array) $this->input->post('categories');
      $page_cats = [];
      foreach ($cats as $cat) {
        $page_cats[] = [
          'page_id'     => $page_id,
          'category_id' => $cat
        ];
      }
      $this->category->add_category_to_page($page_cats);
      $data["success"] = "Updated";
      $this->output($data);

    } else {
      $this->output([
        'error' => 'Server error. Please try again later'
      ], 500);
    }
  }


  public function delete_page()
  {
    $this->check_request_method('post');
    $this->load->model("page_model");
    $page_id = $this->input->post('id');
    $page = $this->page_model->get_by_field('id', $page_id);

    if (empty($page)) {
      $this->output([
        'error' => 'Content not found'
      ], 404);
    }
    $is_deleted = $this->page_model->delete([
      'id' => $page_id
    ]);
    if($is_deleted) {
      if ($page->content != "assets/pagebuilder/new-page-blank-template.html") {
        @unlink($page->content);
      }
      $this->load->model("categories_model", "category");
      $this->category->delete_category_by_page($page_id);
      $this->output([
        "success" => 1
      ]);
      
    }
    else {
      $this->output([
        "error" => 'Server error. Please try again later'
      ], 500);
    }
  }

  public function save_page_content() {
    $this->load->model("page_model");
    $page_id = $this->input->post('page_id');
    $page = $this->page_model->get_by_field('id', $page_id);

    if (empty($page)) {
      $this->output([
        'status' => 404,
        'error' => 'Content not found'
      ]);
    }
    $html = $this->input->post('html');
    $path = "page-content/page-$page_id.html";
    $folder = $_SERVER['DOCUMENT_ROOT'] . "/";
    $file_size = file_put_contents($folder . $path, $html);
    $this->page_model->update(
      ['id' => $page_id],
      ['content' => $path]
    );
    if ($file_size) {
      $this->output([
        'success' => 1,
      ]);
    } else {
      $this->output([
        'error' => "Page can not be saved now. Please try later",
      ]);
    }
    
  }

  /**
   * ======================================= Categories =======================================
   */

  public function sanitize_title() {

    $this->check_request_method('post');
    
    $this->form_validation->set_rules('title', "String", 'trim|required|max_length[255]');
    $this->form_validation->set_rules('type', "Type", 'trim|required|alpha|in_list[category,page]');
    $this->form_validation->set_rules('action', "Action", 'trim|required|alpha|in_list[create,edit]');
    $this->form_validation->set_rules('object_id', "Object", 'trim|required|integer');

    // Validation Failed 
    if (!$this->form_validation->run()) {
    
      $this->output([
        'error' => validation_errors()
      ], 400);
    
    }

    $title     = $this->input->post('title');
    $type      = $this->input->post('type');
    $action    = $this->input->post('action');
    $object_id = $this->input->post('object_id');
    $table     = $type === "page" ? 'pages' : 'categories';
    $slug = $this->slugify($title, $table, $action, $object_id);
    $this->output([
      'slug' => $slug
    ]);
    
  }

  public function get_category($parent_id = 0) {
    if ($parent_id < 0) {
      echo json_encode([]);die;
    }
    $this->load->model('Categories_model', 'category');
    $cats = $this->category->get_list_cats($parent_id);
    echo json_encode($cats);die;
  }

  public function create_category(){
    $this->check_request_method('post');
    $this->load->model('Categories_model', 'category');
    $name      = $this->input->post('name');
    $parent_id = (int) $this->input->post('parent_id');

    if (empty($name) || strlen($name) > 255) {
      
      $this->output([
        'status' => 400,
        'msg'    => "invalid category name"
      ]);
    
    }

    $slug = $this->slugify($name, 'categories', 'create');
    $id   = $this->category->create([
      'name'      => $name,
      'slug'      => $slug,
      'parent_id' => $parent_id
    ]);

    if ($id) {

      $this->output([
        'status' => 200,
        'cat_id' => $id
      ]);

    } else {

      $this->output([
        'status' => 500,
        'msg'    => "insert error"
      ]);

    }
  }

  public function update_category(){
    $this->load->model('Categories_model', 'category');
    $name      = $this->input->post('name');
    $id        = (int) $this->input->post('id');
    $parent_id = (int) $this->input->post('parent_id');

    if (empty($name) || strlen($name) > 255) {
      $this->output([
        'status' => 400,
        'msg'    => "invalid category name"
      ]);
    }

    if (empty($id) || $id < 1) {
      $this->output([
        'status' => 400,
        'msg'    => "invalid category id"
      ]);
    }

    $slug = $this->slugify($name, 'categories', 'edit', $id);
    $updated   = $this->category->update([
      'id' => $id
    ], [
      'name'      => $name,
      'slug'      => $slug,
      'parent_id' => $parent_id
    ]);

    if ($updated) {

      $this->output([
        'status' => 200,
        'cat_id' => $id
      ]);

    } else {

      $this->output([
        'status' => 500,
        'msg'    => "update error"
      ]);

    }
  }

  public function delete_category(){
    $this->load->model('Categories_model', 'category');
    $id = (int) $this->input->post('id');

    if (empty($id) || $id < 1) {
      $this->output([
        'status' => 400,
        'msg'    => "invalid category id"
      ]);
    }

    $is_deleted = $this->category->delete([
      'id' => $id
    ]);

    if ($is_deleted) {
      $this->category->delete([
        'parent_id' => $id
      ]);
      $this->output([
        'status' => 200,
      ]);

    } else {

      $this->output([
        'status' => 404,
        'msg'    => "Object not found"
      ]);

    }
  }
  /**
   * ======================================= End Categories =======================================
   */


  /**
   * ======================================= Common function ===================================
   */

  private function output ($data, $status = 200, $render_crsf = true) {
    if ($render_crsf) {
      $data = array_merge(
        $data,
        $this->render_new_crsf()
      );
    }
    $this->output
      ->set_status_header($status)
      ->set_content_type('application/json')
      ->set_output(json_encode($data))
      ->_display();
    exit;
  }

  private function render_new_crsf(){
    return [
      'csrfName' => $this->security->get_csrf_token_name(),
      'csrfHash' => $this->security->get_csrf_hash()
    ];
  }

  public function slugify($text, $table, $action = 'create', $object_id = 0) {
    $text = $this->remove_accents($text);
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    $temp_slug = $text;
    $index = 1;
    $where     = ['slug' => $temp_slug];

    if ($action === 'edit') {
      $where['id !='] = $object_id;
    }

    $query = $this->db->get_where($table, $where);
    while ($query->num_rows() > 0) {

      $temp_slug = "$text-$index";
      $where['slug'] = $temp_slug;
      $query = $this->db->get_where($table, $where);
      $index++;
    }

    return $temp_slug;
  }

  private function remove_accents($string)
  {
    if (!preg_match('/[\x80-\xff]/', $string)) {
      return $string;
    }

    if ($this->seems_utf8($string)) {
      $chars = array(
        // Decompositions for Latin-1 Supplement.
        'ª' => 'a',
        'º' => 'o',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'AE',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ð' => 'D',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ý' => 'Y',
        'Þ' => 'TH',
        'ß' => 's',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'ae',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'd',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ü' => 'u',
        'ý' => 'y',
        'þ' => 'th',
        'ÿ' => 'y',
        'Ø' => 'O',
        // Decompositions for Latin Extended-A.
        'Ā' => 'A',
        'ā' => 'a',
        'Ă' => 'A',
        'ă' => 'a',
        'Ą' => 'A',
        'ą' => 'a',
        'Ć' => 'C',
        'ć' => 'c',
        'Ĉ' => 'C',
        'ĉ' => 'c',
        'Ċ' => 'C',
        'ċ' => 'c',
        'Č' => 'C',
        'č' => 'c',
        'Ď' => 'D',
        'ď' => 'd',
        'Đ' => 'D',
        'đ' => 'd',
        'Ē' => 'E',
        'ē' => 'e',
        'Ĕ' => 'E',
        'ĕ' => 'e',
        'Ė' => 'E',
        'ė' => 'e',
        'Ę' => 'E',
        'ę' => 'e',
        'Ě' => 'E',
        'ě' => 'e',
        'Ĝ' => 'G',
        'ĝ' => 'g',
        'Ğ' => 'G',
        'ğ' => 'g',
        'Ġ' => 'G',
        'ġ' => 'g',
        'Ģ' => 'G',
        'ģ' => 'g',
        'Ĥ' => 'H',
        'ĥ' => 'h',
        'Ħ' => 'H',
        'ħ' => 'h',
        'Ĩ' => 'I',
        'ĩ' => 'i',
        'Ī' => 'I',
        'ī' => 'i',
        'Ĭ' => 'I',
        'ĭ' => 'i',
        'Į' => 'I',
        'į' => 'i',
        'İ' => 'I',
        'ı' => 'i',
        'Ĳ' => 'IJ',
        'ĳ' => 'ij',
        'Ĵ' => 'J',
        'ĵ' => 'j',
        'Ķ' => 'K',
        'ķ' => 'k',
        'ĸ' => 'k',
        'Ĺ' => 'L',
        'ĺ' => 'l',
        'Ļ' => 'L',
        'ļ' => 'l',
        'Ľ' => 'L',
        'ľ' => 'l',
        'Ŀ' => 'L',
        'ŀ' => 'l',
        'Ł' => 'L',
        'ł' => 'l',
        'Ń' => 'N',
        'ń' => 'n',
        'Ņ' => 'N',
        'ņ' => 'n',
        'Ň' => 'N',
        'ň' => 'n',
        'ŉ' => 'n',
        'Ŋ' => 'N',
        'ŋ' => 'n',
        'Ō' => 'O',
        'ō' => 'o',
        'Ŏ' => 'O',
        'ŏ' => 'o',
        'Ő' => 'O',
        'ő' => 'o',
        'Œ' => 'OE',
        'œ' => 'oe',
        'Ŕ' => 'R',
        'ŕ' => 'r',
        'Ŗ' => 'R',
        'ŗ' => 'r',
        'Ř' => 'R',
        'ř' => 'r',
        'Ś' => 'S',
        'ś' => 's',
        'Ŝ' => 'S',
        'ŝ' => 's',
        'Ş' => 'S',
        'ş' => 's',
        'Š' => 'S',
        'š' => 's',
        'Ţ' => 'T',
        'ţ' => 't',
        'Ť' => 'T',
        'ť' => 't',
        'Ŧ' => 'T',
        'ŧ' => 't',
        'Ũ' => 'U',
        'ũ' => 'u',
        'Ū' => 'U',
        'ū' => 'u',
        'Ŭ' => 'U',
        'ŭ' => 'u',
        'Ů' => 'U',
        'ů' => 'u',
        'Ű' => 'U',
        'ű' => 'u',
        'Ų' => 'U',
        'ų' => 'u',
        'Ŵ' => 'W',
        'ŵ' => 'w',
        'Ŷ' => 'Y',
        'ŷ' => 'y',
        'Ÿ' => 'Y',
        'Ź' => 'Z',
        'ź' => 'z',
        'Ż' => 'Z',
        'ż' => 'z',
        'Ž' => 'Z',
        'ž' => 'z',
        'ſ' => 's',
        // Decompositions for Latin Extended-B.
        'Ș' => 'S',
        'ș' => 's',
        'Ț' => 'T',
        'ț' => 't',
        // Euro sign.
        '€' => 'E',
        // GBP (Pound) sign.
        '£' => '',
        // Vowels with diacritic (Vietnamese).
        // Unmarked.
        'Ơ' => 'O',
        'ơ' => 'o',
        'Ư' => 'U',
        'ư' => 'u',
        // Grave accent.
        'Ầ' => 'A',
        'ầ' => 'a',
        'Ằ' => 'A',
        'ằ' => 'a',
        'Ề' => 'E',
        'ề' => 'e',
        'Ồ' => 'O',
        'ồ' => 'o',
        'Ờ' => 'O',
        'ờ' => 'o',
        'Ừ' => 'U',
        'ừ' => 'u',
        'Ỳ' => 'Y',
        'ỳ' => 'y',
        // Hook.
        'Ả' => 'A',
        'ả' => 'a',
        'Ẩ' => 'A',
        'ẩ' => 'a',
        'Ẳ' => 'A',
        'ẳ' => 'a',
        'Ẻ' => 'E',
        'ẻ' => 'e',
        'Ể' => 'E',
        'ể' => 'e',
        'Ỉ' => 'I',
        'ỉ' => 'i',
        'Ỏ' => 'O',
        'ỏ' => 'o',
        'Ổ' => 'O',
        'ổ' => 'o',
        'Ở' => 'O',
        'ở' => 'o',
        'Ủ' => 'U',
        'ủ' => 'u',
        'Ử' => 'U',
        'ử' => 'u',
        'Ỷ' => 'Y',
        'ỷ' => 'y',
        // Tilde.
        'Ẫ' => 'A',
        'ẫ' => 'a',
        'Ẵ' => 'A',
        'ẵ' => 'a',
        'Ẽ' => 'E',
        'ẽ' => 'e',
        'Ễ' => 'E',
        'ễ' => 'e',
        'Ỗ' => 'O',
        'ỗ' => 'o',
        'Ỡ' => 'O',
        'ỡ' => 'o',
        'Ữ' => 'U',
        'ữ' => 'u',
        'Ỹ' => 'Y',
        'ỹ' => 'y',
        // Acute accent.
        'Ấ' => 'A',
        'ấ' => 'a',
        'Ắ' => 'A',
        'ắ' => 'a',
        'Ế' => 'E',
        'ế' => 'e',
        'Ố' => 'O',
        'ố' => 'o',
        'Ớ' => 'O',
        'ớ' => 'o',
        'Ứ' => 'U',
        'ứ' => 'u',
        // Dot below.
        'Ạ' => 'A',
        'ạ' => 'a',
        'Ậ' => 'A',
        'ậ' => 'a',
        'Ặ' => 'A',
        'ặ' => 'a',
        'Ẹ' => 'E',
        'ẹ' => 'e',
        'Ệ' => 'E',
        'ệ' => 'e',
        'Ị' => 'I',
        'ị' => 'i',
        'Ọ' => 'O',
        'ọ' => 'o',
        'Ộ' => 'O',
        'ộ' => 'o',
        'Ợ' => 'O',
        'ợ' => 'o',
        'Ụ' => 'U',
        'ụ' => 'u',
        'Ự' => 'U',
        'ự' => 'u',
        'Ỵ' => 'Y',
        'ỵ' => 'y',
        // Vowels with diacritic (Chinese, Hanyu Pinyin).
        'ɑ' => 'a',
        // Macron.
        'Ǖ' => 'U',
        'ǖ' => 'u',
        // Acute accent.
        'Ǘ' => 'U',
        'ǘ' => 'u',
        // Caron.
        'Ǎ' => 'A',
        'ǎ' => 'a',
        'Ǐ' => 'I',
        'ǐ' => 'i',
        'Ǒ' => 'O',
        'ǒ' => 'o',
        'Ǔ' => 'U',
        'ǔ' => 'u',
        'Ǚ' => 'U',
        'ǚ' => 'u',
        // Grave accent.
        'Ǜ' => 'U',
        'ǜ' => 'u',
      );

      // Used for locale-specific rules.
      $chars['Ä'] = 'Ae';
      $chars['ä'] = 'ae';
      $chars['Ö'] = 'Oe';
      $chars['ö'] = 'oe';
      $chars['Ü'] = 'Ue';
      $chars['ü'] = 'ue';
      $chars['ß'] = 'ss';
      $chars['Æ'] = 'Ae';
      $chars['æ'] = 'ae';
      $chars['Ø'] = 'Oe';
      $chars['ø'] = 'oe';
      $chars['Å'] = 'Aa';
      $chars['å'] = 'aa';
      $chars['l·l'] = 'll';
      $chars['Đ'] = 'D';
      $chars['đ'] = 'd';

      $string = strtr($string, $chars);
    } else {
      $chars = array();
      // Assume ISO-8859-1 if not UTF-8.
      $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
      . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
      . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
      . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
      . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
      . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
      . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
      . "\xec\xed\xee\xef\xf1\xf2\xf3"
      . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
      . "\xfc\xfd\xff";

      $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';

      $string              = strtr($string, $chars['in'], $chars['out']);
      $double_chars        = array();
      $double_chars['in']  = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
      $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
      $string              = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
  }

  private function mbstring_binary_safe_encoding($reset = false)
  {
    static $encodings  = array();
    static $overloaded = null;

    if (is_null($overloaded)) {
      $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2); // phpcs:ignore PHPCompatibility.IniDirectives.RemovedIniDirectives.mbstring_func_overloadDeprecated
    }

    if (false === $overloaded) {
      return;
    }

    if (!$reset) {
      $encoding = mb_internal_encoding();
      array_push($encodings, $encoding);
      mb_internal_encoding('ISO-8859-1');
    }

    if ($reset && $encodings) {
      $encoding = array_pop($encodings);
      mb_internal_encoding($encoding);
    }
  }

  private function reset_mbstring_encoding()
  {
    $this->mbstring_binary_safe_encoding(true);
  }

  private function seems_utf8($str)
  {
    $this->mbstring_binary_safe_encoding();
    $length = strlen($str);
    $this->reset_mbstring_encoding();
    for ($i = 0; $i < $length; $i++) {
      $c = ord($str[$i]);
      if ($c < 0x80) {
        $n = 0; // 0bbbbbbb
      } elseif (($c & 0xE0) == 0xC0) {
        $n = 1; // 110bbbbb
      } elseif (($c & 0xF0) == 0xE0) {
        $n = 2; // 1110bbbb
      } elseif (($c & 0xF8) == 0xF0) {
        $n = 3; // 11110bbb
      } elseif (($c & 0xFC) == 0xF8) {
        $n = 4; // 111110bb
      } elseif (($c & 0xFE) == 0xFC) {
        $n = 5; // 1111110b
      } else {
        return false; // Does not match any model.
      }
      for ($j = 0; $j < $n; $j++) { // n bytes matching 10bbbbbb follow ?
        if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
          return false;
        }
      }
    }
    return true;
  }
  
  private function check_request_method($method)
  {
    // Lower case method
    $method = strtolower($method);

    if ($this->input->method(FALSE) !== $method) {

      $this->output([
        'error' => 'Can not access this source'
      ], 401, false);
    }
  }
}
 