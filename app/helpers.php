<?php

if (! function_exists('rupiah')) {
    function rupiah(float|int|string $value, bool $compact = false): string
    {
        $number = (float) $value;
        if ($compact) {
            $abs = abs($number);
            if ($abs >= 1_000_000_000) return 'Rp '.rtrim(rtrim(number_format($number / 1_000_000_000, 1, ',', '.'), '0'), ',').'B';
            if ($abs >= 1_000_000) return 'Rp '.rtrim(rtrim(number_format($number / 1_000_000, 1, ',', '.'), '0'), ',').'M';
            if ($abs >= 1_000) return 'Rp '.rtrim(rtrim(number_format($number / 1_000, 1, ',', '.'), '0'), ',').'K';
        }
        return 'Rp '.number_format($number, 0, ',', '.');
    }
}
