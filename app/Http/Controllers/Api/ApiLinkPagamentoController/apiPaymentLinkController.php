<?php

namespace App\Http\Controllers\Api\ApiLinkPagamentoController;

use App\DTO\PaymentLinkDTO;
use App\Services\PaymentLinkService;
use App\Transformes\LinkPagamento\LinkPagamentoTransforme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class apiPaymentLinkController
{

    /** @var PaymentLinkService  */
    protected $paymentLinkService;

    public function __construct(PaymentLinkService $assasService)
    {
        $this->paymentLinkService = $assasService;
    }

    /**
     * create payment link
     * @param Request $request
     * @return JsonResponse
     */
    public function createLink(Request $request)
    {
            // Criando o DTO com os dados da requisição
            $paymentLinkDTO = new PaymentLinkDTO(
                $request->input('billingType'),
                $request->input('chargeType'),
                $request->input('name'),
                $request->input('description'),
                $request->input('endDate'),
                $request->input('value'),
                $request->input('dueDateLimitDays'),
                $request->input('externalReference'),
                $request->input('notificationEnabled'),
                $request->input('callback'),
                $request->input('isAddressRequired'),
                $request->input('maxInstallmentCount'),
                $request->input('cycle'),
                $request->input('empresa_id')
            );

            list( $paymentLink, $statusCode ) = $this->paymentLinkService->createPaymentLink($paymentLinkDTO);

            return new JsonResponse(
                [
                    'data' => $paymentLink,
                    'status_code' => $statusCode
                ],
            );
    }



    /**
     * create payment link
     * @param Request $request
     * @return JsonResponse
     */
    public function listaLinksPagamento(Request $request)
    {

         $linksPagamento = $this->paymentLinkService->listaLinksPagamento($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Links de pagamento listados com sucesso.',
            'data' => $linksPagamento,
        ], 200);

    }

}
