<?php
	//Integrity: 2c9406e264b469d23b2a8b6d272338849abc1038

	class QArray implements ArrayAccess,Countable,Iterator
	{
		protected $_array;

		public function __construct($value=array())
		{
			$this->clear();
			if($value instanceof QArray) $this->_array=$value->toArray();
			if(is_array($value)) $this->_array=$value;
			else $this->_array[]=$value;
		}

		public function clear()
		{
			$this->_array=array();
		}

		public function fromArray($array) //FIXME: changer par copy()?
		{
			$this->clear();
			if(is_array($array)) $this->_array=$array;
		}

		public function toArray()
		{
			return $this->_array;
		}

		public function toJSON()
		{
			return json_encode($this->_array);
		}

		public function fromJSON($serialized)
		{
			$this->_array=json_decode($serialized,true);
			if(!is_array($this->_array))
			{
				$this->_array=array();
				return false;
			}
			return true;
		}

		public function get($path,$default='')
		{
			$array=$this->_find($path,$key);
			if(is_null($array) || !array_key_exists($key,$array)) $array=$default; else $array=$array[$key];
			if(is_array($array)) $array=new QArray($array);
			return $array;
		}

		///////////////////////////
		// Interface ArrayAccess //
		///////////////////////////
		public function offsetExists($path)
		{
			$array=$this->_find($path,$key);
			if(!is_null($array) && array_key_exists($key,$array)) return true;
			return false;
		}

		public function offsetGet($path)
		{
			$default='';

			//Cherche les syntaxes "cl�:valeur" ou "cl�,expression"
			if(preg_match('#^(.*?)([:,])(.*)$#muis',$path,$m))
			{
				$path=$m[1];
				$default=$m[3];
				if($m[2]==',') $default=eval("return ($default);");
			}

			return $this->get($path,$default);
		}

		public function offsetSet($path,$value)
		{
			if($value instanceof QArray) $value=$value->toArray();
			if(is_null($path)) $this->_array[]=$value;
			else $this->_find($path,$key,'create',$value);
		}

		public function offsetUnset($path)
		{
			$this->_find($path,$key,'unset');
		}

		/////////////////////////
		// Interface Countable //
		/////////////////////////
		public function count()
		{
			return count($this->_array);
		}

		////////////////////////
		// Interface Iterator //
		////////////////////////
		public function current()
		{
			return $this->get(key($this->_array));
		}

		public function key()
		{
			return key($this->_array);
		}

		public function next()
		{
			next($this->_array);
		}

		public function rewind()
		{
			reset($this->_array);
		}

		public function valid()
		{
			return !is_null(key($this->_array))?true:false;
		}

		//M�thode interne
		protected function _find($path,&$key,$mode='normal',$value=null)
		{
			$array=&$this->_array;
			$key=$path;

			//On scanne le path...
			while(!is_null($array) && !empty($path))
			{
				//Extraction de la cl� en cours
				$key=$path;
				if(($pos=strpos($path,'/'))!==false) $key=substr($path,0,$pos);
				$pos=false;

				//Analyse les syntaxes 'name[xxx]' ou 'name'
				if(preg_match('#^(.*?)\[(.*?)\]#',$key,$m))
				{
					$key=$m[1];
					if(empty($key))
					{
						//Cas o� on a [] ou [xxx] sans pr�fix
						$key=$m[2];
						if(is_numeric($key)) $key=(int)$key;
						$pos=strlen($m[0]);
					}
				}

				//On pr�pare le path suivant
				if($pos===false) $pos=strlen($key);
				if(substr($path,$pos,1)=='/') $pos++;
				$path=substr($path,$pos);

				if($mode=='create')
				{
					//Cas o� on a un []. Dans ce cas, on g�n�re une nouvelle entr�e dans le tableau
					if(is_string($key) && $key=='') {$array[]=array(); end($array); $key=key($array);}
					//Cas o� la cl� courante n'est pas un array(), alors qu'il y a d'autres items sur le chemin.
					//Dans ce cas, on �crase l'entr�e par un nouveau tableau.
					elseif(!empty($path) && !is_array($array[$key])) $array[$key]=array();
					//Cas o� la cl� n'existe pas. On g�n�re une nouvelle entr�e dans le tableau.
					elseif(!array_key_exists($key,$array)) $array[$key]=array();
				}

				if(!empty($path))
				{
					//On continue le parcours s'il reste du path � parcourir.
					if(array_key_exists($key,$array)) $array=&$array[$key]; else {unset($array); $array=null;}
				}
			}

			//NOTE: Si c'est demand�, on fait faire le set() ou le unset() ici car php ne prend pas
			//la r�f�rence du tableau en dehors de cette fonction, m�me si on sp�cifie la
			//m�thode _find() avec un retour par r�f�rence.
			if(is_array($array))
			{
				if($mode=='create') $array[$key]=$value;
				elseif($mode=='unset') unset($array[$key]);
			}

			return $array;
		}
	}
?>