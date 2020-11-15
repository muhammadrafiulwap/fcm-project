<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use \Firebase\JWT\JWT;

use App\Controllers\Auth;

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class Products extends ResourceController {

	protected $format = 'json';
	protected $modelName = 'App\Models\Product_Model';

	protected $isSuccess = null;
	protected $message = null;
	protected $data = null;
	protected $status = null;
	protected $errors = null;

	public function __construct()
    {
        // inisialisasi class Auth dengan $this->protect
        $this->protect = new Auth();
    }

	public function index() {

		// ambil dari controller auth function public private key
        $secret_key = $this->protect->privateKey();
  
        if ($this->request->getServer('HTTP_AUTHORIZATION') == "") {

        	$authHeader = null;

            $this->isSuccess = false;
			$this->message = "Access denied";
			$this->status = 401;

			return $this->show_response();
     	
        } else {

        	$authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

        }
 
        $arr = explode(" ", $authHeader);
 
        $token = $arr[1];

        if($token){
 
            try {
         
                $decoded = JWT::decode($token, $secret_key, array('HS256'));
         
                // Access is granted. Add code of the operation here 
                if($decoded){
                    // response true

                	$data = $this->model->findAll();

					if ($data) {

						$this->isSuccess = true;
						$this->message = "Data berhasil didapatkan";
						$this->data = $data;
						$this->status = 200;

					} else {

						$this->isSuccess = false;
						$this->message = "Data tidak ditemukan";
						$this->status = 200;

					}

                    // $output = [
                    //     'message' => 'Access granted'
                    // ];
                    // return $this->respond($output, 200);
                }
                 
         
            } catch (Exception $e){
 
                $output = [
                    'message' => 'Access denied',
                    "error" => $e->getMessage()
                ];
         
                return $this->respond($output, 401);
            }

		return $this->show_response();

		}
	}

	public function create(){

		$validation = \Config\Services::validation();

		$product_name = $this->request->getPost('product_name');
		$product_stock = $this->request->getPost('product_stock');
		$product_image = $this->request->getPost('product_image');

		$data = array(
			'product_name' => $product_name,
			'product_stock' => $product_stock,
			'product_image' => $product_image
		);

		// ambil dari controller auth function public private key
        $secret_key = $this->protect->privateKey();
  
        if ($this->request->getServer('HTTP_AUTHORIZATION') == "") {

        	$authHeader = null;

            $this->isSuccess = false;
			$this->message = "Access denied";
			$this->status = 401;

			return $this->show_response();
     	
        } else {

        	$authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

        }
 
        $arr = explode(" ", $authHeader);
 
        $token = $arr[1];

        if($token){
 
            try {
         
                $decoded = JWT::decode($token, $secret_key, array('HS256'));
         
                // Access is granted. Add code of the operation here 
                if($decoded){
                    // response true

                	if ($validation->run($data, 'product') == FALSE) {
			
						$this->isSuccess = false;
						$this->errors = $validation->getErrors();
						$this->message = "Ada kesalahan";
						$this->status = 200;

					} else {

						$simpan = $this->model->insertProduct($data);

						if ($simpan) {
							
							$this->isSuccess = true;
							$this->message = "Success post data product";
							$this->data = $data;
							$this->status = 200;

						} else {

							$this->isSuccess = false;
							$this->message = "Failed post data product";
							$this->status = 200;

						}

					}
				
                }
         
            } catch (Exception $e){
 
                $output = [
                    'message' => 'Access denied',
                    "error" => $e->getMessage()
                ];
         
                return $this->respond($output, 401);
            }

		return $this->show_response();

		}
	}

	public function update($id = NULL){

		$validation = \Config\Services::validation();

		$product_name = isset($this->request->getRawInput()['product_name']) ? $this->request->getRawInput()['product_name'] : null;

		$product_stock = isset($this->request->getRawInput()['product_stock']) ? $this->request->getRawInput()['product_stock'] : null;


		$data = array(
			'product_name' => $product_name,
			'product_stock' => $product_stock 
		);

		if ($validation->run($data, 'product') == FALSE) {
			
			$this->isSuccess = false;
			$this->errors = $validation->getErrors();
			$this->message = "Ada kesalahan";
			$this->status = 200;

		} else {

			$update = $this->model->updateProduct($data, $id);

			if ($update) {
				
				$this->isSuccess = true;
				$this->message = "Success update data product";
				$this->data = $data;
				$this->status = 200;

			} else {

				$this->isSuccess = false;
				$this->message = "Failed update data product";
				$this->status = 200;

			}

		}

		return $this->show_response();
	}

	public function delete($id = NULL) {

		$hapus = $this->model->deleteProduct($id);

		if ($hapus) {
			
			$this->isSuccess = true;
			$this->message = "Success delete data product";
			$this->status = 200;

		} else {

			$this->isSuccess = false;
			$this->message = "Failed delete data product";
			$this->status = 200;

		}

		return $this->show_response();

	}

	public function show_response(){

		$response = array(
			'isSuccess' => $this->isSuccess, 
			'message' => $this->message,
			'data' => $this->data,
			'errors' => $this->errors
		);

		return $this->respond($response, $this->status);

	}
}