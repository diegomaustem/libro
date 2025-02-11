<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Http\Resources\RegistrationResource;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationControlle extends Controller
{
    public function index()
    {
        try {
            $registration = Registration::all();
            return RegistrationResource::collection($registration);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failde. Try later!',
            ], 500);
        }
    }

    public function store(StoreRegistrationRequest $request)
    {
        try {
            $registration = Registration::create($request->validated());

            return response()->json([
                'message' => 'Registration completed.',
                'registration' => new RegistrationResource($registration) 
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, registration cannot be inserted. Try later!',
            ], 500);
        }
    }

    public function show(Registration $registration)
    {
        try {
            return new RegistrationResource($registration);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
            ], 500);
        }
    }

    public function update(StoreRegistrationRequest $request, Registration $registration)
    {
        try {
            $registration->update($request->validated());

            return response()->json([
                'message' => "Updated registration.",
                'curso' => new RegistrationResource($registration)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, query failed. Try later!',
            ], 500);
        }
    }
    
    public function destroy(Registration $registration)
    {
        try {
            $registration->delete();
            return response()->json([
                'message' => "Excluded registration.", 
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ops, The registration could not be deleted. Try later!',
            ], 500);
        }
    }
}