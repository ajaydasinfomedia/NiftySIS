<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class Smgt_subjectTable extends Table
{
	public function initialize(array $config)
    {
        $this->hasMany('smgt_users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }
}

?>