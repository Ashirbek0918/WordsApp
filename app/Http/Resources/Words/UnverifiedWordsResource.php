<?php

namespace App\Http\Resources\Words;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnverifiedWordsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'word' => $this->word,
            'user' =>[
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
        ];
    }
}
