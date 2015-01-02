<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller {

	public function index()
	{
 		 if($this->cart->total_items() == 0){
			redirect('home');
		 }
		
  		$this->lang->load('home', $this->session->userdata('lang_file'));
 		$this->lang->load('cart', $this->session->userdata('lang_file'));

		
		
		$currency_info = $this->currency_library->currency('currency');

		foreach($this->cart->contents() AS $carts){
				$cart[] = array(
                   'rowid'  		=> $carts['rowid'],
				   'name'			=> $carts['name'],
				   'qty'			=> $carts['qty'],
				   'subtotal'		=> ''.$this->cart->format_number($carts['subtotal']).' '.$currency_info[0]->symbol.'',
				   'price'			=> ''.$this->cart->format_number($carts['price']).' '.$currency_info[0]->symbol.'',
               );  
			}
		$data['cart'] = $cart;
		$data['cart_total'] = ''.$this->cart->format_number($this->cart->total()).' '.$currency_info[0]->symbol.'';
		//Products...
 		$data['slider_products'] = $this->products_model->slider_products();
 
		//Menu...
		 $data['categories'] = $this->categories_model->get_cats();
 
		$this->load->view('cart', $data);
	}
	public function update(){
	
			$qtys = $this->input->post("qty");
			$i = 0;
			$total = count($this->input->post('rowid'));
			
            foreach ($this->input->post('rowid') as $index => $id){
 
			$data = array(
               array(
                       'rowid'   => $id,
                       'qty'     => $qtys[$index],	
                    )
            );
			
			$this->cart->update($data);
			$i++;
 
 			if($total == $i){
				redirect($_SERVER['HTTP_REFERER']);
			}
			
 		 }
 
	}
		function remove() {

			$data = array(
               array(
                       'rowid'   => $this->uri->segment(3),
                       'qty'     => 0,	
                    )
            );
			$this->cart->update($data);
			redirect($_SERVER['HTTP_REFERER']);
	}	
}