<?php

namespace App\Utils;

class FormatDataUtil {
    public function format($item)
    {
        if (!isset($item))
            return $item;

        $keysToChange = [
            'nome' => 'name',
            'descricao' => 'description',
            'preco' => 'price',
            'categoria' => 'category',
            'departamento' => 'department',
            'material' => [ 'details', 'material' ]
        ];

        $formattedItem = [];

        foreach($item as $key => $value) {
            if (in_array($key, array_keys($keysToChange))) {
                if (is_array($keysToChange[$key])) {
                   [$firstKeyName, $secondKeyName] = $keysToChange[$key];
                   $formattedItem[$firstKeyName][$secondKeyName] = $value;
                } else
                    $formattedItem[$keysToChange[$key]] = $value;
            } else
                $formattedItem[$key] = $value;
        }

       return $formattedItem;
    }
}
