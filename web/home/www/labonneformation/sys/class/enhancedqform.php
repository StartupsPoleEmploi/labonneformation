<?php
	require_once(QUARK_PATH.'/qform.php');

	class MyController extends QController
	{
		public function isHidden()
		{
			if($showIf=$this->getCondition('showif',false))
			{
				//echo "$showIf<br>";
			}
			return false;
		}
		public function control($value)
		{
			if($this->isHidden()) return false;
			return parent::control($value);
		}
	}
	class MyControllerDate extends MyController
	{
		public function transform($val)
		{
			if(preg_match('#^(\d+)/(\d+)/(\d+)$#',$val,$m))
				return sprintf('%04d%02d%02d',$m[3],$m[2],$m[1]);
			return '';
		}
		public function isMin($val1,$val2)
		{
			return self::transform($val1)<self::transform($val2);
		}
		public function isMax($val1,$val2)
		{
			return self::transform($val1)>self::transform($val2);
		}
		public function control($value)
		{

			if($error=parent::control($value)) return $error;
			if(preg_match('#^(\d+)/(\d+)/(\d+)$#',$value,$m))
				if(!checkdate($m[2],$m[1],$m[3]))
					return 'badformat';
			return false;
		}
	}
	class MyControllerGroup extends MyController
	{
	}

	function getNeeds($needs)
	{
		$jsAge='function calcAge2(dateString)
	                {
	                	//alert(dateString);
	                	if(!dateString.match(/^\d{1,2}\/\d{1,2}\/\d{4}$/g)) return 0;
	                	var today=new Date();
	                	var birthDate=new Date(dateString.replace(/^(\d+?)\/(\d+?)\/(\d+?)$/,"$3"),dateString.replace(/^(\d+?)\/(\d+?)\/(\d+?)$/,"$2"),dateString.replace(/^(\d+?)\/(\d+?)\/(\d+?)$/,"$1"));
	                	var age=today.getFullYear()-birthDate.getFullYear();
	                	var m=today.getMonth()-birthDate.getMonth();
	                	if(m<0 || (m===0 && today.getDate()<birthDate.getDate())) age--;
	                	return age;
	                }';
	    foreach($needs as $need=>$v)
	    {
	    	switch($need)
	    	{
	    		case 'age':
	    			return $jsAge;
	    			break;
	    		default:
	    			# code...
	    			break;
	    	}
	    }
	}
	function getJs($tag,&$needs=array(),$isGroup=false)
	{
		$js=array();
		$name=$tag->getId();
		$fields=array();
		$event=false;
		if($showIf=$tag->getCondition('showif',''))
		{
			$test=preg_replace_callback('#([a-zA-Z0-9_]+)\.([a-z]+)#',function($m) use(&$fields,&$js,$tag,&$event)
				{
					$a=$m[1];
					$b=$m[2];
					$fields["#$a"]=true;
					//echo "$b, ";
					switch($b)
					{
						default:
							$replace="\$(\"#$a\").prop(\"$b\")";
							break;
						case 'age':
							$replace="calcAge(\$(\"#$a\").val())";
							$needs['age']=true;
							break;
						case 'content':
							$replace="\$(\"#$a\").inner()";
							break;
						case 'val':
							$replace="\$(\"#$a\").val()";
							break;
						case 'clicked';
							$event=true;
							$replace=sprintf("\$(\"#%s\").is(\":hidden\")",$tag->getId());
							break;
					}
					return $replace;
				},$showIf);
			$parent='.parent()';
			if($isGroup) $parent='.parent().parent().parent()';
			$js[]=sprintf("if(%s) $(\"#%s\")$parent.show(); else $(\"#%s\")$parent.hide();",$test,$name,$name);
			$js[]=sprintf("$(\"%s\").on(\"change keyup click\",function(e)\n".
			             "{\n".
			             "	if(%s) $(\"#%s\")$parent.slideDown(); else $(\"#%s\")$parent.slideUp();\n".
			             "	%s".
			             "});\n",
			             implode(', ',array_keys($fields)),
			             $test,
			             $name,$name,
			            ''/*$event?"e.preventDefault(); return false;":'return true;'*/);
		}
		return implode("\n",$js);
	}

	class EnhancedQTagGroup extends QTag
	{
		protected $form;
		protected $label;

		public function __construct($name,$label,$id=null)
		{
			$this->form=new EnhancedQForm();
			parent::__construct('div',null,$name?$name:$id,true);
			$this->label=$label;
			return $this;
		}
		public function getJs(&$needs)
		{
			$js=array();
			$js[]=getJs($this,$needs,true);
			$js[]=$this->form->getJs(null,$needs);
			return implode("\n",$js);
		}
		public function display($errors=array())
		{
			$doc='';
			$this->content($this->form->display($errors),true);
			if(is_null($this->label))
			{
				$doc=sprintf("<div class=\"row\">\n<div class=\"col-md-12\">%s</div>\n</div>\n",parent::display($errors));
			} else
			{
				$doc=sprintf("<div class=\"row\">\n<span class=\"col-md-12 label\">%s</span>\n<div class=\"col-md-12\">%s</div>\n</div>",$this->label,parent::display($errors));
			}
			return $doc;
		}
		public function getArray()
		{
			$group=array();
			foreach($this->form->getArray() as $grp)
				$group[]=$grp['inner'];
			return array('attr'=>array_merge(array('type'=>'group','label'=>$this->label),$this->getAttr()),'inner'=>$group);
		}
		public function getForm()
		{
			return $this->form;
		}
		public function control($names=null)
		{
			return $this->form->control($names);
		}
	}
	class EnhancedQTagLabel extends QTag
	{
		public function __construct($name,$id=null)
		{
			parent::__construct('span',null,$name?$name:$id,true);
		}
		public function getJs(&$needs)
		{
			return getJs($this,$needs);
		}
		public function getArray()
		{
			return array('attr'=>array_merge(array('type'=>'label'),parent::getAttr()),'inner'=>parent::getArray());
		}
		public function control()
		{
			return false;
		}
	}
	class EnhancedQTagCheckbox extends QTagCheckbox
	{
		public function display($errors=array())
		{
			$this->attr('class','sr-only');
			return parent::display($errors);
		}
		public function getJs(&$needs)
		{
			return getJs($this,$needs);
		}
	}
	class EnhancedQTagRadio extends QTagRadio
	{
		public function display($errors=array())
		{
			$this->attr('class','sr-only');
			return parent::display($errors);
		}
		public function getJs(&$needs)
		{
			return getJs($this,$needs);
		}
	}
	class EnhancedQTagInput extends QTagInput
	{
		public function getJs(&$needs)
		{
			return getJs($this,$needs);
		}
	}
	class EnhancedQTagSelect extends QTagSelect
	{
		public function getJs(&$needs)
		{
			return getJs($this,$needs);
		}
	}

	class EnhancedQForm extends QForm
	{
		public function __construct()
		{
			parent::__construct();
		}
		public function add($type,$name=null,$id=null)
		{
			switch($type)
			{
				case 'date':
					$tag=new EnhancedQTagInput('text',$name,$id);
					$tag->controller(new MyControllerDate);
					$this->tagList[]=$tag;
					break;
				case 'group':
					$tag=new EnhancedQTagGroup($name,$name);
					$tag->controller(new MyControllerGroup);
					$this->tagList[]=$tag;
					break;
				case 'label':
					$tag=new EnhancedQTagLabel($name,$id);
					$tag->controller(new MyController);
					$this->tagList[]=$tag;
					break;
				case 'checkbox':
					$tag=new EnhancedQTagCheckbox($name,$id);
					$tag->controller(new MyController);
					$this->tagList[]=$tag;
					break;
				case 'radio':
					$tag=new EnhancedQTagRadio($name,$id);
					$tag->controller(new MyController);
					$this->tagList[]=$tag;
					break;
				case 'text':
					$tag=new EnhancedQTagInput($type,$name,$id);
					$tag->controller(new MyController);
					$this->tagList[]=$tag;
					break;
				case 'select':
					$tag=new EnhancedQTagSelect($name,$id);
					$tag->controller(new MyController);
					$this->tagList[]=$tag;
					break;
				default:
					$tag=new EnhancedQTagInput($type,$name,$id);
					$tag->controller(new MyController);
					$this->tagList[]=$tag;
					break;
			}
			return $tag;
		}
		public function group($name,$label=null)
		{
			$tag=new EnhancedQTagGroup($name,$label);
			$tag->controller(new MyControllerGroup);
			$this->tagList[]=$tag;
			return $tag;
		}
		public function display($errors=array(),$ids=null)
		{
			if(empty($ids)) $ids=$this->getIds();
			$doc='';
			foreach($ids as $id)
			{
				$tag=$this->getTag($id);
				$tagName=$tag->getName();
				$errorToDisplay=($errors && array_key_exists($tagName,$errors))?true:false;
				$errorClass=$errorToDisplay?' error':'';

				if($errorToDisplay) $tag->attr('class',$tag->getAttr('class','').' error');
				$labelBefore=$tag->getAttr('label-before',''); $tag->attr('label-before');
				$labelAfter=$tag->getAttr('label-after',''); $tag->attr('label-after');
				$indent=$tag->getAttr('indent',null); $tag->attr('indent');
				if($labelBefore) $labelBefore="<span class=\"label-before\">$labelBefore</span>&nbsp;";
				if($labelAfter) $labelAfter="&nbsp;<label for=\"$id\" class=\"label-after$errorClass\">$labelAfter</label>";
				if(is_null($indent)) $indent=0;
				$indent=' group-indent'.$indent;
				$col=sprintf("<div class=\"col-md-12%s form-line\">%s%s%s</div>",$indent,$labelBefore,$tag->display($errors,$ids),$labelAfter);
				$doc.="<div class=\"row\">$col</div>\n";
			}
			return $doc;
		}
		public function getArray($ids=null)
		{
			if(empty($ids)) $ids=$this->getIds();
			$doc=array();
			foreach($ids as $id)
			{
				$tag=$this->getTag($id);
				$tagName=$tag->getName();
				if($errors && array_key_exists($tagName,$errors)) $tag->attr('class',$tag->getAttr('class','').' error');
				$labelBefore=$tag->getAttr('label-before','');// $tag->attr('label-before');
				$labelAfter=$tag->getAttr('label-after','');// $tag->attr('label-after');
				$indent=$tag->getAttr('indent',null);// $tag->attr('indent');
				$line=array('attr'=>array('type'=>'form'));
				if($labelBefore) $line['labelbefore']=$labelBefore;
				if($labelAfter) $line['labelafter']=$labelAfter;
				if(!is_null($indent)) $line['indent']=$indent;
				$line['inner']=$tag->getArray($ids);
				$doc[]=$line;
				//$doc.=sprintf("<div class=\"col-md-12%s\">%s%s%s</div>",$indent,$labelBefore,$tag->display($errors,$ids),$labelAfter);
			}
			return $doc;
		}
		public function getJs($ids=array(),&$needs=array())
		{
			$js=array();
			if(empty($ids)) $ids=$this->getIds();
			//print_r($ids);
			foreach($ids as $id)
			{
				if($tag=$this->getTag($id))
					if($j=$tag->getJs($needs))
						$js[]=$j;
			}
			return implode("\n",$js);
		}
		public function getNeeds($needs)
		{
			return getNeeds($needs);
		}
		public function getTag($id,$default=false)
		{
			foreach($this->tagList as $tag)
			{
				$tagType=$tag->getTag();
				$tagId=$tag->getId();
				if($tagType=='div' && $tagId!=$id)
					$tag=$tag->getForm()->getTag($id);
				if($tag && $tag->getId()==$id)
					return $tag;
			}
			return $default;
		}
		private function showIf($cond)
		{
			if($cond) return true;
			return false;
		}
		public function control($names=null)
		{
			$this->errors=array();
			foreach($this->tagList as $tag)
			{
				$name=$tag->getName();
				if($error=$tag->control($names))
				{
					if(is_array($error))
						$this->errors+=$error;
					else
						$this->errors[$name]=$error;
				}
			}
			if(!empty($names))
			{
				$errors=array();
				foreach($this->errors as $name=>$error)
					if(in_array($name,$names))
						$errors[$name]=$error;
				$this->errors=$errors;
			}

			return empty($this->errors)?false:$this->errors;
		}
	}
?>