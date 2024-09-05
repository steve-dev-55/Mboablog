<?php

namespace App\Models;

class SettingModel extends BaseModel
{
    protected $table = 'settings';

    // Récupérer tous les paramètres
    public function getAllSettings()
    {
        $values = [];
        $sql = "SELECT * FROM {$this->table}";
        $rawData['data'] = $this->query($sql)->fetchAll();
        foreach ($rawData['data'] as $item) {
            if (isset($item['key']) && isset($item['value'])) {
                $values[$item['key']] = $item['value'];
            }
        }
        return $values;
    }

    // Récupérer un paramètre par sa clé
    public function getSettingByKey($key)
    {
        $sql = "SELECT * FROM {$this->table} WHERE `key` = ?";
        return $this->query($sql, [$key])->fetch();
    }

    // Mettre à jour un paramètre
    public function updateSetting($key, $value)
    {
        $keyResult = $this->getSettingByKey($key);
        if (!empty($keyResult)) {
            $sql = "UPDATE {$this->table} SET `value` = ? WHERE `key` = ?";
        } else {
            $sql = "INSERT INTO {$this->table} (`value`,`key`) VALUES (:value, :key)";
        }
        return $this->query($sql, [$value, $key]);
    }
}
