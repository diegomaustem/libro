<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'gender'        => $this->gender,
            'data_of_birth' => Carbon::parse($this->data_of_birth)->format('d/m/Y H:i:s'),
            'created_at'    => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at'    => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s')
        ];
    }
}
