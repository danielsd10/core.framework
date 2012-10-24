<?php
class Paging {
	/**
	 * p�gina actual
	 * @var int
	 */
	public $page;
	
	/**
	 * items por p�gina
	 * @var int
	 */
	public $items_page;
	
	/**
	 * total de items encontrados
	 * @var int
	 */
	public $total_items;
	
	/**
	 * total de p�ginas estimadas
	 * @var int
	 */
	public $total_pages;
	
	/**
	 * total de items de la p�gina actual
	 * @var int
	 */
	public $total_page_items;
}
?>