<?php

namespace App\Core;

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            foreach ($rule as $validation => $param) {
                if (!$this->$validation($value, $param)) {
                    $this->errors[$field][] = $this->getErrorMessage($field, $validation, $param);
                }
            }
        }
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function required($value)
    {
        return !empty($value);
    }

    private function min($value, $param)
    {
        return strlen($value) >= $param;
    }

    private function max($value, $param)
    {
        return strlen($value) <= $param;
    }

    private function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private function getErrorMessage($field, $validation, $param)
    {
        $messages = [
            'required' => "Le champ $field est obligatoire.",
            'min' => "Le champ $field doit contenir au moins $param caractères.",
            'max' => "Le champ $field ne doit pas dépasser $param caractères.",
            'email' => "Le champ $field doit être une adresse email valide."
        ];
        return $messages[$validation] ?? "Validation échouée pour $field.";
    }

    public function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    // Valider l'image téléchargée
    public function validateImage($image)
    {
        $errors = [];

        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            // Vérifier le type de fichier
            if (!in_array($image['type'], $allowedTypes)) {
                $errors[] = 'Le type de fichier est invalide. Seules les images JPEG, PNG, et GIF sont autorisées.';
            }

            // Vérifier la taille du fichier
            if ($image['size'] > $maxSize) {
                $errors[] = 'La taille de l\'image dépasse la limite autorisée de 2MB.';
            }
        } elseif ($image && $image['error'] !== UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Erreur lors du téléchargement de l\'image.';
        }

        return ['status' => empty($errors), 'errors' => $errors];
    }
}
