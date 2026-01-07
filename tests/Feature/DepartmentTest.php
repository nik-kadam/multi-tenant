<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Department;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_departments()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['created_by' => null]);

        $response = $this->actingAs($admin)->get('/departments');

        $response->assertStatus(200);
        $response->assertViewIs('departments');
    }

    public function test_non_admin_cannot_view_departments()
    {
        /** @var \App\Models\User $owner */
        $owner = User::factory()->create(['created_by' => null]);
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['created_by' => $owner->id]);

        $response = $this->actingAs($user)->get('/departments');

        $response->assertStatus(403);
    }

    public function test_admin_can_add_department()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['created_by' => null]);

        $response = $this->actingAs($admin)->postJson('/add-department', [
            'name' => 'IT Department',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('departments', ['name' => 'IT Department']);
    }

    public function test_admin_can_update_department()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['created_by' => null]);
        $department = Department::create([
            'name' => 'Old Name',
            'user_id' => $admin->id
        ]);

        $response = $this->actingAs($admin)->postJson('/update-department', [
            'departmentId' => $department->id,
            'edit_name' => 'New Name',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('departments', ['name' => 'New Name']);
    }

    public function test_admin_can_delete_department()
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['created_by' => null]);
        $department = Department::create([
            'name' => 'To Delete',
            'user_id' => $admin->id
        ]);

        $response = $this->actingAs($admin)->postJson('/delete-department', [
            'delDepartmentId' => $department->id,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }
}
