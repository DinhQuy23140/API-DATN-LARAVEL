<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'progress_log_id' => $this->progress_log_id,
            'file_name' => $this->file_name,
            'file_url' => $this->file_url,
            'file_type' => $this->file_type,
            'upload_time' => $this->upload_time,
            'uploader_id' => $this->uploader_id,
        ];
    }
}
