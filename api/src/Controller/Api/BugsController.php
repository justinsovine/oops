<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\EventInterface;

class BugsController extends AppController
{
    /**
     * beforeFilter() - Set up CORS and allow necessary HTTP methods.
     *
     * @param EventInterface $event
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Allow CORS for API (adjust the origin as needed for development or production)
        $this->response = $this->response->cors($this->request)
            ->allowOrigin(['http://localhost:5173']) // Vue dev server or adjust for production
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
     * index() - Return all bugs ordered by creation date, serialized as JSON.
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        // Fetch all bugs ordered by the created_at field in descending order
        $bugs = $this->Bugs->find()
            ->order(['created_at' => 'DESC'])
            ->all();

        // Disable view rendering and serialize the 'bugs' array into JSON
        $this->viewBuilder()->setClassName('Json');  // Tell Cake to return JSON
        $this->set('bugs', $bugs);
        $this->viewBuilder()->setOption('serialize', ['bugs']);
    }

    /**
     * view() - View a single bug's details.
     *
     * @param int $id The ID of the bug to view.
     * @return \Cake\Http\Response|null
     */
    public function view($id)
    {
        // Fetch the bug by ID
        $bug = $this->Bugs->get($id);

        // Disable view rendering and serialize the 'bug' entity into JSON
        $this->viewBuilder()->setClassName('Json');  // Tell Cake to return JSON
        $this->set('bug', $bug);
        $this->viewBuilder()->setOption('serialize', ['bug']);
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
                // Return the saved bug entity as JSON
                $this->viewBuilder()->setClassName('Json');
                $this->set('bug', $bug);
                $this->viewBuilder()->setOption('serialize', ['bug']);
            } else {
                // Handle validation errors and return them
                $this->response = $this->response->withStatus(422);
                $this->viewBuilder()->setClassName('Json');
                $this->set('errors', $bug->getErrors());
                $this->viewBuilder()->setOption('serialize', ['errors']);
            }
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
        // Fetch the bug by ID
        $bug = $this->Bugs->get($id);

        if ($this->request->is(['patch', 'put'])) {
            // Patch the incoming data to the bug entity
            $bug = $this->Bugs->patchEntity($bug, $this->request->getData());

            if ($this->Bugs->save($bug)) {
                // Return the updated bug as JSON
                $this->viewBuilder()->setClassName('Json');
                $this->set('bug', $bug);
                $this->viewBuilder()->setOption('serialize', ['bug']);
            } else {
                // Return validation errors if save failed
                $this->response = $this->response->withStatus(422);
                $this->viewBuilder()->setClassName('Json');
                $this->set('errors', $bug->getErrors());
                $this->viewBuilder()->setOption('serialize', ['errors']);
            }
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

        // Fetch the bug to delete
        $bug = $this->Bugs->get($id);

        if ($this->Bugs->delete($bug)) {
            // Return a 204 No Content status if deletion was successful
            $this->response = $this->response->withStatus(204);
        } else {
            // Return a 500 Internal Server Error if deletion failed
            $this->response = $this->response->withStatus(500);
        }

        return $this->response;
    }
}
