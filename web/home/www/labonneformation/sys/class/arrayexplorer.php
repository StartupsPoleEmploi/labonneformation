<?php
	class ArrayExplorer extends QArray
	{

	}
	class ArrayExplorer2 implements ArrayAccess,Countable,Iterator
	{
		protected $array;
		protected $position;

		public function __construct($array=array())
		{
			$this->fromArray($array);
		}

		public function clear()
		{
			$this->position=0;
			$this->array=array();
		}

		public function fromArray($array) //FIXME: changer par copy()?
		{
			$this->clear();
			if(is_array($array)) $this->array=$array;
		}

		public function toArray()
		{
			return $this->array;
		}

		public function toJSON()
		{
			return json_encode($this->array);
		}

		public function fromJSON($serialized)
		{
			$this->position=0;
			$this->array=json_decode($serialized,true);
			if(!is_array($this->array))
			{
				$this->array=array();
				return false;
			}
			return true;
		}

		/* Interface ArrayAccess */
		public function offsetExists($offset)
		{
			return true;
		}
		public function offsetGet($offset)
		{
			$default='';
			if(preg_match('#^(.*?)([:,])(.*)$#muis',$offset,$m))
			{
				$offset=$m[1];
				$selector=$m[2];
				$default=$m[3];
				if($selector==',') $default=eval("return ($default);");
			}
			return $this->get($offset,$default);
		}
		public function offsetSet($offset,$value)
		{
			if(is_null($offset))
				$this->array[]=$value;
			else
				$this->array[$offset]=$value;
		}
		public function offsetUnset($field)
		{
			$array=&$this->array;
			foreach(explode('/',$field) as $nodeName)
			{
				$offset=null;
				if(preg_match('#^(.*?)(\[(\d+)\])?$#',$nodeName,$m))
				{
					$nodeName=$m[1];
					if(array_key_exists(3,$m)) $offset=$m[3];
				}
				if(array_key_exists($nodeName,$array))
				{
					if(is_null($offset))
						unset($array[$nodeName]);
					else
						unset($array[$nodeName][$offset]);
					return;
				} else
					$array=&$array[$nodeName];
			}
		}

		/* Interface Countable */
		public function count()
		{
			return count($this->array);
		}

		/* Interface Iterator */
		public function current()
		{
			return $this->offsetGet($this->position);
		}
		public function key()
		{
			return $this->position;
		}
		public function next()
		{
			$this->position++;
		}
		public function rewind()
		{
			$this->position=0;
		}
		public function valid()
		{
			return isset($this->array[$this->position]);
		}

		public function get($field,$default='')
		{
			$res=$this->getPosition($field);
			if($res===false) $res=$default;
			return is_array($res)?new ArrayExplorer($res):$res;
		}

		protected function &getPosition($field)
		{
			$array=&$this->array;
			foreach(explode('/',$field) as $nodeName)
			{
				$offset=null;
				if(preg_match('#^(.*?)(\[(.+?)\])?$#',$nodeName,$m))
				{
					$nodeName=$m[1];
					if(array_key_exists(3,$m)) $offset=$m[3];
				}
				if(array_key_exists($nodeName,$array))
				{
					$array=&$array[$nodeName];
					if(!is_null($offset))
						if(array_key_exists($offset,$array))
							$array=&$array[$offset];
						else
							return false;
				} else
					return false;
			}
			return $array;
		}
	}
?>