<?php
# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Color.class.php                                    :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: gfielder <marvin@42.fr>                    +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2019/04/02 01:48:55 by gfielder          #+#    #+#              #
#    Updated: 2019/04/02 01:48:55 by gfielder         ###   ########.fr        #
#                                                                              #
# **************************************************************************** #

class Color
{
	public $red;
	public $green;
	public $blue;

	static $verbose = false;

	public function __construct($arr)
	{
		if (array_key_exists('rgb', $arr))
		{
			$rgb = intval($arr['rgb']);
			//build from rgb
			$this->red = ($rgb / (65281)) % 256;
			$this->green = ($rgb / 256) % 256;
			$this->blue = $rgb % 256;
		}
		else
		{
			$this->red = intval($arr['red']);
			$this->green = intval($arr['green']);
			$this->blue = intval($arr['blue']);
		}
		if (self::$verbose)
		{
			printf("Color( red: %3d, green: %3d, blue: %3d ) constructed.\n",
				$this->red, $this->green, $this->blue);
		}
	}

	function __destruct()
	{
		if (self::$verbose)
		{
			printf("Color( red: %3d, green: %3d, blue: %3d ) destructed.\n",
				$this->red, $this->green, $this->blue);
		}
	}

	public function __tostring()
	{
		$str = sprintf("Color( red: %3d, green: %3d, blue: %3d )",
			$this->red, $this->green, $this->blue);
		return $str;
	}

	public static function doc()
	{
		return file_get_contents('Color.doc.txt');
	}

	public function add($b)
	{
		$color = new Color(array(
			'red' => ($b->red + $this->red),
			'green' => ($b->green + $this->green),
			'blue' => ($b->blue + $this->blue)));
		return $color;
	}


	public function sub($b)
	{
		$color = new Color(array(
			'red' => (-($b->red) + $this->red),
			'green' => (-($b->green) + $this->green),
			'blue' => (-($b->blue) + $this->blue)));
		return $color;
	}

	public function mult($mult)
	{
		$color = new Color(array(
			'red' => ($mult * $this->red),
			'green' => ($mult * $this->green),
			'blue' => ($mult * $this->blue)));
		return $color;
	}
}

?>
