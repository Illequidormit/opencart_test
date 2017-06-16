<?php
class ControllerScategoriesScategories extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}


  //   $this->load->model('catalog/category');
	 //
  //   $data['categories'] = array();
	 //
  //  $categories = $this->model_catalog_category->getCategories(0);
  //  foreach ($categories as $category) {
	 //
  //    $data['categories'][] = array(
  //         'category_id' => $category['category_id'],
  //         'name'        => $category['name']
	 //
  //       );
  //  }

	$this->load->model('catalog/category');

	$this->load->model('catalog/product');

	$data['categories'] = array();

	$categories = $this->model_catalog_category->getCategories(0);

	foreach ($categories as $category) {
		if ($category['top']) {
			// Level 2
			$children_data = array();

			$children = $this->model_catalog_category->getCategories($category['category_id']);

			foreach ($children as $child) {
				$filter_data = array(
					'filter_category_id'  => $child['category_id'],
					'filter_sub_category' => true
				);

				$children_data[] = array(
					'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
					'isCheked' => $child['isCheked']
				);
			}

			// Level 1
			$data['categories'][] = array(
				'name'     => $category['name'],
				'children' => $children_data,
				'column'   => $category['column'] ? $category['column'] : 1,
				'href'     => $this->url->link('product/category', 'path=' . $category['category_id']),
				'isCheked' => $category['isCheked']
			);
		}
	}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/scategories/scategories.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/scategories/scategories.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/scategories/scategories.tpl', $data));
		}
	}
}
