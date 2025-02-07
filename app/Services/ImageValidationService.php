<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageValidationService
{
    protected $allowedExtensions = ['png', 'jpg', 'jpeg', 'svg'];

    /**
     * Valida y procesa la imagen.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $folderPath
     * @return string|null
     */
    public function validateAndSave($image, $folderPath)
    {
        // Validar si la imagen es válida
        $extension = strtolower($image->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \Exception("El formato de la imagen no es válido. Solo se permiten: " . implode(', ', $this->allowedExtensions));
        }

        // Crear la carpeta si no existe (esto solo es necesario para algunos disks)
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Generar un nombre único
        $imageName = Str::uuid() . '.' . $extension;

        // Guardar la imagen en el disk 'public'
        $image->storeAs($folderPath, $imageName, 'public');

        // Retornar la URL correcta
        return Storage::url($folderPath . '/' . $imageName);
    }
}
