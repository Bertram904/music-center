<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * Formats the database ouput into a clean JSON structure for the frontend.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
           'id' => $this->id,
           'name' => $this->name,
           //Format time from HH:MM:SS to HH:MM
           'start_time' => Carbon::parse($this->start_time)->format('H:i'),
           'end_time' => Carbon::parse($this->end_time)->format('H:i'),
           'status' => $this->is_active ? 'Active' : 'Inactive',
       ];
    }
}
