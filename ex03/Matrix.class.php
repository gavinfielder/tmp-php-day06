<?php

class Matrix
{
	const ZERO = 0;
	const IDENTITY = 1;
	const SCALE = 2;
	const RX = 36;
	const RY = 40;
	const RZ = 48;
	const RANY = 32;
	const TRANSLATION = 64;
	const PROJECTION = 128;

	public static function doc() { return file_get_contents('Matrix.doc.txt'); }

	private $_type;
	private $_m;
	private $_scale;
	private $_angle;
	private $_vtc;
	private $_fov;
	private $_ratio;
	private $_near;
	private $_far;

	public function m($i,$j) { return $this->_m[$i][$j]; }
	public function get_type() { return $this->_type; }
	public function getScale() { return $this->_scale; }
	public function getAngle() { return $this->_angle; }
	public function getVtc() { return $this->_vtc; }
	public function getFov() { return $this->_fov; }
	public function getRatio() { return $this->_ratio; }
	public function getFar() { return $this->_far; }
	public function getNear() { return $this->far; }

	static $verbose = false;

	public function __construct($arr)
	{
		for ($i = 0; $i < 4; $i++)
		{
			for ($j = 0; $j < 4; $j++)
				$this->_m[$i][$j] = 0;
		}
		$this->_type = (array_key_exists('preset', $arr) ? $arr['preset'] : 0);
		$this->_scale = 0;
		$this->_angle = 0;
		$this->_vtc = null;
		$this->_fov = 0;
		$this->_ratio = 0;
		$this->_near = 0;
		$this->_far = 0;
		if ($this->_type == self::SCALE)
			$this->initScale($arr['scale']);
		elseif ($this->_type == self::RX)
			$this->initRx($arr['angle']);
		elseif ($this->_type == self::RY)
			$this->initRy($arr['angle']);
		elseif ($this->_type == self::RZ)
			$this->initRz($arr['angle']);
		elseif ($this->_type == self::TRANSLATION)
			$this->initTranslation($arr['vtc']);
		elseif ($this->_type == self::PROJECTION)
			$this->initProjection($arr['fov'], $arr['ratio'],
				$arr['near'], $arr['far']);
		elseif ($this->_type == self::ZERO)
			; //do nothing
		else
			$this->initIdentity();
		if (self::$verbose)
		{
			if ($this->_type == self::IDENTITY)
				echo "Matrix " . $this->typeStr() . " instance constructed\n";
			else
				echo "Matrix " . $this->typeStr() . " preset instance constructed\n";
		}
	}

	function __destruct()
	{
		if (self::$verbose) echo "Matrix instance destructed\n";
	}

	private function initIdentity()
	{
		for ($i = 0; $i < 4; $i++)
			$this->_m[$i][$i] = 1;
	}
	
	private function initScale($scale)
	{
		$this->_scale = $scale;
		for ($i = 0; $i < 3; $i++)
			$this->_m[$i][$i] = $scale;
		$this->_m[3][3] = 1;
	}

	private function initRx($angle)
	{
		$this->_angle = $angle;
		//$angle = deg2rad($angle);
		$this->_m[1][1] = cos($angle);
		$this->_m[2][1] = sin($angle);
		$this->_m[1][2] = -sin($angle);
		$this->_m[2][2] = cos($angle);
		$this->_m[0][0] = 1;
		$this->_m[3][3] = 1;
	}

	private function initRy($angle)
	{
		$this->_angle = $angle;
		//$angle = deg2rad($angle);
		$this->_m[0][0] = cos($angle);
		$this->_m[2][0] = -sin($angle);
		$this->_m[0][2] = sin($angle);
		$this->_m[2][2] = cos($angle);
		$this->_m[1][1] = 1;
		$this->_m[3][3] = 1;
	}

