<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ClassmgtTable extends Table
{
	public function initialize(array $config)
    {
        $this->belongsTo('smgt_users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }
}

?>