<?php
function hitungPredikat(float $nilai): string
{
    if ($nilai >= 90) {
        return 'A';
    }

    if ($nilai >= 80) {
        return 'B';
    }

    if ($nilai >= 70) {
        return 'C';
    }

    return 'D';
}
