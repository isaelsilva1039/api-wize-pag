<?php


namespace App\Services\AuthService;

use App\DTO\Empresa\EmpresaDTO;
use App\DTO\User\UserDTO;
use App\Models\Empresa\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    // Método para registrar um novo usuário

    /**
     * @param UserDTO $userDTO
     * @param EmpresaDTO $empresaDTO
     * @return array
     */
    public function register(UserDTO $userDTO, EmpresaDTO $empresaDTO)
    {
        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $userDTO->name,
                'email' => $userDTO->email,
                'password' => Hash::make($userDTO->password),
            ]);

            $empresa = Empresa::create([
                'company_name' => $empresaDTO->companyName,
                'company_email' => $empresaDTO->companyEmail,
                'company_phone' => $empresaDTO->companyPhone,
                'cnpj' => $empresaDTO->cnpj,
                'social_reason' => $empresaDTO->socialReason,
                'chave' => (string) \Ramsey\Uuid\Guid\Guid::uuid4(),
            ]);

            $user->empresas()->attach($empresa->id);

            $user->empresa_selecionada = $empresa->id;
            $user->save();

            DB::commit();

            Auth::login($user);

            return [
                'status' => true,
                'user' => $user,
                'empresa' => $empresa,
            ];
        } catch (\Exception $e) {

            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Ocorreu um erro ao registrar o usuário e a empresa.',
                'error' => $e->getMessage(),
            ];
        }
    }


    // Método para fazer login e retornar o token
    public function login(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = Auth::user();
            $token = $user->createToken('evolu-pay')->plainTextToken; // Cria um token para o usuário

            return [
                'status' => true,
                'user' => $user,
                'token' => $token, // Retorna o token
            ];
        }

        return [
            'status' => false,
            'message' => 'Credenciais inválidas',
        ];
    }

    // Método para deslogar
    public function logout()
    {
        Auth::user()->tokens->delete(); // Remove todos os tokens do usuário

        return [
            'status' => true,
            'message' => 'Usuário deslogado com sucesso',
        ];
    }
}
