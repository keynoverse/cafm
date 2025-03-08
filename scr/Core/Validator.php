<?php

namespace App\Core;

class Validator
{
    private array $data;
    private array $rules;
    private array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                $this->validateField($field, $rule);
            }
        }

        if (!empty($this->errors)) {
            $_SESSION['errors'] = $this->errors;
            $_SESSION['old'] = $this->data;
        }

        return empty($this->errors);
    }

    private function validateField(string $field, string $rule)
    {
        $value = $this->data[$field] ?? null;

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->errors[$field][] = "The $field field is required.";
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "The $field must be a valid email address.";
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->errors[$field][] = "The $field must be a number.";
                }
                break;

            case 'min':
                $min = (int) substr($rule, 4);
                if (!empty($value) && strlen($value) < $min) {
                    $this->errors[$field][] = "The $field must be at least $min characters.";
                }
                break;

            case 'max':
                $max = (int) substr($rule, 4);
                if (!empty($value) && strlen($value) > $max) {
                    $this->errors[$field][] = "The $field must not exceed $max characters.";
                }
                break;

            case 'unique':
                $params = explode(',', substr($rule, 7));
                $table = $params[0];
                $column = $params[1] ?? $field;
                $exceptId = $params[2] ?? null;

                $db = Database::getInstance();
                $sql = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
                $params = [$value];

                if ($exceptId) {
                    $sql .= " AND id != ?";
                    $params[] = $exceptId;
                }

                $result = $db->fetch($sql, $params);
                if ($result['count'] > 0) {
                    $this->errors[$field][] = "The $field has already been taken.";
                }
                break;

            case 'exists':
                $params = explode(',', substr($rule, 6));
                $table = $params[0];
                $column = $params[1] ?? $field;

                $db = Database::getInstance();
                $result = $db->fetch(
                    "SELECT COUNT(*) as count FROM $table WHERE $column = ?",
                    [$value]
                );

                if ($result['count'] === 0) {
                    $this->errors[$field][] = "The selected $field is invalid.";
                }
                break;

            case 'confirmed':
                if (!empty($value) && $value !== ($this->data[$field . '_confirmation'] ?? null)) {
                    $this->errors[$field][] = "The $field confirmation does not match.";
                }
                break;

            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->errors[$field][] = "The $field is not a valid date.";
                }
                break;

            case 'in':
                $allowedValues = explode(',', substr($rule, 3));
                if (!empty($value) && !in_array($value, $allowedValues)) {
                    $this->errors[$field][] = "The selected $field is invalid.";
                }
                break;

            case 'regex':
                $pattern = substr($rule, 6);
                if (!empty($value) && !preg_match($pattern, $value)) {
                    $this->errors[$field][] = "The $field format is invalid.";
                }
                break;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
} 