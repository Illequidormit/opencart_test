<?php
  class ControllerModuleScategories extends Controller {
    private $error = array();

     public function index() {
         $this->load->language('module/scategories');

         $this->document->setTitle($this->language->get('heading_title'));

         $this->load->model('extension/module');

         if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
             if (!isset($this->request->get['module_id'])) {
                 $this->model_extension_module->addModule('scategories', $this->request->post);
             } else {
                 $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
             }

             $this->session->data['success'] = $this->language->get('text_success');

             $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
         }

         $data['heading_title'] = $this->language->get('heading_title');

         $data['text_edit'] = $this->language->get('text_edit');
         $data['text_enabled'] = $this->language->get('text_enabled');
         $data['text_disabled'] = $this->language->get('text_disabled');

         $data['entry_name'] = $this->language->get('entry_name');
         $data['entry_limit'] = $this->language->get('entry_limit');
         $data['entry_status'] = $this->language->get('entry_status');

         $data['button_save'] = $this->language->get('button_save');
         $data['button_cancel'] = $this->language->get('button_cancel');

         if (isset($this->error['warning'])) {
             $data['error_warning'] = $this->error['warning'];
         } else {
             $data['error_warning'] = '';
         }

         if (isset($this->error['name'])) {
             $data['error_name'] = $this->error['name'];
         } else {
             $data['error_name'] = '';
         }

         $data['breadcrumbs'] = array();

         $data['breadcrumbs'][] = array(
             'text' => $this->language->get('text_home'),
             'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
         );

         $data['breadcrumbs'][] = array(
             'text' => $this->language->get('text_module'),
             'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
         );

         if (!isset($this->request->get['module_id'])) {
             $data['breadcrumbs'][] = array(
                 'text' => $this->language->get('heading_title'),
                 'href' => $this->url->link('module/scategories', 'token=' . $this->session->data['token'], 'SSL')
             );
         } else {
             $data['breadcrumbs'][] = array(
                 'text' => $this->language->get('heading_title'),
                 'href' => $this->url->link('module/scategories', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
             );
         }

         if (!isset($this->request->get['module_id'])) {
             $data['action'] = $this->url->link('module/scategories', 'token=' . $this->session->data['token'], 'SSL');
         } else {
             $data['action'] = $this->url->link('module/scategories', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
         }

         $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

         if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
             $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
         }


         $this->load->model('catalog/category');
         $data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories(0);
        foreach ($categories as $category) {

          $data['categories'][] = array(
               'category_id' => $category['category_id'],
               'name'        => $category['name']

             );
        }



         $data['header'] = $this->load->controller('common/header');
         $data['column_left'] = $this->load->controller('common/column_left');
         $data['footer'] = $this->load->controller('common/footer');

         $this->response->setOutput($this->load->view('module/scategories.tpl', $data));
     }

     protected function validate() {
         if (!$this->user->hasPermission('modify', 'module/scategories')) {
             $this->error['warning'] = $this->language->get('error_permission');
         }

         if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
             $this->error['name'] = $this->language->get('error_name');
         }

         return !$this->error;
     }
  }
?>