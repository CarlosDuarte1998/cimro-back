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
    
        // Crear la carpeta si no existe
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }
    
        // Crear un nombre único para la imagen
        $uniqueName = uniqid() . '_' . time() . '.' . $extension;

        
        // Guardar la imagen en el disk 'public'
        $image->storeAs($folderPath, $uniqueName, options: 'public');
        
    
        return $folderPath . '/' . $uniqueName;
    }
    

    /**
     * Elimina una imagen.
     *
     * @param string $imagePath
     * @return void
     */

    public function delete($imagePath)
    {
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
    /**
     * Actualiza una imagen.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $oldImagePath
     * @param string $folderPath
     * @return string|null
     */

    public function update($image, $oldImagePath, $folderPath)
    {
     
        $this->delete($oldImagePath);

   
        return $this->validateAndSave($image, $folderPath);
    }
}
