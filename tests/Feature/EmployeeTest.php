<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_employee()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/add-employee', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'position' => 'Developer',
            'department' => 'IT',
            'salary' => 50000,
            'joining_date' => '2023-01-01',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('employees', ['email' => 'john@example.com']);
    }

    public function test_user_can_update_employee()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $employee = Employee::create([
            'user_id' => $user->id,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'position' => 'Junior',
            'department' => 'IT',
            'salary' => 30000,
            'joining_date' => '2023-01-01',
        ]);

        $response = $this->actingAs($user)->postJson('/update-employee', [
            'employeeId' => $employee->emp_id,
            'edit_name' => 'New Name',
            'edit_email' => 'new@example.com',
            'edit_position' => 'Senior',
            'edit_department' => 'IT',
            'edit_salary' => 60000,
            'edit_joining_date' => '2023-01-01',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('employees', ['name' => 'New Name']);
    }

    public function test_user_can_delete_employee()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $employee = Employee::create([
            'user_id' => $user->id,
            'name' => 'To Delete',
            'email' => 'delete@example.com',
            'position' => 'Temp',
            'department' => 'Temp',
            'salary' => 10000,
            'joining_date' => '2023-01-01',
        ]);

        $response = $this->actingAs($user)->postJson('/delete-employee', [
            'delEmployeeId' => $employee->emp_id,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertSoftDeleted('employees', ['emp_id' => $employee->emp_id]);
    }
}
