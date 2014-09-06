<?php
class ProductsController extends AppController {

	var $name = 'Products';
	var $uses = array('Product', 'Party', 'Retail', 'ItemGroup', 'User');
	public $components = array('Paginator');
	//var $scaffold;
	
	var $data = array();
	
	public function index(){
		$conditions = array();
		$params = $this->request->params['named'];
		
		if(isset($params['search']) && !empty($params['search'])){
			$text = '%' . $params['search'] . '%';
			$conditions = array('Product.name LIKE ' => $text);	
		}
		
		$conditions[] = array('Product.user_id' => $this->Auth->user('id'));
		
		$this->paginate = array(
			'conditions' => $conditions,
			'order' => array('Product.item_group' => 'ASC') 
		);
		
		$this->set('products', $this->paginate());
		$products_list = $this->Product->find('list', array('conditions' => array('Product.user_id' => $this->Auth->user('id')),
													'fields' => array('id', 'name')));
		
		$this->set('products_list', $products_list);
	}
	
	public function add($name = ''){
		if($this->request->is('post')){
			if($this->Product->save($this->request->data)){
				$itemGroup = $this->ItemGroup->find('first', array('conditions' => array('name' => trim($this->request->data['Product']['item_group'], " "), 
												'ItemGroup.user_id' => $this->Auth->user('id'))));
				if(empty($itemGroup)){
					if($this->request->data['Product']['item_group'] != ''){
						$productToSave['ItemGroup']['name'] = $this->request->data['Product']['item_group'];
						$this->ItemGroup->save($productToSave);
					}
				}
				$this->Session->setFlash('Product Added successfully.');
			}else{
				$this->Session->setFlash('An error occured. Please try again.');
			}
			$this->redirect(array('action' => 'index'));
		}
		
		$product = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "),
						'Product.user_id' => $this->Auth->user('id'))));
		if(!empty($product)){
			$this->Session->setFlash('Item already exists with this name. You can not add.');
			$this->redirect($this->referer());
		}
		$item_groups = $this->ItemGroup->find('list', array('fields' => array('id', 'name'), 
										'conditions' => array('ItemGroup.user_id' => $this->Auth->user('id'))));
		$this->set('item_groups', $item_groups);	
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
		$this->request->data = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "), 
									'Product.user_id' => $this->Auth->user('id'))));
	}
	
	public function delete($id = ''){
		if($this->Product->delete($id)){
			$this->redirect($this->referer());
		}
	}
	
	public function add_quantity($name = ''){
		if($this->request->is('post') || $this->request->is('put')){
			$data = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "), 
												'Product.user_id' => $this->Auth->user('id'))));
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
		$this->request->data = $this->Product->find('first', array('conditions' => array('Product.name' => trim($name, " "),
														'Product.user_id' => $this->Auth->user('id'))));
	}
	
	public function print_p(){
		if($this->request->is('post')){
			if(!empty($this->request->data)){
				$user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'recursive' => -1));
				
				$party = $this->Party->find('first', array('conditions' => array('name' => trim($this->request->data['Parchi']['party_name'], " "),
																'Party.user_id' => $this->Auth->user('id'))));
						
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
						$product = $this->Product->find('first', array('conditions' => array('name' => trim($val['product_id'], " "),
						   												'Product.user_id' => $this->Auth->user('id'))));
						if(empty($product)){
							if($val['product_id'] != ''){
								$k++;
								$productToSave[$k]['Product']['name'] = $val['product_id'];
								$productToSave[$k]['Product']['price'] = $val['unit_price'];
								$productToSave[$k]['Product']['unit'] = $val['unit'];								
							}
						}else{
								$ks++;
								$productToSaveExists[$ks] = $product;
								$productToSaveExists[$ks]['Product']['net_quantity'] = $product['Product']['net_quantity'] - $val['quantity'];	
						}			
					}
				}
				
				$this->Product->saveAll($productToSaveExists);
				$this->Product->saveAll($productToSave);
				
				$interval = $user['User']['last_parchi_date'] - date('Y-m-d');
				$c = $user['User']['last_parchi_number'];
				
				if($interval != 0){
					$parchi_number = 'a'; 
				}else{
					$c++;
					$parchi_number = $c; 
				}
				
				$user['User']['last_parchi_date'] = date('Y-m-d');
				$user['User']['last_parchi_number'] = $parchi_number;
				
				$this->User->saveAll($user, array('validate' => false));
				
				if($this->Retail->saveAll($this->request->data['Retail'])){
					
					$this->set('data', $this->request->data);
					$products = $this->Product->find('list', array('fields' => array('id', 'name'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id'))));
					
					$products_price = $this->Product->find('list', array('fields' => array('id', 'price'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id')) 
													));
					$products_unit = $this->Product->find('list', array('fields' => array('id', 'unit'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id'))));
					$this->set(compact('products', 'parties', 'products_price', 'products_unit'));
					$this->set('voucher_number', $user['User']['last_parchi_number']);
					$this->layout = false;
					$this->render('print_parchi');
				}else{
					$this->Session->setFlash('An error occured. Please try again.');
				}
			}
		}
		
		$retail = $this->Retail->find('first', array('order' => array('order' => 'DESC'), 'recursive' => -1, 
														'conditions' => array('Retail.user_id' => $this->Auth->user('id'))));
		$index = 1;
		if(!empty($retail)){
			$index = $retail['Retail']['order'] + 1;  
		}
		$this->set('order', $index);
		
		$products = $this->Product->find('list', array('fields' => array('id', 'name'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id'))));
		$products_price = $this->Product->find('list', array('fields' => array('name', 'price'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id'))));
		$products_unit = $this->Product->find('list', array('fields' => array('name', 'unit'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id'))));
		$parties = $this->Party->find('list', array('fields' => array('id', 'name'), 
														'conditions' => array('Party.user_id' => $this->Auth->user('id'))));
		$this->set(compact('products', 'parties', 'products_unit', 'products_price'));
	}
	
	public function print_parchi(){
		$this->set('data', $this->data);
		$products = $this->Product->find('list', array('fields' => array('id', 'name'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id'))));
		$products_price = $this->Product->find('list', array('fields' => array('id', 'price'), 
														'conditions' => array('Product.user_id' => $this->Auth->user('id'))));
		$parties = $this->Party->find('list', array('fields' => array('id', 'name'), 
														'conditions' => array('Party.user_id' => $this->Auth->user('id'))));
		$this->set(compact('products', 'parties', 'products_price'));
	}
	
	public function inventory(){
		if($this->request->is('post')){
			$data = $this->request->data['Product'];
		}else{
			$data['start_date'] = date('Y-m-d', strtotime('-2 day'));
			$data['end_date'] = date('Y-m-d');
		}
		
		$this->request->data = $this->Retail->find('all', array('conditions' => array(
									'Retail.date >=' => $data['start_date'], 'Retail.date <=' => $data['end_date'], 
														'Retail.user_id' => $this->Auth->user('id')),
									'group' => array('Retail.product_id'),
									'fields' => array('SUM(quantity) as total_quantity', 'Retail.product_id', 'Retail.date')
								));
		
		$this->request->data['Product'] = $data;						
								
		/*$params = $this->request->params['named'];
		if(isset($params['date']) && !empty($params['date'])){
			$date = $params['date'];
			$date = date('Y-m-d', strtotime($date));
			$this->request->data = $this->Retail->find('all', array('conditions' => array(
												'Retail.date' => $date),
												'group' => array('Retail.product_id'),
												'fields' => array('SUM(quantity) as total_quantity', 'Retail.product_id', 'Retail.date')
											));
		}else{
			$date = date('Y-m-d');
			$this->request->data = $this->Retail->find('all', array('conditions' => array(
												'Retail.date' => $date),
												'group' => array('Retail.product_id'),
												'fields' => array('SUM(quantity) as total_quantity', 'Retail.product_id', 'Retail.date')
											));
		}
		$this->set('date', $date);*/
	}

}
?>