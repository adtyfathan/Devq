<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}

// Laravel API Resources format the output of models when returning JSON responses.

// namespace App\Http\Resources;

// use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\JsonResource;

// class QuizResource extends JsonResource
// {
//     public function toArray(Request $request): array
//     {
//         return [
//             'id' => $this->id,
//             'title' => $this->title,
//             'description' => $this->description,
//             'created_at' => $this->created_at->toDateString(),
//         ];
//     }
// }

// PAKE DI CONTROLLER
// return QuizResource::collection(Quiz::all());