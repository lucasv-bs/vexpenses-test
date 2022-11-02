<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    /**
     * Feature test of get all employees.
     *
     * @return void
     */
    public function testAllEmployeesCanBeListed()
    {
        $response = $this->get(route('employees.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('current_page')->etc()
        );
    }

    /**
     * Feature test of create an employee.
     *
     * @return void
     */
    public function testEmployeeCanBeCreated()
    {
        $payload = [
            'name' => fake()->name(),
            'birth_date' => fake()->dateTimeBetween('-100 years')->format('Y-m-d'),
            'registration_number' => fake()->unique()->randomNumber(),
            'company_id' => fake()->numberBetween(1, 10),
        ];

        $response = $this->post(route('employees.store'), $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('employees', $payload);
    }

    /**
     * Test to validate submission of all required data for creating an employee.
     *
     * @return void
     */
    public function testAllMandatoryDataMustBeSubmittedToCreateAnEmployee()
    {
        $payload = [
            'foo' => 'bar'
        ];

        $response = $this->post(route('employees.store'), $payload, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Feature test of get an specific employee.
     *
     * @return void
     */
    public function testSpecifEmployeeCanBeRetrieved()
    {
        $employee = Employee::factory()->create();

        $response = $this->get(route('employees.show', $employee->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'name' => $employee->name,
            'birth_date' => $employee->birth_date,
            'registration_number' => $employee->registration_number,
            'company_id' => $employee->company_id,
        ]);
    }

    /**
     * Feature test to update an employee.
     *
     * @return void
     */
    public function testEmployeeCanBeUpdated()
    {
        $employee = Employee::factory()->create();

        $employee->name = "User name updated";
        $employee = $employee->toArray();

        $response = $this->put(route('employees.update', $employee['id']), $employee);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseHas('employees', $employee);
    }

    /**
     * Feature test to delete an employee.
     *
     * @return void
     */
    public function testEmployeeCanBeDeleted()
    {
        $employee = Employee::factory()->create();

        $response = $this->delete(route('employees.destroy', $employee->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
