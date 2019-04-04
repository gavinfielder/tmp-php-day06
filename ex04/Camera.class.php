<?php

class Camera
{
	private $_origin;
	private $_orientation;
	private $_width;
	private $_height;
	private $_ratio;
	private $_fov;
	private $_near;
	private $_far;
	private $_tT;
	private $_tR;
	private $_vmx;
	private $_projection;

	public static $verbose = false;

	public static function doc() { return file_get_contents('Camera.doc.txt'); }

	public function getOrigin() { return $_origin; }
	public function getOrientation() { return $_orientation; }
	public function getWidth() { return $_width; }
	public function getHeight() { return $_height; }
	public function getRatio() { return $_ratio; }
	public function getFov() { return $_fov; }
	public function getNear() { return $_near; }
	public function getFar() { return $_far; }

	public function __construct($arr)
	{
		$this->_origin = $arr['origin'];
		$this->_orientation = $arr['orientation'];
		$this->_width = $arr['width'];
		$this->_height = $arr['height'];
		$this->_ratio = $arr['ratio'];
		$this->_fov = $arr['fov'];
		$this->_near = $arr['near'];
		$this->_far = $arr['far'];
		$this->_tT = new Matrix(array('preset' => Matrix::TRANSLATION,
			'vtx' => $this->_origin->opposite()));
		$this->_tR = $this->orientation->transpose();
		$this->_vmx = $this->_tR->mult($this->_tT);
		$this->_projection = new Matrix(array('preset' => Matrix::PROJECTION,
			'fov' => $this->_fov, 'ratio' => $this->_ratio,
			'near' => $this->_near, 'far' => $this->_far));
	}

	public function ndc_to_raster(Vertex $ndc)
	{
		$rst = new Vertex(array('x' => 0, 'y' => 0, 'z' => 0));
		$x = &$rst->getRefX();
		$y = &$rst->getRefY();
		




	public function watchVertex(Vertex $worldVertex)
	{

	}


