<?php

namespace App\Utils;

class FormatDataUtil {
    public function formatArray($array) {
        $formattedArray = [];

        foreach($array as $item) {
            $formattedArray[] = $this->format($item);
        }

        return $formattedArray;
    }

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
            // brazilian_provider has both name and nome in some records, removing name to not overflow;
            if (in_array($key, array_keys($keysToChange))) {
                if (is_array($keysToChange[$key])) {
                   [$firstKeyName, $secondKeyName] = $keysToChange[$key];
                   $formattedItem[$firstKeyName][$secondKeyName] = $value;
                } else
                    $formattedItem[$keysToChange[$key]] = $value;
            } else if (!isset($formattedItem[$key]))
                $formattedItem[$key] = $value;
        }

       return $formattedItem;
    }
}
