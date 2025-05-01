<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Bug extends Entity
{
    protected $_accessible = [
        'title' => true,
        'description' => true,
        'priority' => true,
        'status' => true,
        'created_at' => false,
        'updated_at' => false,
    ];
}