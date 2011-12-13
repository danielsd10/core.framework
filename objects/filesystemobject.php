<?php

class FileSystemObject {
	const exec = 0x1;
	const read = 0x2;
	const write = 0x4;
	
	private $_folder;
	private $_file;
	
	private $filename;
	private $name;
	private $ext;
	private $size;
	private $owner;
	private $attributes;

	public function __construct() {
		
	}
	
	public function open(){}
	public function close(){}
	public function read() {}
	public function write() {}
	public function append() {}
	public function copy() {}
	public function move() {
		
	}
	public function delete() {
		
	}
	
	public function owner() {
		
	}
	public function attributes(){
		
	}
}
?>