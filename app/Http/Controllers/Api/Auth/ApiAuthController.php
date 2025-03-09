<?php

namespace App\Http\Controllers\Api\Auth;

use App\DTO\Empresa\EmpresaDTO;
use App\DTO\User\UserDTO;
use App\Services\AuthService\AuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {

        // Validação dos dados
        $request->validate([
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|unique:users,email',
            'user.password_confirmation' => 'required|string|min:8',
            'company.company_name' => 'required|string|max:255',
            'company.company_email' => 'required|email|unique:empresas,company_email',
            'company.company_phone' => 'required|string|max:15',
            'company.cnpj' => 'required|string|max:18|unique:empresas,cnpj',
            'company.social_reason' => 'required|string|max:255',
        ]);

        $userDTO = new UserDTO(
            $request->input('user.name'),
            $request->input('user.email'),
            $request->input('user.password_confirmation'), // Você vai usar a senha confirmada
            $request->input('user.password_confirmation')  // Verifique se isso está correto
        );

        $empresaDTO = new EmpresaDTO(
            $request->input('company.company_name'),
            $request->input('company.company_email'),
            $request->input('company.company_phone'),
            $request->input('company.cnpj'),
            $request->input('company.social_reason')
        );


        $result = $this->authService->register($userDTO, $empresaDTO);

        return $result;
        if (!$result['status']) {
            return response()->json($result['errors'], 422);
        }

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'user' => $result['user'],
            'empresa' => $result['empresa']
        ], 200);
    }

    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        $result = $this->authService->login($data);

        if (!$result['status']) {
            return response()->json(['message' => $result['message'] ?? $result['errors']], 401);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'user' => $result['user'],
            'evolu_token' => $result['token'],
        ], 200);
    }

    public function logout()
    {
        $result = $this->authService->logout();

        return response()->json([
            'message' => $result['message'],
        ], 200);
    }
}
