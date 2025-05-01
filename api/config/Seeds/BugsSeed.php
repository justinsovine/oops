<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * BugsSeed seed.
 */
class BugsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        // Load the Bugs model to leverage its Timestamp behavior
        $bugsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Bugs');
        
        $data = [
            [
                'title' => 'Submit button not working',
                'description' => 'Clicking submit does nothing on mobile Safari.',
                'priority' => 'High',
                'status' => 'New',
            ],
            [
                'title' => 'Page crashes on load',
                'description' => 'Dashboard intermittently 500s on load.',
                'priority' => 'Critical',
                'status' => 'In Progress',
            ],
            [
                'title' => 'Spelling mistake in footer',
                'description' => '"Copryight" instead of "Copyright".',
                'priority' => 'Low',
                'status' => 'Resolved',
            ],
        ];
        
        foreach ($data as $bug) {
            $entity = $bugsTable->newEntity($bug);
            $bugsTable->save($entity);
        }
    }
}