<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Menus extends Hosting_Billing
{
        public $temp = array();
   
        function __construct()
        {
            parent::__construct();
            User::logged_in();     
                 
            $this->load->module('layouts');
            $this->load->library(array('template'));  
            $this->load->helper('url'); 
        }



        public function add_menu() {
            if (isset($_POST['title'])) {
                $data['title'] = $this->input->post('title');
                if (!empty($data['title'])) {
                    Applib::is_demo();
                    if ($this->db->insert('menu_group', $data)) {
                    $id = $this->db->insert_id();  
                    $data = array (
                        'name' => $this->input->post('title'),
                        'param' => 'menus_'.$id,
                        'type' => 'Module',
                        'module' => 'Menus'
                    ); 
                    $this->db->insert('blocks_modules', $data);                
                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('menu_created'));
                    redirect(base_url().'menus/menu/'.$id);
                    }
                }

            } else {
               $this->template->title(lang('menu').' - '.config_item('company_name'));
               $data['page'] = lang('menu');
               $this->template
               ->set_layout('users')
               ->build('add_menu',isset($data) ? $data : NULL);	
            }
        }
    
        public function edit_menu() {
            $id = $this->input->post('id');
            $title = $this->input->post('title');
            if ($title) {
                Applib::is_demo();
                $data['title'] = $title;
                $response['success'] = false;
                $res = $this->Menu->update_menu_group($data, $id);
                if ($res) {

                    if($this->db->where('param', "menus_".$id)->where('module','Menus')->get('blocks_modules')->num_rows() == '0'){
                        $data = array (
                            'name' => $title,
                            'param' =>  'menus_'.$id,
                            'type' => 'Module',
                            'module' => 'Menus'
                        ); 
                        App::save_data('blocks_modules', $data);
                    }
                    else 
                    {
                        $data = array (
                            'name' => $title
                        );
                        $this->db->where('param', "menus_".$id);
                        $this->db->update('blocks_modules', $data);
                    }


                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('operation_successfull'));
                    redirect($_SERVER['HTTP_REFERER']);
                }
                    $this->session->set_flashdata('response_status', 'warning');
                    $this->session->set_flashdata('message', lang('operation_failed'));
                    redirect($_SERVER['HTTP_REFERER']);
                }
        }
    
        public function delete_menu() {
            $id = $this->input->post('id');
            if ($id) {
                Applib::is_demo();
                if ($id == 1) {
                    $response['success'] = false;
                    $response['msg'] = 'Cannot delete Group ID = 1';
                } else {
                    $delete = $this->Menu->delete_menu_group($id);
                    if ($delete) {

                        $this->db->where('param', "menus_".$id)->where('module', 'Menus')->delete('blocks_modules');
                        $block = $this->db->where('id', 'menus_'.$id)->where('module', 'Menus')->get('blocks')->row();
                        if($block) {
                            $this->db->where('id', 'menus_'.$id)->where('module', 'Menus')->delete('blocks');
                            $this->db->where('block_id', $block->block_id)->delete('blocks_pages');
                        }                       

                        $del = $this->Menu->delete_menus($id);
                        $response['success'] = true;
                    } else {
                        $response['success'] = false;
                    }
                }
                header('Content-type: application/json');
                echo json_encode($response);
            }
        }

    
        /**
         * Show menu Menu
         */
        public function index()
        {
            
            if (User::is_client()) {
                Applib::go_to('clients', 'error', lang('access_denied'));
            }	

            $group_id = 1;
            $menu = $this->Menu->get_menu($group_id);
            $data['menu_ul'] = '<ul id="easymm"></ul>';
            if ($menu) {
                foreach ($menu as $row) {
                    $this->add_row(
                        $row->id, $row->parent_id, ' id="menu-' . $row->id . '" class="sortable "', $this->get_label($row)
                    ); 
                }
             
                $data['menu_ul'] = $this->generate_list('id="easymm"');
            }

            $data['group_id'] = $group_id;
            $data['group_title'] = $this->Menu->get_menu_group_title($group_id)->title;
            $data['menu_groups'] = $this->Menu->get_menu_groups();                
            $this->template->title(lang('menu').' - '.config_item('company_name'));
            $data['page'] = lang('menu');  
            $data['menus'] = true; 

            $this->template
            ->set_layout('users')
            ->build('menus',isset($data) ? $data : NULL);		
        }
    
        /**
         * Show menu pages
         */
        public function menu($group_id)
        {
            $menu = $this->Menu->get_menu($group_id);
            //echo "<pre>".print_r($menu,true);die();
            $data['menu_ul'] = '<ul id="easymm"></ul>';
            if ($menu) {
                foreach ($menu as $row) {
                    $this->add_row(
                        $row->id, $row->parent_id, ' id="menu-' . $row->id . '" class="sortable"', $this->get_label($row)
                    );
                }
    
                $data['menu_ul'] = $this->generate_list('id="easymm"');
            }
            
            $data['group_id'] = $group_id;
            $data['group_title'] = $this->Menu->get_menu_group_title($group_id)->title;
            $data['menu_groups'] = $this->Menu->get_menu_groups();
            $data['page'] = $data['group_title'];  
            $data['menus'] = true;
            $this->template
            ->set_layout('users')
            ->build('menus',isset($data) ? $data : NULL); 
        }
    
        /**
         * Generates nested lists
         *
         * @param string $ul_attr
         * @return string
         */
        function generate_list($ul_attr = '')
        {
            return $this->ul(0, $ul_attr);
        }


        function activate($id)
        {         
            return $this->db->where('id', $id)->update('menu', array('active' => $this->input->post('active')));
        }
    
        /**
         * Recursive method for generating nested lists
         *
         * @param int $parent
         * @param string $attr
         * @return string
         */
        function ul($parent = 0, $attr = '')
        {        
            static $i = 1;
            $indent = str_repeat("\t\t", $i);
            if (isset($this->temp[$parent])) {
                if ($attr) {
                    $attr = ' ' . $attr;
                }
                $html = "\n$indent";
                $html .= "<ul$attr>";
                $i++;
                foreach ($this->temp[$parent] as $row) {
                    $child = $this->ul($row['id']);
                    $html .= "\n\t$indent";
                    $html .= '<li' . $row['li_attr'] . '>';
                    $html .= $row['label'];
                    if ($child) {
                        $i--;
                        $html .= $child;
                        $html .= "\n\t$indent";
                    }
                    $html .= '</li>';
                }
                $html .= "\n$indent</ul>";
                return $html;
            } else {
                return false;
            }
        }
    
        function add_row($id, $parent, $li_attr, $label)
        {
                $this->temp[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
        }
    
        /**
         * Add menu item action
         * For use with ajax
         * Return json data
         */
        public function add()
        {   $data = array();
            $title = $this->input->post('title');
            if ($title) {
                Applib::is_demo();
                $data['title'] = $this->input->post('title');
                if (!empty($data['title'])) {
                    $data['url'] = $this->input->post('url');
                    $data['active'] = 1;
                    //$data['class'] = $this->input->post('class');
                    $data['group_id'] = $this->input->post('group_id');
                    if ($this->db->insert('menu', $data)) {
                        $data['id'] = $this->db->insert_id();
                        $response['status'] = 1;
                        $li_id = 'menu-' . $data['id'];
                        $response['li'] = '<li id="' . $li_id . '" class="sortable">' . $this->get_labels($data) . '</li>';
                        $response['li_id'] = $li_id;
                    } else {
                        $response['status'] = 2;
                        $response['msg'] = 'Add menu error.';
                    }
                } else {
                    $response['status'] = 3;
                }
                header('Content-type: application/json');
                echo json_encode($response);
            }
        }
    
        public function edit($id)
        {
            $data['row'] = $this->Menu->get_row($id);
            $data['menu_groups'] = $this->Menu->get_menu_groups();
            $this->load->view('menu_edit', $data);
        }

    
        public function save()
        {
            $title = $this->input->post('title');
            if ($title) {
                Applib::is_demo();
                $data['title'] = trim($_POST['title']);
                if (!empty($data['title'])) {
                    $data['id'] = $this->input->post('menu_id');
                    $data['url'] = $this->input->post('url');
    //                $data['class'] = $this->input->post('class');
    
                    $item_moved = false;
                    $group_id = $this->input->post('group_id');
                    if ($group_id) {
                        $group_id = $this->input->post('group_id');
                        $old_group_id = $this->input->post('old_group_id');
    
                        //if group changed
                        if ($group_id != $old_group_id) {
                            $data['group_id'] = $group_id;
                            $data['position'] = $this->Menu->get_last_position($group_id);
                            $item_moved = true;
                        }
                    }
    
                    if ($this->db->update('menu', $data, 'id' . ' = ' . $data['id'])) {
                        if ($item_moved) {
                            //move sub items
                            $ids = $this->Menu->get_descendants($data['id']);
                            if (!empty($ids)) {
                                $sql = sprintf('UPDATE %s SET %s = %s WHERE %s IN (%s)', 'menu', 'group_id', $group_id, 'id', $ids);
                                $update_sub = $this->db->Execute($sql);
                            }
                            $response['status'] = 4;
                        } else {
                            $response['status'] = 1;
                            $d['title'] = $data['title'];
                            $d['url'] = $data['url'];
    //                        $d['klass'] = $data['class']; //klass instead of class because of an error in js
                            $response['menu'] = $d;
                        }
                    } else {
                        $response['status'] = 2;
                        $response['msg'] = 'Edit menu item error.';
                    }
                } else {
                    $response['status'] = 3;
                }
                header('Content-type: application/json');
                echo json_encode($response);
            }
        }
    
        public function delete()
        {
            $id = $this->input->post('id');
            if ($id) {
                Applib::is_demo();
                $this->Menu->get_descendants($id);
                if (!empty($this->ids)) {
                    $ids = implode(', ', $this->ids);
                    $id = "$id, $ids";
                }
    
                $res = $this->Menu->delete_menu($id);
                if ($res) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                }
                header('Content-type: application/json');
                echo json_encode($response);
            }
        }
    
        /**
         * new save position method
         */
        public function save_position()
        {
            $menu = $this->input->post('menu');
            if (!empty($menu)) {
                //adodb_pr($menu);
                $menu = $this->input->post('menu');
                foreach ($menu as $k => $v) {
                    if ($v == 'null') {
                        $menu2[0][] = $k;
                    } else {
                        $menu2[$v][] = $k;
                    }
                }
                $success = 0;
                if (!empty($menu2)) {
                    foreach ($menu2 as $k => $v) {
                        $i = 1;
                        foreach ($v as $v2) {
                            $data['parent_id'] = $k;
                            $data['position'] = $i;
                            if ($this->db->update('menu', $data, 'id' . ' = ' . $v2)) {
                                $success++;
                            }
                            $i++;
                        }
                    }
                }
            }

            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', lang('item_added_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    

        public function old_save_position()
        {
            if (isset($_POST['easymm'])) {
                $easymm = $_POST['easymm'];
                $this->update_position(0, $easymm);
            }
        }
    
        private function update_position($parent, $children)
        {
            $i = 1;
            foreach ($children as $k => $v) {
                $id = (int)$children[$k]['id'];
                $data[MENU_PARENT] = $parent;
                $data[MENU_POSITION] = $i;
                $this->db->update(MENU_TABLE, $data, MENU_ID . ' = ' . $id);
                if (isset($children[$k]['children'][0])) {
                    $this->update_position($id, $children[$k]['children']);
                }
                $i++;
            }
        }
    
        /**
         * Get label for list item in menu Menu
         * this is the content inside each <li>
         *
         * @param array $row
         * @return string
         */

         
        private function get_label($row)
        {
            $label = '<div class="ns-row">' .
                '<div class="ns-title">' . $row->title . '</div>' .
                '<div class="ns-url">' . $row->url . '</div>' .
                '<div class="actions">' .
                '<a href="#" class="edit-menu" title="Edit">' .
                '<span class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></span>' .
                '</a>' .
                '<a href="#" class="delete-menu" title="Delete">' .
                '<span class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>' .
                '</a>' .
                '<a data-rel="tooltip" data-original-title="'.($row->active == 1 ? lang('deactivate') : lang('activate') ).'" class="activate-item btn btn-xs btn-'.($row->active == 0 ? 'default' : 'success' ).'" href="#" data-href="'.base_url().'menus/activate/'.$row->id.'"><i class="fa fa-power-off"></i></a>' .
                '</a>' .
                '<input type="hidden" name="menu_id" value="' . $row->id . '">' .
                '</div>' .
                '</div>';
            return $label;
        }
    
        private function get_labels($row)
        {
            $label = '<div class="ns-row">' .
                '<div class="ns-title">' . $row['title'] . '</div>' .
                '<div class="ns-url">' . $row['url'] . '</div>' .
                '<div class="actions">' .
                '<a href="#" class="edit-menu" title="Edit">' .
                '<span class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></span>' .
                '</a>' .
                '<a href="#" class="delete-menu" title="Delete">' .
                '<span class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>' .
                '</a>' .
                '<a data-rel="tooltip" data-original-title="'.($row['active'] == 1 ? lang('deactivate') : lang('activate') ).'" class="activate-item btn btn-xs btn-'.($row['active'] == 0 ? 'default' : 'success' ).'" href="#" data-href="'.base_url().'menus/activate/'.$row['active'].'"><i class="fa fa-power-off"></i></a>' .
                '<input type="hidden" name="menu_id" value="' . $row['id'] . '">' .
                '</div>' .
                '</div>';
            return $label;
        }



        public function menus_block($group_id)
        {
            $object = new stdClass();
            $object->id = $group_id;
            $main_menu = []; 
            $this->db->select('*');
            $this->db->from('menu');
            $this->db->where('group_id', $group_id);
            $this->db->order_by('position', 'ASC');
            $this->db->where('active', 1);
            $menu = $this->db->get()->result();
        
            for ($i = 0; $i <= count($menu) - 1; $i++) {
                if ($menu[$i]->parent_id == 0) {
                    $main_menu[] = $menu[$i];
                };
            };
        
            for ($x = 0; $x < count($main_menu, true); $x++) {
                $parent_menu = [];
                for ($q = 0; $q < count($menu, true); $q++) {
                    if ($menu[$q]->parent_id == $main_menu[$x]->id) {
                        $parent_menu[] = $menu[$q];
                    };
                };
                $main_menu[$x]->parent_menu = $parent_menu;
            };
         
            for ($i = 0; $i < count($main_menu, true); $i++) {
                for ($x = 0; $x < count($main_menu[$i]->parent_menu, true); $x++) {
                    for ($e = 0; $e < count($menu, true); $e++) {
                        if ($main_menu[$i]->parent_menu[$x]->id == $menu[$e]->parent_id) {
                            $parent_submenu[] = $menu[$e];
                            $uniqueArray = array_unique($parent_submenu, SORT_REGULAR);
                            $main_menu[$i]->parent_menu[$x]->parent_submenu = $uniqueArray;
                        };
                    };
                };
            }
        
            $object->main_menu = $main_menu;

            $data['menu'] = $object;
            $this->load->view(config_item('active_theme').'/views/blocks/menu_block', $data);
        }
    
      
    }
    