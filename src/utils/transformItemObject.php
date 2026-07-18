<?php
    function transformItem (array $autor): array {
        return [
            'id'   => $autor ['ITEMID'],
            'name' => $autor ['ITEMNAME'],
            'description' => $autor ['ITEMDESCRIPTION']
        ];
    }
?>