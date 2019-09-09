<?php
	//Integrity: 6ccc51e59bffa0e700aee35bbf6ff71eb99e92b8
	class QController
	{
		protected $conditions;

		function __construct($conditions=array())
		{
			$this->condition($conditions);
		}
		public function control($value)
		{
			if(array_key_exists('mandatory',$this->conditions) && $this->conditions['mandatory'] && trim($value)=='') return 'empty';
			if(array_key_exists('match',$this->conditions) && preg_match($this->conditions['match'],$value)==0) return 'dontmatch';
			if(array_key_exists('dontmatch',$this->conditions) && preg_match($this->conditions['dontmatch'],$value)>0) return 'match';
			if(array_key_exists('min',$this->conditions) && $this->isMin($value,$this->conditions['min'])) return 'minoverflow';
			if(array_key_exists('max',$this->conditions) && $this->isMax($value,$this->conditions['max'])) return 'maxoverflow';
			return false;
		}
		public function isMin($val1,$val2)
		{
			return $val1<$val2;
		}
		public function isMax($val1,$val2)
		{
			return $val1>$val2;
		}
		public function condition($conditions)
		{
			$this->conditions=$conditions;
		}
		public function getCondition($name,$default='')
		{
			if(array_key_exists($name,$this->conditions)) return $this->conditions[$name];
			return $default;
		}
	}
	class QControllerText extends QController
	{
	}
	class QControllerTextarea extends QController
	{
	}
	class QControllerSelect extends QController
	{
	}
	class QControllerEmail extends QController
	{
		public function control($value)
		{
			if($error=parent::control($value)) return $error;
			if(!filter_var($value,FILTER_VALIDATE_EMAIL)) return 'badformat';
			return false;
		}
	}
	class QControllerCheckbox extends QController
	{
	}
	class QControllerTel extends QController
	{
		public function control($value)
		{
			if($error=parent::control($value)) return $error;
			$patterns=array('#^0\d(?:\W?\d{2}){4}\s*$#',
			                '#(?:0|32)\d{8}#Si', // Fixe Belgique local et international
			                '#(?:0|32)4[7-9]\d{7}#Si');// Portable Belgique local et international
			$value=preg_replace('#[^0-9]#Si','',$value);
			$isPhoneNumber=false;
			foreach($patterns as $pattern)
				if(preg_match($pattern,$value)==1)
				{
					$isPhoneNumber=true;
					break;
				}
			if(!$isPhoneNumber) return 'badformat';
			return false;
		}
	}
	class QControllerDate extends QController
	{
		public function control($value)
		{
			if($error=parent::control($value)) return $error;
			//if(!empty($value) && !preg_match('#\d{1,4}-\d{1,2}-\d{1,2}#',$value)) return 'badformat';
			return false;
		}
		public function isMin($val1,$val2)
		{
			return $val1<=$val2;
		}
		public function isMax($val1,$val2)
		{
			return $val1>=$val2;
		}
	}
	class QTag
	{
		private $insideTag,$attr,$content,$noEncodeContent;
		private $encoding='utf-8';
		protected $controller,$tag;

		public function __construct($tag='input',$name='',$id=null,$insideTag=false)
		{
			$this->controller(new QController());
			$this->attr=array();
			$this->tag=$tag;
			if(!is_null($id)) $this->id($id);
			if($name) $this->name($name);
			$this->insideTag=$insideTag;
		}
		public function __toString()
		{
			return $this->display();
		}
		public function getTag()
		{
			return $this->tag;
		}
		public function name($name)
		{
			//printf("%s:%s<br>",$name,preg_replace('#^(.+?)\[(.+?)\]$#','$1_$2',$name));
			$this->attr('name',$name);
			if(!$this->getAttr('id',null)) $this->id(preg_replace('#^(.+?)\[(.+?)\]$#','$1_$2',$name));
			return $this;
		}
		public function id($id)
		{
			$this->attr('id',$id);
			return $this;
		}
		public function getId()
		{
			return $this->getAttr('id');
		}
		public function getName()
		{
			return $this->getAttr('name');
		}
		public function attr($attr,$value=null)
		{
			if(func_num_args()>1)
			{
				if(is_string($value) || is_integer($value)) $this->attr[$attr]=$value;
				elseif(is_null($value)) unset($this->attr[$attr]);
				else $this->attr[$attr]=true;
			} else
			{
				if(is_array($attr))
					foreach($attr as $k=>$v)
						$this->attr[$k]=$v;
				else
					unset($this->attr[$attr]);
			}
			return $this;
		}
		public function getAttr($attr=null,$default=null)
		{
			if(is_null($attr)) return $this->attr;
			return array_key_exists($attr,$this->attr)?$this->attr[$attr]:$default;
		}
		public function content($content,$noEncodeContent=false)
		{
			$this->content=$content;
			$this->noEncodeContent=$noEncodeContent;
			return $this;
		}
		public function getContent()
		{
			return $this->content;
		}
		public function display()
		{
			if($this->insideTag)
				return sprintf('<%s %s>%s</%s>',$this->tag,$this->displayAttr(),$this->noEncodeContent?$this->getContent():htmlentities($this->getContent(),ENT_COMPAT,$this->encoding),$this->tag);
			else
				return sprintf('<%s %s/>',$this->tag,$this->displayAttr());
		}
		public function getArray()
		{
			return array('attr'=>$this->getAttr(),'inner'=>$this->getContent());
		}
		public function put()
		{
			echo $this->display();
		}
		public function displayAttr()
		{
			$doc=array();
			foreach($this->attr as $attr=>$value)
				if($value===true)
					$doc[]=$attr;
				else
					$doc[]=sprintf('%s="%s"',$attr,htmlentities($value,ENT_COMPAT,$this->encoding));
			return implode(' ',$doc);
		}
		public function control()
		{
			return $this->controller->control($this->getAttr('value',''));
		}
		public function controller($controller)
		{
			$this->controller=$controller;
			return $this;
		}
		public function condition($conditions)
		{
			$this->controller->condition($conditions);
			return $this;
		}
		public function getCondition($key,$default='')
		{
			return $this->controller->getCondition($key,$default);
		}
	}
	class QTagInput extends QTag
	{
		private $error;

		function __construct($type,$name='',$id=null)
		{
			parent::__construct('input',$name,$id,false);
			if($type) $this->attr('type',$type);
			$this->controller(new QControllerText());
		}
		public function value($value)
		{
			$this->attr('value',$value);
			return $this;
		}
		public function getValue()
		{
			return $this->getAttr('value');
		}
	}
	class QTagRadioOption extends QTag
	{
		private $checked;
		protected $radioTag;

		public function __construct($radioTag,$name,$value,$checked=false)
		{
			parent::__construct('radio',$name,null,true);
			$this->radioTag=$radioTag;
			$this->value($value);
			$this->checked($checked);
		}
		public function value($value)
		{
			$this->attr('value',$value);
			return $this;
		}
		public function getValue()
		{
			return $this->getAttr('value');
		}
		public function checked($checked=true)
		{
			if($checked) $this->attr('checked','checked');
			return $this;
		}
		/* For next radio tag, use parent tag */
		public function option($value,$checked=false)
		{
			return $this->radioTag->option($value,$checked);
		}
	}
	class QTagRadio extends QTagInput
	{
		private $options,$checkedValue;

		public function __construct($name,$id=null,$checkedValue=null)
		{
			parent::__construct('radio',$name,$id);
			$this->options=array();
			$this->checked($checkedValue);
		}
		public function getArray()
		{
			$array=parent::getArray();
			if($this->checkedValue) $array['attr']+=array('checked'=>'checked');
			return $array;
		}
		public function checked($value)
		{
			$this->checkedValue=$value;
			return $this;
		}
		public function getChecked()
		{
			return $this->checkedValue;
		}
		public function display($offset=null)
		{
			$checkedValue=$this->getChecked();
			$doc=array();
			if(empty($this->options))
			{
				if($checkedValue) $this->attr('checked','checked');
				$doc[]=parent::display();
			} else
			{
				$i=0;
				foreach($this->options as $opt)
				{
					if(!is_null($offset) && $i!=$offset) continue;
					if($opt->getValue()==$checkedValue) $opt->checked(true);
					$doc[]=$opt->display();
					$i++;
				}
			}
			return implode("\n",$doc);
		}
		public function option($value,$checked=false)
		{
			$this->options[]=new QTagRadioOption($this,$this->getName(),$value,$checked);
			return $this;
		}
		public function control()
		{
			return $this->controller->control($this->getChecked());
		}
	}
	class QTagEmail extends QTagInput
	{
		public function __construct($name,$id=null)
		{
			parent::__construct('email',$name,$id);
			$this->controller(new QControllerEmail());
		}
	}
	class QTagCheckbox extends QTagInput
	{
		public function __construct($name,$id=null)
		{
			parent::__construct('checkbox',$name,$id);
			$this->controller(new QControllerCheckbox());
		}
		public function checked($val=false)
		{
			if($val) $this->attr('checked','checked');
			return $this;
		}
	}
	class QTagTel extends QTagInput
	{
		public function __construct($name,$id=null)
		{
			parent::__construct('tel',$name,$id);
			$this->controller(new QControllerTel());
		}
	}
	class QTagDate extends QTagInput
	{
		//protected $format;

		public function __construct($name,$id=null)
		{
			parent::__construct('date',$name,$id);
			$this->controller(new QControllerDate());
			//$this->format='DD/MM/YYYY';
		}
		//public function format($str)
		//{
		//	$this->format=$str;
		//	return $this;
		//}
	}
	class QTagTextarea extends QTag
	{
		public function __construct($name,$id=null)
		{
			parent::__construct('textarea',$name,$id,true);
			$this->controller(new QControllerTextarea());
		}
		public function control()
		{
			return $this->controller->control($this->getContent());
		}
	}
	class QTagOption extends QTag
	{
		private $selected;
		protected $selectTag;

		public function __construct($selectTag,$value,$content,$selected=false)
		{
			parent::__construct('option',null,null,true);
			$this->selectTag=$selectTag;
			$this->value($value);
			$this->content($content);
			$this->selected($selected);
		}
		public function value($value)
		{
			$this->attr('value',$value);
			return $this;
		}
		public function getValue()
		{
			return $this->getAttr('value');
		}
		public function selected($selected=true)
		{
			$this->attr('selected',$selected?true:null);
			return $this;
		}
		/* For next select tag, use parent tag */
		public function option($value,$content,$selected=false)
		{
			return $this->selectTag->option($value,$content,$selected);
		}
	}
	class QTagSelect extends QTag
	{
		private $options,$selectedValue;

		public function __construct($name,$id=null,$selectedValue=null)
		{
			parent::__construct('select',$name,$id,true);
			$this->controller(new QControllerSelect());
			$this->options=array();
			$this->selected($selectedValue);
		}
		public function selected($value)
		{
			$this->selectedValue=$value;
			return $this;
		}
		public function getSelected()
		{
			return $this->selectedValue;
		}
		public function display()
		{
			$selectedValue=$this->getSelected();
			$doc='';
			foreach($this->options as $opt)
			{
				if($opt->getValue()==$selectedValue) $opt->selected(true);
				$doc.=$opt->display()."\n";
			}
			$this->content($doc,true);
			return parent::display();
		}
		public function option($value,$content,$selected=false)
		{
			$option=new QTagOption($this,$value,$content,$selected);
			$this->options[]=$option;
			return $option;
		}
		public function getArray()
		{
			$selectedValue=$this->getSelected();
			$doc='';
			foreach($this->options as $opt)
			{
				if($opt->getValue()==$selectedValue)
				{
					$doc=$opt->getContent();
				}
			}
			return array_merge(array('attr'=>array_merge(array('type'=>'select'),$this->getAttr())),
			                   array('selected'=>$this->getSelected(),'selectedlabel'=>$doc));
		}
		public function control()
		{
			return $this->controller->control($this->getSelected());
		}
	}
	class QForm
	{
		protected $tagList;
		private $errors;

		public function __construct()
		{
			$this->tagList=array();
			$this->errors=array();
		}
		public function add($type,$name=null,$id=null)
		{
			switch($type)
			{
				case 'email':
					$tag=new QTagEmail($name,$id);
					break;
				case 'tel':
					$tag=new QTagTel($name,$id);
					break;
				case 'date':
					$tag=new QTagDate($name,$id);
					break;
				case 'select':
					$tag=new QTagSelect($name,$id);
					break;
				case 'textarea':
					$tag=new QTagTextarea($name,$id);
					break;
				case 'checkbox':
					$tag=new QTagCheckbox($name,$id);
					break;
				case 'radio':
					$tag=new QTagRadio($name,$id);
					break;
				default:
					$tag=new QTagInput($type,$name,$id);
					break;
			}
			$this->tagList[]=$tag;
			return $tag;
		}
		public function getNames()
		{
			$list=array();
			foreach($this->tagList as $tag)
				$list[]=$tag->getName();
			return $list;
		}
		public function getIds()
		{
			$list=array();
			foreach($this->tagList as $tag)
				$list[]=$tag->getId();
			return $list;
		}
		public function declareTag($type,$QTag)
		{

		}
		public function display2($name)
		{
			if($tag=$this->get($name))
				return $tag->display();
			return '';
		}
		public function getTag($id,$default=false)
		{
			return $this->getTagId($id,$default);
		}
		public function getTagId($id,$default=false)
		{
			foreach($this->tagList as $tag)
				if($tag->getId()==$id)
					return $tag;
			return $default;
		}
		public function getTagName($name,$default=false)
		{
			foreach($this->tagList as $tag)
				if($tag->getName()==$name)
					return $tag;
			return $default;
		}
		public function getError($name=null)
		{
			if(isset($name)) return $this->errors[$name];
			return $this->errors;
		}
		public function error($errors)
		{
			$this->errors=$errors;
			return $this;
		}
		public function control()
		{
			$this->errors=array();
			foreach($this->tagList as $tag)
				if($error=$tag->control())
					$this->errors[$tag->getName()]=$error;
			return empty($this->errors)?false:$this->errors;
		}
	}
?>
