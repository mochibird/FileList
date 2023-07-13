<?php
// ①カードキーと値の連想配列を作成する
// [
// '2' => 2,
// '3' => 3,
// '4' => 4,
// '5' => 5,
// '6' => 6,
// '7' => 7,
// '8' => 8,
// '9' => 9,
// '10' => 10,
// 'J' => 11,
// 'Q' => 12,
// 'K' => 13,
// 'A' => 14
// ];
// ②①の連想配列を使って2プレイヤーの3枚のカードの値を連想配列に置き換える
// ③array_chunkを使って連想配列を2つの配列に分ける
// ④array_mapの判定する関数に③の各配列を投げる
// ⑤判定して役・役のつよさ・一番強い数字・二番目に強い数字・三番目に強い数字を連想配列で返す(Q-K-A が最強、A-2-3 が最弱に注意)
// ⑥⑤の連想配列を受け取って値から勝者を判定する
// ⑦showDownからreturnで返す
const HIGH_CARD = 'high card';
const PAIR = 'pair';
const STRAIGHT = 'straight';
const THREE_CARD = 'three card';
const INCREMENT_NUMBER = 1;
const SPLIT_CARD = 3;
const THREE_EQUAL = 1;
const HAND_LANK = [
    HIGH_CARD => 1,
    PAIR => 2,
    STRAIGHT => 3,
    THREE_CARD => 4,
];
const FIRST_PLAYER = 1;
const SECOND_PLAYER = 2;
const DRAW = 0;
const CARD = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
define('CARD_RANK', (function () {
    $cardRanks = [];
    foreach (CARD as $index => $card) {
        $cardRanks[$card] = $index;
    }
    return $cardRanks;
})());

function show(string $card11, string $card12, string $card13, string $card21, string $card22, string $card23): array
{
    $cardRank = convertToCardRank([$card11, $card12, $card13, $card21, $card22, $card23]);
    $playerCardRanks = array_chunk($cardRank, SPLIT_CARD);
    $hands = array_map(fn ($playerCardRank) => checkHand($playerCardRank[0], $playerCardRank[1], $playerCardRank[2]), $playerCardRanks);
    $winner = decideWinner($hands[0], $hands[1]);

    return [$hands[0]['name'], $hands[1]['name'], $winner];
}


function convertToCardRank(array $cards): array
{
    return array_map(fn ($card) => CARD_RANK[substr($card, 1, strlen($card) - 1)], $cards);
}


function checkHand(int $cardRank1, int $cardRank2, int $cardRank3): array
{
    $cardNumbers = [$cardRank1, $cardRank2, $cardRank3];
    rsort($cardNumbers);
    $primary =  $cardNumbers[0];
    $secondary = $cardNumbers[1];
    $third = $cardNumbers[2];
    $name = HIGH_CARD;
    if (isThreeCard($cardNumbers)){
        $name = THREE_CARD;
    }
    if (isStraight($cardNumbers)){
        $name = STRAIGHT;
        if(isMinMax($cardNumbers)){
            $primary = $secondary;
            $secondary = $third;
            $third = $primary;
        }
    }
    if (isPair($cardNumbers)){
        $name = PAIR;
    }

    return [
        'name' => $name,
        'rank' => HAND_LANK[$name],
        'primary' => $primary,
        'secondary' => $secondary,
        'third' => $third,
    ];

}

function isThreeCard(array $cardNumbers){
    return count(array_unique($cardNumbers)) === THREE_EQUAL;
}

function isStraight(array $cardNumbers): bool
{
    return abs($cardNumbers[0] - $cardNumbers[1]) === 1 && abs($cardNumbers[0] - $cardNumbers[2]) === 2 || isMinMax($cardNumbers);
}

#14 2　3だった時の処理
function isMinMax(array $cardNumbers): bool
{
    return $cardNumbers[0] - $cardNumbers[1] ===  max(CARD_RANK) - min(CARD_RANK) - 1;
}

function isPair(array $cardNumbers): bool
{
    if(isThreeCard($cardNumbers)){
        return false;
    }
    return count(array_unique($cardNumbers)) !== count($cardNumbers);
}

function decideWinner(array $hand1, array $hand2): int
{
    foreach(['rank', 'primary', 'secondary', 'third'] as $k){
        if ($hand1[$k] > $hand2[$k]){
            return FIRST_PLAYER;
        }
        if ($hand1[$k] < $hand2[$k]) {
            return SECOND_PLAYER;
        }
    }
    return DRAW;
}
