<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class BugsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('bugs');
        $this->setPrimaryKey('id');
        
        // Configure Timestamp behavior with custom field names
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                ]
            ]
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title', 'Title is required')
            ->notEmptyString('description', 'Description is required')
            ->notEmptyString('priority', 'Priority is required')
            ->notEmptyString('status', 'Status is required');

        return $validator;
    }
}