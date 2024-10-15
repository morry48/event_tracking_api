<?php

namespace App\Features\Shipment\Presentation\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateShipmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'internal_reference_name' => 'required|string|max:255|unique:shipments,internal_reference_name',
            'events' => 'required|array',

            // preparation event
            'events.preparation' => 'required|array',
            'events.preparation.estimated_started_at' => 'nullable|date',
            'events.preparation.estimated_completion_at' => 'nullable|date',
            'events.preparation.actual_started_at' => 'nullable|date',
            'events.preparation.actual_completion_at' => 'nullable|date',

            // port departure event
            'events.port_departure' => 'required|array',
            'events.port_departure.estimated_started_at' => 'nullable|date',
            'events.port_departure.estimated_completion_at' => 'nullable|date',
            'events.port_departure.actual_started_at' => 'nullable|date',
            'events.port_departure.actual__completion_at' => 'nullable|date',


            // port arrival event
            'events.port_arrival' => 'required|array',
            'events.port_arrival.estimated_started_at' => 'nullable|date',
            'events.port_arrival.estimated_completion_at' => 'nullable|date',
            'events.port_arrival.actual_started_at' => 'nullable|date',
            'events.port_arrival.actual_completion_at' => 'nullable|date',

            // delivery event
            'events.delivery' => 'required|array',
            'events.delivery.estimated_started_at' => 'nullable|date',
            'events.delivery.estimated_completion_at' => 'nullable|date',
            'events.delivery.actual_started_at' => 'nullable|date',
            'events.delivery.actual_completion_at' => 'nullable|date',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'message' => 'Invalid request parameters',
            'errors' => $validator->errors()
        ], 400);  // 400 Bad Request を返す

        throw new HttpResponseException($response);
    }
}
