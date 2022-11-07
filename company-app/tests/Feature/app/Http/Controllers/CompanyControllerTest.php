<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    /**
     * Feature test of get all companies.
     *
     * @return void
     */
    public function testAllCompaniesCanBeListed()
    {
        $response = $this->get(route('companies.index'));

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
    public function testCompanyCanBeCreated()
    {
        $payload = [
            'corporate_name' => fake()->company(),
            'cnpj' => fake()->unique()->randomNumber(9)
        ];

        $response = $this->post(route('companies.store'), $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('companies', $payload);
    }

    /**
     * Test to validate submission of all required data for creating an employee.
     *
     * @return void
     */
    public function testAllMandatoryDataMustBeSubmittedToCreateAnCompany()
    {
        $payload = [
            'foo' => 'bar'
        ];

        $response = $this->post(route('companies.store'), $payload, [
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
    public function testSpecificCompanyCanBeRetrieved()
    {
        $company = Company::factory()->create();

        $response = $this->get(route('companies.show', $company->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            "corporate_name" => $company->corporate_name,
            "cnpj" => (string)$company->cnpj
        ]);
    }

    /**
     * Feature test to update an employee.
     *
     * @return void
     */
    public function testCompanyCanBeUpdated()
    {
        $company = Company::factory()->create();

        $company->corporate_name = "Company name updated";
        $company = $company->toArray();

        $response = $this->put(route('companies.update', $company['id']), $company);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $this->assertDatabaseHas('companies', $company);
    }

    /**
     * Feature test to delete an employee.
     *
     * @return void
     */
    public function testCompanyCanBeDeleted()
    {
        $company = Company::factory()->create();

        $response = $this->delete(route('companies.destroy', $company->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
