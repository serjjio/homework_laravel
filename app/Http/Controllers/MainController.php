<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthTokenInterface;
use App\Services\Auth\Token;
use App\Services\CheckMaxIdToModels;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * @var CheckMaxIdToModels
     */
    private $checkMaxIdToModels;

    /**
     * @var AuthTokenInterface
     */
    private $authJwtToken;

    /**
     * MainController constructor.
     * @param CheckMaxIdToModels $checkMaxIdToModels
     * @param AuthTokenInterface $authJwtToken
     */
    public function __construct(CheckMaxIdToModels $checkMaxIdToModels, AuthTokenInterface $authJwtToken)
    {
        $this->checkMaxIdToModels = $checkMaxIdToModels;
        $this->authJwtToken = $authJwtToken;
    }

    public function getList()
    {
        foreach ($this->checkMaxIdToModels->getMaxIds() as $table => $id) {
            echo $table .': ' . $id . '<br>';
        }
    }

    public function getToken(Request $request)
    {
        //{"email:'test@test.com'; "password":'123'}
        $data = json_decode($request->getContent());
        /*$user = User::query()->where([
            'email' => 'test@test.com',
            'password' => 123,
        ])->first();*/

        if (!$user instanceof User) {
            throw new AuthenticationException();
        }

        $dateExt = new \DateTime();
        $dateExt->modify('+1h');

        $token = new Token($user, $dateExt, 'editor');

        return ['token' => $this->authJwtToken->generate($token)];
    }
}
