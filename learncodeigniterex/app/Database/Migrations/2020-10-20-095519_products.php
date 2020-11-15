<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Products extends Migration
{
	public function up()
	{
		
		$this->forge->addField([
			'product_id'			=> [
				'type'				=> 'BIGINT',
				'constraint'		=> 20,
				'unsigned'			=> TRUE,
				'auto_increment'	=> TRUE
			],
			'product_name'			=> [
				'type'				=> 'VARCHAR',
				'constraint'		=> 100
			],
			'product_stock'			=> [
				'type'				=> 'BIGINT',
				'constraint'		=> 20,
				'default'			=> 0
			],

		]);

		$this->forge->addKey('product_id', TRUE);
		$this->forge->createTable('products');

	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
	}
}
