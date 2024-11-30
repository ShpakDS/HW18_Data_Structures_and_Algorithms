<?php

function countingSort(array $arr): array
{
    $maxVal = max($arr);
    $countArr = array_fill(0, $maxVal + 1, 0);

    foreach ($arr as $val) {
        $countArr[$val]++;
    }

    $sortedArr = [];
    foreach ($countArr as $key => $count) {
        for ($i = 0; $i < $count; $i++) {
            $sortedArr[] = $key;
        }
    }

    return $sortedArr;
}