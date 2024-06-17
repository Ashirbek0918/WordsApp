<?php
namespace App\Http\Resources\Words;
use App\Http\Resources\Words\UnverifiedWordsResource;
use Illuminate\Http\Request;

class WordsResource extends UnverifiedWordsResource {
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => $this->type,
        ];
    }
}