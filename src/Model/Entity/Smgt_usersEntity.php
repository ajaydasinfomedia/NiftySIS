<?php
namespace App\Model\Entity;
use Cake\ORM\Entity
class Smgt_users extends Entity
{
	protected function _getclassname($classname)
    {
        return ucwords($classname);
    }
	 protected function _setclassname($classname)
    {
        $this->set('slug', Inflector::slug($classname));
        return $classname;
    }
}

?>