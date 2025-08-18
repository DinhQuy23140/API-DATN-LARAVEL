<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgressLogResource extends JsonResource
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
            'process_id' => $this->process_id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date_time' => $this->start_date_time,
            'end_date_time' => $this->end_date_time,
            'instructor_comment' => $this->instructor_comment,
            'student_status' => $this->student_status,
            'instructor_status' => $this->instructor_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'attachments' => AttachmentResource::collection($this->attachments),
        ];
    }
}
