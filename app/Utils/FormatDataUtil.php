<?php

namespace App\Utils;

class FormatDataUtil {
    public function formatArray($array, $supplier) {
        $formattedArray = [];

        foreach($array as $item) {
            $formattedArray[] = $this->format($item, $supplier);
        }

        // removing null values
        $formattedArray = array_filter($formattedArray, fn ($value) => !is_null($value));

        return $formattedArray;
    }

    public function format($item, $supplier)
    {
        $item = $this->preventFromInvalidData($item, $supplier);

        if (!isset($item))
            return $item;

        $keysToChange = [
            'nome' => 'name',
            'descricao' => 'description',
            'preco' => 'price',
            'categoria' => 'category',
            'departamento' => 'department',
            'material' => [ 'details', 'material' ],
            'imagem' => 'gallery'
        ];

        $formattedItem = [];

        foreach($item as $key => $value) {
            // brazilian_provider has both name and nome in some records, removing name to not overflow;
            if (in_array($key, array_keys($keysToChange))) {
                if (is_array($keysToChange[$key])) {
                   [$firstKeyName, $secondKeyName] = $keysToChange[$key];
                   $formattedItem[$firstKeyName][$secondKeyName] = $value;
                } else {
                    if ($key === 'imagem') $formattedItem[$keysToChange[$key]] = [$value];
                    else $formattedItem[$keysToChange[$key]] = $value;
                }
            } else if (!isset($formattedItem[$key]))
                $formattedItem[$key] = $value;
        }

       return $formattedItem;
    }

      // check if the current value is a valid value, the API from brazilian_provider is dupplicating the list of products inside main product list
      private function preventFromInvalidData($item, $supplier)
      {
          if (!isset($item[0])) return [ ...$item, 'supplier' => $supplier ];
      }
}
