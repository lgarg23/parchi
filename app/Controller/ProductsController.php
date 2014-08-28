<?php
class ProductsController extends AppController {

	var $name = 'Products';
	var $uses = array('Product', 'Party', 'Retail');
	public $components = array('Paginator');
	//var $scaffold;
	
	var $data = array();
	
	public function index(){
		$this->set('products', $this->paginate());		
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
	
	public function add_quantity($name = ''){
		if($this->request->is('post') || $this->request->is('put')){
			$data = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "))));
			$net_quant = $data['Product']['net_quantity'] + $this->request->data['Product']['add_quantity'];
			$this->request->data['Product']['net_quantity'] = $net_quant;
			if($this->Product->save($this->request->data)){
				$this->Session->setFlash('Product Added successfully.');
			}else{
				$this->Session->setFlash('An error occured. Please try again.');
			}
			$this->redirect(array('action' => 'index'));
		}	
		$this->set('name', $name);	
		$this->request->data = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "))));
	}
	
	public function print_p(){
		if($this->request->is('post')){
			if(!empty($this->request->data)){
				$party = $this->Party->find('first', array('conditions' => array('name' => trim($this->request->data['Parchi']['party_name'], " "))));
						
				if(empty($party)){
					$partyToSave = array();
					$partyToSave['Party']['name'] = $this->request->data['Parchi']['party_name'];
					$this->Party->save($partyToSave); 
				}
				
				$productToSave = array();
				$productToSaveExists = array();
				$k = -1;
				$ks = -1;
				foreach($this->request->data['Retail'] as $key => $val){
					if($val['product_id'] == ''){
						unset($this->request->data['Retail'][$key]);
					}else{
						$product = $this->Product->find('first', array('conditions' => array('name' => trim($val['product_id'], " "))));
						if(empty($product)){
							$k++;
							$productToSave[$k]['Product']['name'] = $val['product_id'];
							$productToSave[$k]['Product']['price'] = $val['unit_price'];
							$productToSave[$k]['Product']['unit'] = $val['unit'];
						}else{
							if($product['Product']['net_quantity'] != 0){
								$ks++;
								$productToSaveExists[$ks] = $product;
								$productToSaveExists[$ks]['Product']['net_quantity'] = $product['Product']['net_quantity'] - $val['quantity'];	
							}
						}			
					}
				}
				
				$this->Product->saveAll($productToSaveExists);
				$this->Product->saveAll($productToSave);
				
				if($this->Retail->saveAll($this->request->data['Retail'])){
					
					$this->set('data', $this->request->data);
					$products = $this->Product->find('list', array('fields' => array('id', 'name')));
					$products_price = $this->Product->find('list', array('fields' => array('id', 'price')));
					$products_unit = $this->Product->find('list', array('fields' => array('id', 'unit')));
					$this->set(compact('products', 'parties', 'products_price', 'products_unit'));
					$this->layout = false;
					$this->render('print_parchi');
				}else{
					$this->Session->setFlash('An error occured. Please try again.');
				}
			}
		}
		
		$retail = $this->Retail->find('first', array('order' => array('order' => 'DESC'), 'recursive' => -1));
		$index = 1;
		if(!empty($retail)){
			$index = $retail['Retail']['order'] + 1;  
		}
		$this->set('order', $index);
		
		$products = $this->Product->find('list', array('fields' => array('id', 'name')));
		$products_price = $this->Product->find('list', array('fields' => array('name', 'price')));
		$products_unit = $this->Product->find('list', array('fields' => array('name', 'unit')));
		$parties = $this->Party->find('list', array('fields' => array('id', 'name')));
		$this->set(compact('products', 'parties', 'products_unit', 'products_price'));
	}
	
	public function print_parchi(){
		$this->set('data', $this->data);
		$products = $this->Product->find('list', array('fields' => array('id', 'name')));
		$products_price = $this->Product->find('list', array('fields' => array('id', 'price')));
		$parties = $this->Party->find('list', array('fields' => array('id', 'name')));
		$this->set(compact('products', 'parties', 'products_price'));
	}
	
	public function inventory(){
		$params = $this->request->params['named'];
		if(isset($params['date']) && !empty($params['date'])){
			$date = $params['date'];
			$this->request->data = $this->Retail->find('all', array('conditions' => array(
												'Retail.date' => $params['date']),
												'group' => array('Retail.product_id'),
												'fields' => array('SUM(quantity) as total_quantity', 'Retail.product_id', 'Retail.date')
											));
		}else{
			$date = date('Y-m-d');
			$this->request->data = $this->Retail->find('all', array('conditions' => array(
												'Retail.date' => date('Y-m-d')),
												'group' => array('Retail.product_id'),
												'fields' => array('SUM(quantity) as total_quantity', 'Retail.product_id', 'Retail.date')
											));
		}
		$this->set('date', $date);
	}

}
?>