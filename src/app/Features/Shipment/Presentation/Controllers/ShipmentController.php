<?php

namespace App\Features\Shipment\Presentation\Controllers;

use App\Features\Shipment\Exception\SaveShipmentException;
use App\Features\Shipment\Presentation\Requests\CreateShipmentRequest;
use App\Features\Shipment\ShipmentPolicy;
use App\Features\Shipment\Usecases\CreateShipmentUsecase;
use App\Features\Shipment\Usecases\GetShipmentDetailsUsecase;
use App\Features\Shipment\Usecases\GetShipmentsUsecase;
use App\Features\Shipment\Usecases\UpdateShipmentEventsUsecase;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShipmentController extends Controller
{
    use AuthorizesRequests;

    private GetShipmentsUsecase $getShipmentsUsecase;
    private CreateShipmentUsecase $createShipmentUsecase;
    protected GetShipmentDetailsUsecase $getShipmentDetailsUsecase;
    protected UpdateShipmentEventsUsecase $updateShipmentEventsUsecase;
    private ShipmentPolicy $shipmentPolicy;

    public function __construct(
        GetShipmentsUsecase $getShipmentsUsecase,
        CreateShipmentUsecase $createShipmentUsecase,
        GetShipmentDetailsUsecase $getShipmentDetailsUsecase,
        UpdateShipmentEventsUsecase $updateShipmentEventsUsecase,
        ShipmentPolicy $shipmentPolicy
    ) {
        $this->getShipmentsUsecase = $getShipmentsUsecase;
        $this->createShipmentUsecase = $createShipmentUsecase;
        $this->getShipmentDetailsUsecase = $getShipmentDetailsUsecase;
        $this->updateShipmentEventsUsecase = $updateShipmentEventsUsecase;
        $this->shipmentPolicy = $shipmentPolicy;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $getType = $this->shipmentPolicy->makePermissionTypeByUser($user);
        $shipments = $this->getShipmentsUsecase->execute($user->id, $getType);

        return response()->json($shipments);
    }

    /**
     * @param CreateShipmentRequest $request
     * @return JsonResponse
     */
    public function store(CreateShipmentRequest $request): JsonResponse
    {
        try {
            $inputData = $request->validated();
            $user = auth()->user();
            $inputData['user_id'] = $user->id;
            $shipment = $this->createShipmentUsecase->execute($inputData);

            return response()->json($shipment, 201);
        } catch (SaveShipmentException|Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = auth()->user();
            $getType = $this->shipmentPolicy->makePermissionTypeByUser($user);
            $shipment = $this->getShipmentDetailsUsecase->execute($id, $getType);

            if (!$this->shipmentPolicy->hasAccessToShipment($user, $shipment['user_id'])) {
                return response()->json(['error' => 'You are not allowed to view this shipment.'], 403);
            }

            return response()->json($shipment);
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

        $user = auth()->user();
        if (!$this->shipmentPolicy->CanUpdateToShipment($user, $inputData)) {
            return response()->json(['error' => 'You are not allowed to update this shipment.'], 403);
        }

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
