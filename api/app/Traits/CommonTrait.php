<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait CommonTrait
{
    /*
     * method for image upload
     * */
    protected function commonFields(Request $request): array
    {
        return [
            'ip' => $request->ip(),
            'browser' => $request->userAgent(),
            'created_by' => auth()->id() ?? null,
            'updated_by' => auth()->id() ?? null,
        ];
    }

    protected function storeMetadata(Request $request): array
    {
        return [
            'ip' => $request->ip(),
            'browser' => $request->userAgent(),
            'created_by' => auth()->id() ?? null,
        ];
    }

    protected function updateMetadata(Request $request): array
    {
        return [
            'updated_by' => auth()->id() ?? null,
        ];
    }

}