	private function initRz($angle)
	{
		$this->_angle = $angle;
		//$angle = deg2rad($angle);
		$this->_m[0][0] = cos($angle);
		$this->_m[1][0] = sin($angle);
		$this->_m[0][1] = -sin($angle);
		$this->_m[1][1] = cos($angle);
		$this->_m[2][2] = 1;
		$this->_m[3][3] = 1;
	}

	private function initTranslation($vtc)
	{
		$this->_vtc = $vtc;
		$this->initIdentity();
		$this->_m[0][3] = $vtc->getX();
		$this->_m[1][3] = $vtc->getY();
		$this->_m[2][3] = $vtc->getZ();
	}

	private function initProjection($v, $r, $n, $f)
	{
		$this->_fov = $v;
		$this->_ratio = $r;
		$this->_near = $n;
		$this->_far = $f;
		$this->_m[1][1] = 1 / tan(0.5 * deg2rad($v));
		$this->_m[0][0] = $this->_m[1][1] / $r;
		$this->_m[2][2] = -1 * (-$n - $f) / ($n - $f);
		$this->_m[3][2] = -1;
		$this->_m[2][3] = (2 * $n * $f) / ($n - $f);
	}

	private function vtxGetI(Vertex $v, $i)
	{
		switch ($i)
		{
		case 0:
			return $v->getX();
		case 1:
			return $v->getY();
		case 2:
			return $v->getZ();
		case 3:
			return $v->getW();
		}
	}

	public function transformVertex(Vertex $v)
	{
		$x = 0;
		$y = 0;
		$z = 0;
		$w = 0;
		for ($i = 0; $i < 4; $i++)
		{
			$x += ($this->m(0,$i) * $this->vtxGetI($v, $i));
			$y += ($this->m(1,$i) * $this->vtxGetI($v, $i));
			$z += ($this->m(2,$i) * $this->vtxGetI($v, $i));
			$w += ($this->m(3,$i) * $this->vtxGetI($v, $i));
		}
		$r = new Vertex(array('x' => $x, 'y' => $y, 'z' => $z, 'w' => $w));
		return $r;
	}

	public function mult(Matrix $n)
	{
		$r = new Matrix(array('preset' => self::ZERO));
		for ($k = 0; $k < 4; $k++)
		{
			for ($i = 0; $i < 4; $i++)
			{
				for ($j = 0; $j < 4; $j++)
				{
					$r->_m[$i][$j] += ($this->m($i,$k) * $n->m($k,$j));
				}
			}
		}
		return $r;
	}
	
	function __toString()
	{
		$tmp = "M | vtcX | vtcY | vtcZ | vtxO\n";
		$tmp .= "-----------------------------\n";
		$tmp .= "x | %0.2f | %0.2f | %0.2f | %0.2f\n";
		$tmp .= "y | %0.2f | %0.2f | %0.2f | %0.2f\n";
		$tmp .= "z | %0.2f | %0.2f | %0.2f | %0.2f\n";
		$tmp .= "w | %0.2f | %0.2f | %0.2f | %0.2f";
		return (sprintf($tmp,
			$this->m(0,0),
			$this->m(0,1),
			$this->m(0,2),
			$this->m(0,3),
			$this->m(1,0),
			$this->m(1,1),
			$this->m(1,2),
			$this->m(1,3),
			$this->m(2,0),
			$this->m(2,1),
			$this->m(2,2),
			$this->m(2,3),
			$this->m(3,0),
			$this->m(3,1),
			$this->m(3,2),
			$this->m(3,3)));
	}

	public function typeStr()
	{
		switch ($this->_type)
		{
		case self::IDENTITY:
			return "IDENTITY";
		case self::SCALE:
			return "SCALE";
		case self::RX:
			return "0x ROTATION";
		case self::RY:
			return "0y ROTATION";
		case self::RZ:
			return "0z ROTATION";
		case self::TRANSLATION:
			return "TRANSLATION";
		case self::PROJECTION:
			return "PROJECTION";
		case self::ZERO:
			return "ZERO";
		default:
			return "";
		}
	}
}

?>
