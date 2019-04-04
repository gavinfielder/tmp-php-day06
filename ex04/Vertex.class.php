<?php
require_once 'Color.class.php';

class Vertex
{
    private $_x;
    private $_y;
    private $_z;
    private $_w = 1;
	private $_color;

	static $verbose = false;

    public static function doc() { return file_get_contents('Vertex.doc.txt'); }

    public function getX() { return $this->_x; }
    public function getY() { return $this->_y; }
    public function getZ() { return $this->_z; }
	public function getW() { return $this->_w; }
    public function getColor() { return $this->_color; }

	public function &getRefX() { return $this->_x; }
	public function &getRefY() { return $this->_y; }
	public function &getRefZ() { return $this->_z; }
	public function &getRefW() { return $this->_w; }

	public function getI($i)
	{
		switch ($i)
		{
		case 0:
			return $this->_x;
		case 1:
			return $this->_y;
		case 2:
			return $this->_z;
		case 3:
			return $this->_w;
		default:
			return 0;
		}
	}

    public function setX($x) { $this->_x = $x; }
    public function setY($y) { $this->_y = $y; }
    public function setZ($z) { $this->_z = $z; }
	public function setW($w) { $this->_w = $w; }
    public function setColor($color) { $this->_color = $color; }

    public function __construct($v)
    {
        $this->_x = $v['x'];
        $this->_y = $v['y'];
		$this->_z = $v['z'];
        if (array_key_exists('w', $v))
			$this->_w = $v['w'];
		if (array_key_exists('color', $v) && $v['color'] instanceof Color)
			$this->_color = $v['color'];
		else
			$this->_color = new Color(array('red' => 255, 'green' => 255, 'blue' => 255));
        if (self::$verbose) printf("" . $this . " constructed\n");
	}

    function __destruct()
	{
		if (self::$verbose) printf("" . $this . " destructed\n");
	}

    function __toString()
    {
		if (self::$verbose)
		{
			$str = sprintf(
				"Vertex( x: %0.2f, y: %0.2f, z:%0.2f, w:%0.2f, Color( red: %3d, green: %3d, blue: %3d ) )",
				$this->_x, $this->_y, $this->_z, $this->_w,
				$this->_color->red, $this->_color->green, $this->_color->blue);
		}
		else
		{
			$str = sprintf(
				"Vertex( x: %0.2f, y: %0.2f, z:%0.2f, w:%0.2f )",
				$this->_x, $this->_y, $this->_z, $this->_w);
		}
		return $str;
	}

}

?>
