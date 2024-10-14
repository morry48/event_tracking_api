<?php

namespace App\Features\Shipment\Presentation\Controllers;

use App\Features\Shipment\Exception\SaveShipmentException;
use App\Features\Shipment\Presentation\Requests\CreateShipmentRequest;
use App\Features\Shipment\Usecases\CreateShipmentUsecase;
use App\Features\Shipment\Usecases\GetShipmentDetailsUsecase;
use App\Features\Shipment\Usecases\GetShipmentsUsecase;
use App\Features\Shipment\Usecases\UpdateShipmentEventsUsecase;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShipmentController extends Controller
{
    private GetShipmentsUsecase $getShipmentsUsecase;
    private CreateShipmentUsecase $createShipmentUsecase;
    protected GetShipmentDetailsUsecase $getShipmentDetailsUsecase;
    protected UpdateShipmentEventsUsecase $updateShipmentEventsUsecase;

    public function __construct(
        GetShipmentsUsecase $getShipmentsUsecase,
        CreateShipmentUsecase $createShipmentUsecase,
        GetShipmentDetailsUsecase $getShipmentDetailsUsecase,
        UpdateShipmentEventsUsecase $updateShipmentEventsUsecase
    ) {
        $this->getShipmentsUsecase = $getShipmentsUsecase;
        $this->createShipmentUsecase = $createShipmentUsecase;
        $this->getShipmentDetailsUsecase = $getShipmentDetailsUsecase;
        $this->updateShipmentEventsUsecase = $updateShipmentEventsUsecase;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $shipment = $this->getShipmentsUsecase->execute();

        return response()->json($shipment);
    }

    /**
     * @param CreateShipmentRequest $request
     * @return JsonResponse
     */
    public function store(CreateShipmentRequest $request): JsonResponse
    {
        try {
            $inputData = $request->validated();

            $shipment = $this->createShipmentUsecase->execute($inputData);

            return response()->json($shipment, 201);
        } catch (SaveShipmentException|Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $shipmentDetails = $this->getShipmentDetailsUsecase->execute($id);
            return response()->json($shipmentDetails);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $inputData = $request->all();
        $inputData['id'] = $id;

        try {
            $shipment = $this->updateShipmentEventsUsecase->execute($inputData);
            return response()->json($shipment);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
