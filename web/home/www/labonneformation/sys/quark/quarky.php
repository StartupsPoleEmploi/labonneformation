<?php
	//Integrity: 0f0929662731982c67964752923bac55513a2f29
	class Quarky
	{
		private $tpl;

		public function __construct($tpl,$vars)
		{
			$this->tpl=preg_replace_callback('/({{.*?}}|{%.*?%}|{#.*?#})/si',function($m) use (&$vars)
				{
					return $this->analyze(substr($m[1],0,2),trim(substr($m[1],2,-2)),$vars);
				},$tpl);
		}

		public function display()
		{
			return $this->tpl;
		}

		private function analyze($prefix,$exp,&$vars)
		{
			switch($prefix)
			{
				case '{{':
					return $vars[$exp];
				case '{%':
					ob_start();
					eval($exp);
					return ob_get_clean();
				case '{#':
					return '';
			}
		}
	}
?>