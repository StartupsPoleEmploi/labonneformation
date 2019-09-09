<?php
	class SString extends QString
	{
		public function clean($minLen=1)
		{
			parent::clean($minLen);
			$this->replace('#â\?\?#',"'");
			if(1)
			$this->str=trim(preg_replace_callback('#&?(eacute|[aei]grave|[aeuoi]circ);#sui',function($m)
				{
					return Tools::text2Html('&'.$m[1].';');
				},$this->str));
			return $this;
		}
	}
?>
