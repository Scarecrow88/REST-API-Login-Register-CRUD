<?php
    function transformUser (array $user): array {
        return [
            'id'   => $user ['AUTID'],
            'name' => $user ['AUTNAME'],
            'city' => $user ['AUTCITY'],
            'age'  => (int) $user ['AUTAGE'],
            'user' => $user ['AUTNICKNAME']
        ];
    }
?>