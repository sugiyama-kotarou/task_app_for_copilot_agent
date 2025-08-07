<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the tasks index route exists and returns expected status.
     * Note: This will return 500 initially since views don't exist yet,
     * but the route should be accessible.
     */
    public function test_tasks_index_route_exists(): void
    {
        $response = $this->get('/tasks');
        
        // Since we haven't created views yet, this might return 500
        // but the route should exist (not 404)
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * Test that the tasks create route exists.
     */
    public function test_tasks_create_route_exists(): void
    {
        $response = $this->get('/tasks/create');
        
        // Since we haven't created views yet, this might return 500
        // but the route should exist (not 404)
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * Test that the tasks edit route exists with a valid task.
     */
    public function test_tasks_edit_route_exists(): void
    {
        $task = Task::create([
            'title' => 'Test Task for Edit',
        ]);

        $response = $this->get("/tasks/{$task->id}/edit");
        
        // Since we haven't created views yet, this might return 500
        // but the route should exist (not 404)
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * Test that the tasks show route exists with a valid task.
     */
    public function test_tasks_show_route_exists(): void
    {
        $task = Task::create([
            'title' => 'Test Task for Show',
        ]);

        $response = $this->get("/tasks/{$task->id}");
        
        // Since we haven't created views yet, this might return 500
        // but the route should exist (not 404)
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * Test that route model binding works correctly.
     */
    public function test_route_model_binding_for_nonexistent_task(): void
    {
        $response = $this->get('/tasks/99999');
        
        // Should return 404 for non-existent task
        $response->assertStatus(404);
    }
}
