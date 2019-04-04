<?php
require_once 'Vertex.class.php';

class Vector
{
	private $_x;
	private $_y;
	private $_z;
	private $_w = 0;

	public static $verbose = false;

	public static function doc() { return file_get_contents("Vector.doc.txt"); }

	public function getX() { return $this->_x; }
	public function getY() { return $this->_y; }
	public function getZ() { return $this->_z; }
	public function getW() { return $this->_w; }

	public function __construct($arr)
	{
		if (isset($arr['orig']))
			$orig = $arr['orig'];
		else
			$orig = new Vertex(array('x' => 0, 'y' => 0, 'z' => 0, 'w' => 1));
		$dest = $arr['dest'];
		$this->_x = $dest->getX() - $orig->getX();
		$this->_y = $dest->getY() - $orig->getY();
		$this->_z = $dest->getZ() - $orig->getZ();
		$this->_w = 0;
		if (self::$verbose) printf("" . $this . " constructed\n");
	}

	function __destruct()
	{
		if (self::$verbose) printf("" . $this . " destructed\n");
	}
	
	public function __toString()
	{
		return (sprintf("Vector( x:%0.2f, y:%0.2f, z:%0.2f, w:%0.2f )",
			$this->_x, $this->_y, $this->_z, $this->_w));
	}

	public function magnitude()
	{
		return sqrt(pow($this->_x, 2) + pow($this->_y, 2) + pow($this->_z, 2));
	}

	public function normalize()
	{
		$mag = $this->magnitude();
		$x = $this->_x / $mag;
		$y = $this->_y / $mag;
		$z = $this->_z / $mag;
		return new Vector(array('dest' => new Vertex(
			array('x' => $x, 'y' => $y, 'z' => $z))));
	}

	public function add(Vector $v)
	{
		$x = $this->_x + $v->getX();
		$y = $this->_y + $v->getY();
		$z = $this->_z + $v->getZ();
		return new Vector(array('dest' => new Vertex(
			array('x' => $x, 'y' => $y, 'z' => $z))));
	}

	public function sub(Vector $v)
	{
		$x = $this->_x - $v->getX();
		$y = $this->_y - $v->getY();
		$z = $this->_z - $v->getZ();
		return new Vector(array('dest' => new Vertex(
			array('x' => $x, 'y' => $y, 'z' => $z))));
	}

	public function opposite()
	{
		$x = -$this->_x;
		$y = -$this->_y;
		$z = -$this->_z;
		return new Vector(array('dest' => new Vertex(
			array('x' => $x, 'y' => $y, 'z' => $z))));
	}

	public function scalarProduct($k)
	{
		$x = $this->_x * $k;
		$y = $this->_y * $k;
		$z = $this->_z * $k;
		return new Vector(array('dest' => new Vertex(
			array('x' => $x, 'y' => $y, 'z' => $z))));
	}

	public function dotProduct(Vector $v)
	{
		return ($this->_x * $v->getX()
			  + $this->_y * $v->getY()
			  + $this->_z * $v->getZ());
	}
	
	public function crossProduct(Vector $v)
	{
		$x = $this->_y * $v->getZ() - $this->_z * $v->getY();
		$y = -($this->_x * $v->getZ() - $this->_z * $v->getX());
		$z = $this->_x * $v->getY() - $this->_y * $v->getX();
		return new Vector(array('dest' => new Vertex(
			array('x' => $x, 'y' => $y, 'z' => $z))));
	}

	public function cos(Vector $v)
	{
		$dot = $this->dotProduct($v);
		$denom = $this->magnitude() * $v->magnitude();
		return ($dot / $denom);
	}
}

?>
