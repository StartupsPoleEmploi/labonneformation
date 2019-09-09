<?php
	//Integrity: 5f41189fd128cb1596e01b47e0f9c22bf03ac436
	class CacheFile
	{
		private $path=CACHE_PATH;
		private $group='';
		private $prefix='cache';
		private $timeLimit;

		public function __construct($time=120,$options=array())
		{
			if(isset($options['path'])) $this->path=$options['path'];
			if(isset($options['group'])) $this->group=$options['group'];
			if(isset($options['prefix'])) $this->prefix=$options['prefix'];
			$this->timeLimit=$time;
			if(!@is_dir($this->getPath())) @mkdir($this->getPath(),0777,true);
		}
		public function getInfos($key)
		{
			return $this->cacheGetInfos($key);
		}
		public function getFile($key)
		{
			return $this->getFileName($key);
		}
		public function get($key,$default=false)
		{
			$value=$this->cacheGetContents($key,$this->timeLimit);
			if($value===false) return $default;
			return unserialize($value);
		}
		public function set($key,$value)
		{
			return $this->cachePutContents($key,serialize($value),$this->timeLimit);
		}
		public function del($key)
		{
			return @unlink($this->getFileName($key));
		}
		public function exists($key)
		{
			$fileName=$this->getFileName($key);
			//return file_exists($fileName);
			if($h=@fopen($fileName,'rb'))
			{
				$timeLimit=$this->timeLimit;
				$fstats=fstat($h);
				if(!$timeLimit || (time()-$fstats['mtime'])<=$timeLimit) return true;
				fclose($h);
			}
			return false;
		}

		/* Fonctions privées */
		private function getFileName($key)
		{
			$md5=md5($key);
			$sub='';
			if($this->group) $sub=substr($md5,0,2).'/';
			return $this->getPath().$sub.$this->prefix.'_'.$md5;
		}
		private function getPath()
		{
			$group='';
			if($this->group) $group=$this->group.'/';
			return $this->path.'/'.$group;
		}
		private function cacheGetInfos($key)
		{
			if($fileName=$this->getFileName($key))
				if(file_exists($fileName))
					return filemtime($fileName);
			return false;
		}
		private function cacheGetContents($key,$timeLimit=0)
		{
			$ret=false;
			$fileName=$this->getFileName($key);
			if($h=@fopen($fileName,'rb'))
			{
				if(@flock($h,LOCK_EX))
				{
					$fstats=fstat($h);
					if(!$timeLimit || (time()-$fstats['mtime'])<=$timeLimit) $ret=stream_get_contents($h);
					flock($h,LOCK_UN);
				}
				fclose($h);
			}
			return $ret;
		}
		private function cachePutContents($key,$value,$timeLimit=0)
		{
			$ret=false;
			$fileName=$this->getFileName($key);
			if(!($h=@fopen($fileName,'wb')))
			{
				$dir=pathinfo($fileName);
				if(mkdir($dir['dirname'],0777,true))
					$h=@fopen($fileName,'wb');
			}
			if($h)
			{
				if(@flock($h,LOCK_EX))
				{
					if(fwrite($h,$value)==strlen($value)) $ret=true;
					flock($h,LOCK_UN);
				}
				fclose($h);
				//touch($fileName,$ret?time()+3600:time()-1);
			}
			return $ret;
		}
	}
	class CacheMem
	{
		private $group='';
		private $timeLimit;

		function __construct($time=120,$options=array())
		{
			if(isset($options['group'])) $this->group=$options['group'].'_';
			$this->timeLimit=$time;
		}
		function get($key,$default=false)
		{
			$value=apcu_fetch($this->group.$key,$success);
			if($success===false) return $default;
			return $value;
		}
		function set($key,$value)
		{
			return apcu_store($this->group.$key,$value,$this->timeLimit);
		}
		function del($key)
		{
			return apcu_delete($this->group.$key);
		}
		function exists($key)
		{
			return apcu_exists($this->group.$key);
		}
	}
	class QCache
	{
		private $cache;

		function __construct($time=120,$options=array())
		{
			switch(array_key_exists('mode',$options)?$options['mode']:'')
			{
				case 'MEM':
				case 'APC':
					$this->cache=new CacheMem($time,$options);
					break;
				default:
					$this->cache=new CacheFile($time,$options);
					break;
			}
		}
		/*
		function config($name,$engine='FILE',$options=array())
		{
			$time=isset($options['time'])?$options['time']:120;
			switch($engine)
			{
				case 'MEM':
				case 'APC':
					$this->cache[$name]=new CacheMem($time,$options);
					break;
				default:
					$this->cache[$name]=new CacheFile($time,$options);
					break;
			}
			return $this->cache[$name];
		}
		*/
		function getInfos($key)
		{
			return $this->cache->getInfos($key);
		}
		function get($name,$default=false)
		{
			return $this->cache->get($name,$default);
		}
		function getFile($key)
		{
			return $this->cache->getFile($key);
		}
		function set($name,$value)
		{
			return $this->cache->set($name,$value);
		}
		function del($name)
		{
			$this->cache->del($name);
		}
		function exists($name)
		{
			return $this->cache->exists($name);
		}
	}
?>