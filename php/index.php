<?php

require 'tree.php';
require 'sort.php';

function generateRandomData(int $size): array
{
    return array_map(fn() => random_int(0, 100000), range(1, $size));
}

function measureComplexity(callable $algorithm, array $data): int
{
    $startTime = hrtime(true);
    $algorithm($data);
    return hrtime(true) - $startTime;
}

$sizeArray = [10, 50, 100, 200, 500, 1000, 5000, 10000, 50000];
$bstInsertTimeArr = [];
$bstFindTimeArr = [];
$bstDeleteTimeArr = [];
$countingSortTimeArr = [];

foreach ($sizeArray as $size) {
    $data = generateRandomData($size);

    $bst = new BalancedBST();
    $bstInsertTime = measureComplexity(fn() => $bst->insert($data), $data);
    $bstFindTime = measureComplexity(fn() => $bst->find(random_int(0, 100000)), []);
    $bstDeleteTime = measureComplexity(fn() => $bst->delete(random_int(0, 100000)), []);
    $countingSortTime = measureComplexity(fn() => countingSort($data), $data);

    $bstInsertTimeArr[] = $bstInsertTime;
    $bstFindTimeArr[] = $bstFindTime;
    $bstDeleteTimeArr[] = $bstDeleteTime;
    $countingSortTimeArr[] = $countingSortTime;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BST and Counting Sort Performance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Performance Analysis: BST and Counting Sort</h1>
<table border="1" cellpadding="10">
    <thead>
    <tr>
        <th>Elements</th>
        <th>BST Insert (ns)</th>
        <th>BST Find (ns)</th>
        <th>BST Delete (ns)</th>
        <th>Counting Sort (ns)</th>
    </tr>
    </thead>
    <tbody>
    <?php for ($i = 0; $i < count($sizeArray); $i++): ?>
        <tr>
            <td><?= $sizeArray[$i] ?></td>
            <td><?= $bstInsertTimeArr[$i] ?></td>
            <td><?= $bstFindTimeArr[$i] ?></td>
            <td><?= $bstDeleteTimeArr[$i] ?></td>
            <td><?= $countingSortTimeArr[$i] ?></td>
        </tr>
    <?php endfor; ?>
    </tbody>
</table>

<h2>Performance Chart</h2>
<canvas id="performanceChart" width="800" height="400"></canvas>
<script>
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($sizeArray) ?>,
            datasets: [
                {
                    label: 'BST Insert (ns)',
                    data: <?= json_encode($bstInsertTimeArr) ?>,
                    borderColor: 'blue',
                    fill: false
                },
                {
                    label: 'BST Find (ns)',
                    data: <?= json_encode($bstFindTimeArr) ?>,
                    borderColor: 'red',
                    fill: false
                },
                {
                    label: 'BST Delete (ns)',
                    data: <?= json_encode($bstDeleteTimeArr) ?>,
                    borderColor: 'yellow',
                    fill: false
                },
                {
                    label: 'Counting Sort (ns)',
                    data: <?= json_encode($countingSortTimeArr) ?>,
                    borderColor: 'green',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' + context.raw.toLocaleString() + ' ns';
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Number of Elements'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Time (ns)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>