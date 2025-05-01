<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BugsFixture
 */
class BugsFixture extends TestFixture
{
    
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'title' => 'Submit button not working',
                'description' => 'When clicking the submit button on the contact form, nothing happens.',
                'priority' => 'High',
                'status' => 'New',
                'submitter' => 'Alice',
                'created_at' => '2025-05-01 01:06:58',
                'updated_at' => '2025-05-01 01:06:58',
            ],
            [
                'id' => 2,
                'title' => 'Page crashes on load',
                'description' => 'The dashboard page throws a 500 error intermittently.',
                'priority' => 'Critical',
                'status' => 'In Progress',
                'submitter' => 'Bob',
                'created_at' => '2025-05-01 02:15:00',
                'updated_at' => '2025-05-01 02:20:00',
            ],
            [
                'id' => 3,
                'title' => 'Spelling mistake in footer',
                'description' => 'The word “copyright” is misspelled.',
                'priority' => 'Low',
                'status' => 'Resolved',
                'submitter' => null,
                'created_at' => '2025-05-01 03:30:00',
                'updated_at' => '2025-05-01 04:00:00',
            ],
        ];

        parent::init();
    }
}
