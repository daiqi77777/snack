<?php

namespace App\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AdminUser extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'username' => $this->username,
            'status' => $this->status ? true : false,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at
        ];
    }
}