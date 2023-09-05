<?php

if (! function_exists('flattenCategories')) {
    function flattenCategories($categories, $level = 0): array
    {
        $flatCategories = [];

        foreach ($categories as $category) {
            $dash = str_repeat(' — ', $level);
            $flatCategories[$category->id] = $dash . ' ' . $category->name;

            if ($category->children->isNotEmpty()) {
                $level++;
                $flatCategories += flattenCategories($category->children, $level);
                $level--;
            }
        }

        return $flatCategories;
    }
}