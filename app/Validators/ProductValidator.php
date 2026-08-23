<?php
declare(strict_types=1);

namespace App\Validators;

class ProductValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty(trim((string)($data['name'] ?? '')))) {
            $errors[] = 'El nombre del modelo es obligatorio.';
        }

        if (empty(trim((string)($data['sku'] ?? '')))) {
            $errors[] = 'El código SKU es obligatorio.';
        } elseif (!preg_match('/^[A-Za-z0-9\-_]+$/', (string)$data['sku'])) {
            $errors[] = 'El SKU solo puede contener letras, números, guiones y guiones bajos.';
        }

        if (empty($data['brand_id']) || (int)$data['brand_id'] <= 0) {
            $errors[] = 'Debe seleccionar una marca válida.';
        }

        if (empty($data['category_id']) || (int)$data['category_id'] <= 0) {
            $errors[] = 'Debe seleccionar una categoría válida.';
        }

        if (!isset($data['price']) || (float)$data['price'] <= 0) {
            $errors[] = 'El precio debe ser un número mayor a cero.';
        }

        if (isset($data['stock']) && (int)$data['stock'] < 0) {
            $errors[] = 'El stock inicial no puede ser un número negativo.';
        }

        return $errors;
    }
}