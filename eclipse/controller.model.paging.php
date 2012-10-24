<?php
class Paging {
	/**
	 * página actual
	 * @var int
	 */
	public $page;
	
	/**
	 * items por página
	 * @var int
	 */
	public $items_page;
	
	/**
	 * total de items encontrados
	 * @var int
	 */
	public $total_items;
	
	/**
	 * total de páginas estimadas
	 * @var int
	 */
	public $total_pages;
	
	/**
	 * total de items de la página actual
	 * @var int
	 */
	public $total_page_items;
}
?>