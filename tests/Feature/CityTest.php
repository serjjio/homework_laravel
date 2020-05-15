<?php

namespace Tests\Feature;

use App\City;
use App\Services\Auth\AuthJwtToken;
use App\Services\Auth\Token;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function getCityByName()
    {
        $name = 'Kyiv';

        factory(City::class, 3)->create();

        $city = factory(City::class)->create([
            'name' => $name
        ]);

        $this->assertCount(4, City::all());

        $this->assertEquals($name, $city->name);

        $user = factory(User::class)->create();
        $response = $this->get('/api/cities/'. $city->id,['Authorization' => 'Bearer ' . $this->getToken($user)]);

        $response->assertStatus(200);
        $response->assertJson(['data' => ['id' => 4, 'name' => $name]]);
    }

    /**
     * @test
     */
    public function getCreateCity()
    {
        $name = 'Kyiv';

        factory(City::class, 10)->create();

        $this->assertCount(10, City::all());
    }

    public function getToken(User $user): string
    {
        $dateExt = new \DateTime();
        $dateExt->modify('+1 hour');

        $token = Token::getInstance($user, $dateExt, 'admin');
        $auth = new AuthJwtToken();

        return $auth->generate($token);
    }
}
