<?php
	//Integrity: 905bc6ed5e6ccda465167c134fdcd18b2f16ac71
	class QString
	{
		protected $str;

		public function __construct($str=null)
		{
			$this->set($str);
			return $this;
		}
		public function __toString()
		{
			return $this->str;
		}
		public function set($str)
		{
			$this->str=(string)$str;
			return $this;
		}
		public function trim()
		{
			$this->str=trim($this->str);
			return $this;
		}
		public function replace($pat,$rep='')
		{
			if($this->str!='')
			{
				if(is_array($pat))
					$this->str=strtr($this->str,$pat);
				else
					$this->str=preg_replace($pat,$rep,$this->str);
			}
			return $this;
		}
		public function cut($len=255)
		{
			$str=mb_substr($this->str,0,$len);
			if(strlen($str)>=$len) $str.='...';
			$this->str=$str;
			return $this;
		}
		public function clean($minLen=1)
		{
			if($text=$this->str)
			{
				if(mb_detect_encoding($text,'UTF-8',true)) $text=iconv("UTF-8","ISO-8859-1//TRANSLIT",$text);
				$text=trim(strip_tags($text));
				if(strlen($text)<=$minLen)
				{
					$this->str=''; /* Cas des "-" que l'on considère comme vide */
					return $this;
				}
				//$text=preg_replace('#\\n#si',"\n",$text);
				$text=strtr($text,array('\n'=>"\n"));
				//if(substr((string)$text,0,12)=='1er semestre') echo $text."\n";
				$text=preg_replace_callback('#^.*$#mi',function($m) {return trim($m[0]);},$text);
				$text=preg_replace("#\n+#smi","\n",$text);
				if(mb_detect_encoding($text,'UTF-8',true)) $text=utf8_decode($text); /* Pour les cas de double encodage */
				$this->str=utf8_encode(trim($text));
			}
			return $this;
		}
	}
?>