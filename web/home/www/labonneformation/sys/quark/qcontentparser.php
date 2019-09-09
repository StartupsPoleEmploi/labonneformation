<?php
	//Integrity: 7720411479569a8ac6e40963dbed5f7c8fc3f768
	class LineParser
	{
		protected $attrs,$content,$id;

		public function __construct($id,$attrs=array(),$content=null)
		{
			$this->attrs=$attrs;
			$this->id=$id;
			//if($id) $this->attrs+=array('id'=>$id);
			$this->content=$content;
			return $this;
		}
		public function __toString()
		{
			return (string)$this->inner();
		}
		public function getId()
		{
			return $this->id;
		}
		public function setId($id)
		{
			$this->id=$id;
			return $this;
		}
		public function parse($line)
		{
			$this->attrs=$line['a'];
			$this->content=$line['c'];
			return $this;
		}
		public function serialize()
		{
			return array('a'=>$this->attrs,'c'=>$this->content);
		}
		public function getContent()
		{
			return $this->content;
		}
		public function inner()
		{
			return $this->content;
		}
		function setContent($content)
		{
			$this->content=$content;
			return $this;
		}
		public function getAttr($key,$default=null)
		{
			return array_key_exists($key,$this->attrs)?$this->attrs[$key]:$default;
		}
		public function setAttr($key,$value)
		{
			$this->attrs[$key]=$value;
			return $this;
		}
	}
	class QContentParser
	{
		protected $parse,$shift;

		public function __construct($content=null,$json=true)
		{
			$this->parse=array();
			if(!is_null($content)) $this->parse($content,$json);
			return $this;
		}
		public function __toString()
		{
			return $this->serialize();
		}
		public function __clone()
		{
			$this->parse($this->serialize());
		}
		public function clear()
		{
			$this->parse=array();
			return $this;
		}
		public function parse($content,$json=true)
		{
			$this->parse=array();
			if($json) $content=json_decode($content,true);
			foreach($content as $id=>$v)
			{
				$l=new LineParser($id);
				$this->parse[$id]=$l->parse($v);
			}
			return $this;
		}
		public function serialize()
		{
			$result=array();
			foreach($this->parse as $id=>$v)
				$result[$id]=$v->serialize();
			return json_encode($result);
		}
		public function set($id,$attrs,$content=null)
		{
			if(is_null($content))
			{
				$content=$attrs;
				$attrs=array();
			}
			//$attrs=array_merge(array('id'=>$id),$attrs);
			$this->parse[$id]=new LineParser($id,$attrs,$content);
			return $this->parse[$id];
		}
		public function get($id,$default='')
		{
			if(array_key_exists($id,$this->parse)) return $this->parse[$id];
			if(is_object($default) || is_null($default)) return $default;
			return new LineParser($id,array(),$default);
		}
		public function start($toParse=null)
		{
			$retCode=true;
			if($toParse) $retCode=$this->parse($toParse);
			$this->shift=$this->parse;
			$this->offset=-1;
			return count($this->parse)>0?$retCode:false;
		}
		public function next()
		{
			$this->offset++;
			if(!empty($this->shift))
				return array_shift($this->shift);
			return null;
		}
		public function select()
		{
			$arg=func_get_args();
			if(empty($arg)) return $this->parse;
			$lines=array();
			foreach(func_get_args() as $toFind)
			{
				if($toFind[0]=='[')
				{
					if(preg_match('#\[(.+?)=(.+)\]#',$toFind,$m))
						foreach($this->parse as $k=>$v)
							if($v->getAttr($m[1])==$m[2])
								$lines[$k]=$v;
				} else
				if($line=$this->get($toFind))
					$lines[$toFind]=$line;
			}
			return $lines;
		}
		public function merge($content)
		{
			if($content->start())
				while($line=$content->next())
					$this->parse[$line->getId()]=$line;
			return $this;
		}
	}
?>