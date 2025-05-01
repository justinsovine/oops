<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class BugsController extends AppController
{
    // Set JSON as the view class for all actions
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setClassName('Json');
    }

    /**
     * beforeFilter() - Set up CORS and allow necessary HTTP methods.
     *
     * @param EventInterface $event
     * @return void|\Cake\Http\Response
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Allow CORS for API
        $this->response = $this->response->cors($this->request)
            ->allowOrigin(['http://localhost:5173']) // Vue dev server
            ->allowMethods(['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'])
            ->allowHeaders(['Content-Type', 'Accept'])
            ->allowCredentials()
            ->exposeHeaders(['Link'])
            ->maxAge(300)
            ->build();

        // Handle preflight OPTIONS requests
        if ($this->request->is('options')) {
            return $this->response;
        }
    }

    /**
     * index() - Return all bugs ordered by creation date, wrapped in API response format.
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        // Fetch all bugs ordered by the created_at field in descending order
        $bugs = $this->Bugs->find()
            ->order(['created_at' => 'DESC'])
            ->all();

        // Set structured API response
        $this->set([
            'data' => $bugs,
            'message' => 'Bug list retrieved successfully',
            'status' => 'success',
        ]);

        $this->viewBuilder()->setOption('serialize', ['data', 'message', 'status']);
    }

    /**
     * view() - View a single bug's details.
     *
     * @param int $id The ID of the bug to view.
     * @return \Cake\Http\Response|null
     */
    public function view($id)
    {
        try {
            $bug = $this->Bugs->get($id);

            $this->set([
                'data' => $bug,
                'message' => 'Bug retrieved successfully',
                'status' => 'success',
            ]);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'data' => null,
                'message' => 'Bug not found',
                'status' => 'error',
            ]);
        }

        $this->viewBuilder()->setOption('serialize', ['data', 'message', 'status']);
    }

    /**
     * add() - Add a new bug to the system.
     *
     * @return \Cake\Http\Response|null
     */
    public function add()
    {
        // Create an empty bug entity
        $bug = $this->Bugs->newEmptyEntity();

        if ($this->request->is('post')) {
            // Patch the incoming request data to the bug entity
            $bug = $this->Bugs->patchEntity($bug, $this->request->getData());

            // Save the bug, and return the response accordingly
            if ($this->Bugs->save($bug)) {
                $this->set([
                    'data' => $bug,
                    'message' => 'Bug created successfully',
                    'status' => 'success',
                ]);
            } else {
                $this->response = $this->response->withStatus(422);
                $this->set([
                    'data' => null,
                    'message' => 'Failed to create bug',
                    'status' => 'error',
                    'errors' => $bug->getErrors(),
                ]);
            }
            
            $this->viewBuilder()->setOption('serialize', ['data', 'message', 'status', 'errors']);
        }
    }

    /**
     * edit() - Edit an existing bug's details.
     *
     * @param int $id The ID of the bug to edit.
     * @return \Cake\Http\Response|null
     */
    public function edit($id)
    {
        try {
            // Fetch the bug by ID
            $bug = $this->Bugs->get($id);

            if ($this->request->is(['patch', 'put'])) {
                // Patch the incoming data to the bug entity
                $bug = $this->Bugs->patchEntity($bug, $this->request->getData());

                if ($this->Bugs->save($bug)) {
                    $this->set([
                        'data' => $bug,
                        'message' => 'Bug updated successfully',
                        'status' => 'success',
                    ]);
                } else {
                    $this->response = $this->response->withStatus(422);
                    $this->set([
                        'data' => null,
                        'message' => 'Failed to update bug',
                        'status' => 'error',
                        'errors' => $bug->getErrors(),
                    ]);
                }
                
                $this->viewBuilder()->setOption('serialize', ['data', 'message', 'status', 'errors']);
            }
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'data' => null,
                'message' => 'Bug not found',
                'status' => 'error',
            ]);
            
            $this->viewBuilder()->setOption('serialize', ['data', 'message', 'status']);
        }
    }

    /**
     * delete() - Delete a bug by ID.
     *
     * @param int $id The ID of the bug to delete.
     * @return \Cake\Http\Response|null
     */
    public function delete($id)
    {
        // Allow only DELETE requests
        $this->request->allowMethod(['delete']);

        try {
            // Fetch the bug to delete
            $bug = $this->Bugs->get($id);

            if ($this->Bugs->delete($bug)) {
                // Return success message
                $this->set([
                    'data' => null,
                    'message' => 'Bug deleted successfully',
                    'status' => 'success',
                ]);
            } else {
                // Return error if deletion failed
                $this->response = $this->response->withStatus(500);
                $this->set([
                    'data' => null,
                    'message' => 'Failed to delete bug',
                    'status' => 'error',
                ]);
            }
            
            $this->viewBuilder()->setOption('serialize', ['data', 'message', 'status']);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            $this->response = $this->response->withStatus(404);
            $this->set([
                'data' => null,
                'message' => 'Bug not found',
                'status' => 'error',
            ]);
            
            $this->viewBuilder()->setOption('serialize', ['message', 'status', 'data']);
        }
    }
}