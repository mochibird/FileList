<?php

function judge(int $answer, int $guess): array
{
    $hit = 0;
    $blow = 0;
    $arrayGuess = str_split((string) $guess);
    foreach ($arrayGuess as $index => $guessElem) {
        if (isHit($guessElem, $answer, $index)) {
            $hit++;
        }

        if (isBlow($guessElem, $answer, $index)) {
            $blow++;
        }
    }

    return [$hit, $blow];
}

function isHit(string $letter, int $answer, int $index): bool
{
    return str_split((string) $answer)[$index] === $letter;
}

function isBlow(string $letter, int $answer, int $index): bool
{
    if (isHit($letter, $answer, $index)) {
        return false;
    }

    return in_array($letter, str_split((string) $answer), true);
}
