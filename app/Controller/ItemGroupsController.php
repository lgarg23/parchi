<?php
class ProductsController extends AppController {

	var $uses = array('ItemGroup', 'Product', 'Party', 'Retail');
	public $components = array('Paginator');
	//var $scaffold;
	
	var $data = array();
	
	public function index(){
		$conditions = array();
		$params = $this->request->params['named'];
		
		if(isset($params['search']) && !empty($params['search'])){
			$text = '%' . $params['search'] . '%';
			$conditions = array('ItemGroup.name LIKE ' => $text);	
		}
		
		$this->paginate = array(
			'conditions' => $conditions 
		);
		
		$this->set('itemGroups', $this->paginate());
		$products_list = $this->ItemGroup->find('list', array('fields' => array('id', 'name')));
		$this->set('products_list', $products_list); 		
	}
	
	public function add($name = ''){
		if($this->request->is('post')){
			if($this->Product->save($this->request->data)){
				$this->Session->setFlash('Product Added successfully.');
			}else{
				$this->Session->setFlash('An error occured. Please try again.');
			}
			$this->redirect(array('action' => 'index'));
		}
		
		$product = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "))));
		if(!empty($product)){
			$this->Session->setFlash('Item already exists with this name. You can not add.');
			$this->redirect($this->referer());
		}
			
		$this->set('name', $name);	
	}
	
	public function edit($name = ''){
		if($this->request->is('post') || $this->request->is('put')){
			if($this->Product->save($this->request->data)){
				$this->Session->setFlash('Product Updated successfully.');
			}else{
				$this->Session->setFlash('An error occured. Please try again.');
			}
			$this->redirect(array('action' => 'index'));
		}	
		$this->set('name', $name);	
		$this->request->data = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "))));
	}
	
	public function delete($id = ''){
		if($this->Product->delete($id)){
			$this->redirect($this->referer());
		}
	}
	
}
?>