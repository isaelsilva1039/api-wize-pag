<?php

namespace App\Services;

use App\DTO\PaymentLinkDTO;
use App\DTO\PaymentLinkEditDTO;
use App\Models\Empresa\EmpresaLinksPagamento;
use App\Resources\LinkPagamento\LinkPagamentoUserResource;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentLinkService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {

        $this->baseUrl = config('assas.assas.base_url');
        $this->apiKey = config('assas.assas.api_key');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'access_token' => $this->apiKey,
            ]
        ]);
    }

    /**
     *  Creat links of payments in company
     * @param $paymentLinkDTO PaymentLinkDTO
     * @return string
     */
    public function createPaymentLink(PaymentLinkDTO $paymentLinkDTO)
    {
        $data = '';
        $status_code = 200;

        try {
            $paymentLinkUrl = $this->baseUrl . '/paymentLinks';

            $requestData = [
                'billingType' => $paymentLinkDTO->billingType ?? null,
                'chargeType' => $paymentLinkDTO->chargeType ?? null,
                'name' => $paymentLinkDTO->name ?? null,
                'description' => $paymentLinkDTO->description ?? null,
                'endDate' => $paymentLinkDTO->endDate ?? null,
                'value' => $paymentLinkDTO->value ?? null,
                'dueDateLimitDays' => $paymentLinkDTO->dueDateLimitDays ?? null,
                'externalReference' => $paymentLinkDTO->externalReference ?? null,
                'notificationEnabled' => $paymentLinkDTO->notificationEnabled ?? null,
                'callback' => $paymentLinkDTO->callback ?? [],
                'isAddressRequired' => $paymentLinkDTO->isAddressRequired ?? true,
                'maxInstallmentCount' => $paymentLinkDTO->maxInstallmentCount ?? null,
                'cycle' => $paymentLinkDTO->cycle ?? null
            ];


            $currentDate = Carbon::now()->format('Y-m-d');

            if (!$paymentLinkDTO->endDate || Carbon::parse($paymentLinkDTO->endDate)->lt(Carbon::parse($currentDate))) {
                return new JsonResponse(
                    [
                        'data' => 'A data de encerramento não pode ser anterior à data atual.',
                        'status_code' => 400
                    ]
                );
            }

            $response = $this->client->post($paymentLinkUrl, [
                'json' => $requestData,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);


            // Verifica se a resposta do Assas foi bem-sucedida
            if ($response->getStatusCode() == 200 &&  isset($data['id'])) {
                if (Auth::check()) {
                    EmpresaLinksPagamento::create([
                        'empresa_id' => $paymentLinkDTO->empresa_id,
                        'criado_por' => Auth::user()->id, // Usuário que criou (pode vir do contexto ou DTO)
                        'id_link_assas' => $data['id'],
                        'name' => $data['name'],
                        'value' => $data['value'],
                        'active' => $data['active'],
                        'chargeType' => $data['chargeType'],
                        'url' => $data['url'],
                        'billingType' => $data['billingType'],
                        'subscriptionCycle' => $data['subscriptionCycle'],
                        'description' => $data['description'],
                        'endDate' => $data['endDate'],
                        'deleted' => $data['deleted'],
                        'viewCount' => $data['viewCount'],
                        'maxInstallmentCount' => $data['maxInstallmentCount'],
                        'dueDateLimitDays' => $data['dueDateLimitDays'],
                        'notificationEnabled' => $data['notificationEnabled'],
                        'isAddressRequired' => $data['isAddressRequired'],
                        'externalReference' => $data['externalReference'],
                        'criado_em' => Carbon::now(),
                    ]);
                }
            }

        }catch (\Exception $exception){
            $data = $exception->getMessage();
            $status_code = $exception->getCode();
        }

       return [$data , $status_code];

    }

    /**
     * List all thes links of payments a company
     * @param Request
     */
    public function listaLinksPagamento(Request $request)
    {
        $data = [];
        $user = Auth::user();
        $empresaSelecionadaId = $user->empresa_selecionada;
        $perPage = $request->input('per_page', 15);

        try {

            $empresaLinksPagamentos = EmpresaLinksPagamento::where('empresa_id', $empresaSelecionadaId)->orderBy('created_at', 'desc')->paginate($perPage);

            $linksPagamento=  LinkPagamentoUserResource::collection($empresaLinksPagamentos);

            $data = [
                'status' => 'success',
                'status_code' => 200,
                'links' => $linksPagamento,
                'meta' => [
                        'current_page' => $empresaLinksPagamentos->currentPage(),
                        'total_pages' => $empresaLinksPagamentos->lastPage(),
                        'total_items' => $empresaLinksPagamentos->total(),
                        'per_page' => $empresaLinksPagamentos->perPage(),
                    ]
                ];

        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Erro ao listar os links de pagamento.',
                'error' => $exception->getMessage(),
                'status' => 'erro',
                'status_code' => $exception->getCode(),
            ], 500);
        }

        return $data;
    }

    /**
     * update in one link of payments
     * @param Request
     */
    public function editPaymentLink(int $id, PaymentLinkEditDTO $paymentLinkDTO)
    {

        DB::beginTransaction();
        try {

            $empresaLinksPagamento = EmpresaLinksPagamento::findOrFail($id);

            $empresaLinksPagamento->update([
                'active' => $paymentLinkDTO->active,
                'billingType' => $paymentLinkDTO->billingType,
                'chargeType' => $paymentLinkDTO->chargeType,
                'name' => $paymentLinkDTO->name,
                'description' => $paymentLinkDTO->description,
                'endDate' => $paymentLinkDTO->endDate,
                'value' => $paymentLinkDTO->value,
                'dueDateLimitDays' => $paymentLinkDTO->dueDateLimitDays,
                'externalReference' => $paymentLinkDTO->externalReference,
                'notificationEnabled' => $paymentLinkDTO->notificationEnabled,
                'callback' => $paymentLinkDTO->callback,
                'isAddressRequired' => $paymentLinkDTO->isAddressRequired,
                'maxInstallmentCount' => $paymentLinkDTO->maxInstallmentCount,
                'cycle' => $paymentLinkDTO->cycle,
                'empresa_id' => $paymentLinkDTO->empresa_id,
            ]);

           $response = $this->updatePaymentLinkInAssas($empresaLinksPagamento, $paymentLinkDTO);

            if ($response['status'] !== 'success') {
                throw new \Exception('Erro ao atualizar no Assas');
            }

            DB::commit();
            return [$empresaLinksPagamento, 200];
        } catch (\Exception $e) {
            DB::rollBack();
            return [['error' => $e->getMessage()], 500];
        }
    }

    /**
     * update in one link of payments of comapny in Assas
     * @param Request
     */
    private function updatePaymentLinkInAssas(EmpresaLinksPagamento $empresaLinksPagamento, PaymentLinkEditDTO $paymentLinkDTO)
    {
        $apiUrl = $this->baseUrl . "/paymentLinks/{$empresaLinksPagamento->id_link_assas}";

        $paymentlinkEdit =  [
            'active' => $paymentLinkDTO->active,
            'billingType' => $paymentLinkDTO->billingType,
            'chargeType' => $paymentLinkDTO->chargeType,
            'name' => $paymentLinkDTO->name,
            'description' => $paymentLinkDTO->description,
            'endDate' => $paymentLinkDTO->endDate,
            'value' => $paymentLinkDTO->value,
            'dueDateLimitDays' => $paymentLinkDTO->dueDateLimitDays,
            'externalReference' => $paymentLinkDTO->externalReference,
            'notificationEnabled' => $paymentLinkDTO->notificationEnabled,
            'callback' => $paymentLinkDTO->callback,
            'isAddressRequired' => $paymentLinkDTO->isAddressRequired,
            'maxInstallmentCount' => $paymentLinkDTO->maxInstallmentCount,
            'cycle' => $paymentLinkDTO->cycle,
            'empresa_id' => $paymentLinkDTO->empresa_id,
        ];

        $response = $this->client->put($apiUrl, ['json' => $paymentlinkEdit]);

        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['id']) {
            return ['status' => 'success', 'data' => $data];
        }

        return ['status' => 'error', 'message' => $response->body()];
    }

}
