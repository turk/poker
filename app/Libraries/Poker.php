<?php
namespace App\Libraries;

class Poker
{
    public const RULE_SAME_TYPE = 'sameType';
    public const RULE_CONSECUTIVE = 'consecutive';
    public const RULE_SAME_VALUE = 'sameValue';
    public const PLAYER_1 = 'player1';
    public const PLAYER_2 = 'player2';

    public const ROYAL_FLUSH = [
        'name' => 'Royal Flush',
        'rules' => [self::RULE_SAME_TYPE],
        'value' => [14, 13, 12, 11, 10],
        'priority' => 1
    ];
    public const STRAIGHT_FLUSH = [
        'name' => 'Straight Flush',
        'rules' => [self::RULE_SAME_TYPE, self::RULE_CONSECUTIVE],
        'value' => ['X'],
        'priority' => 2
    ];
    public const FOUR_OF_A_KIND = [
        'name' => 'Four Of A Kind',
        'rules' => [self::RULE_SAME_VALUE],
        'value' => ['X', 'X', 'X', 'X', 'Y'],
        'priority' => 3
    ];
    public const FULL_HOUSE = [
        'name' => 'Full House',
        'rules' => [self::RULE_SAME_VALUE],
        'value' => ['X', 'X', 'X', 'Y', 'Y'],
        'priority' => 4
    ];
    public const FLUSH = [
        'name' => 'Flush',
        'rules' => [self::RULE_SAME_TYPE],
        'value' => ['X', 'X', 'X', 'X', 'X'],
        'priority' => 5
    ];
    public const STRAIGHT = [
        'name' => 'Straight',
        'rules' => [self::RULE_CONSECUTIVE],
        'value' => ['X'],
        'priority' => 6
    ];
    public const THREE_OF_A_KIND = [
        'name' => 'Three Of A Kind',
        'rules' => [self::RULE_SAME_VALUE],
        'value' => ['X', 'X', 'X'],
        'priority' => 7
    ];
    public const TWO_PAIRS = [
        'name' => 'Two Pairs',
        'rules' => [self::RULE_SAME_VALUE, self::RULE_SAME_TYPE],
        'value' => ['X', 'X', 'Y', 'Y'],
        'priority' => 8
    ];
    public const ONE_PAIR = [
        'name' => 'One Pair',
        'rules' => [self::RULE_SAME_VALUE],
        'value' => ['X', 'X'],
        'priority' => 9
    ];
    public const HIGH_CARD = [
        'name' => 'High Card',
        'rules' => [self::RULE_SAME_VALUE],
        'value' => ['V', 'W', 'X', 'Y', 'Z'],
        'priority' => 10,
    ];

    public $specialCars = [
        'original_data' => ['T', 'J', 'Q', 'K', 'A'],
        'fake_data' => [10, 11, 12, 13, 14]
    ];
    public $line;
    public $player1stCards;
    public $player2ndCards;
    public $tmpArray = [];

    /**
     * Set hand for players.
     *
     * @return void
     */
    public function setHandForPlayers(): void
    {
        $cards = explode(' ', $this->line);
        foreach ($cards as $key => $card) {
            if ($key < 5) {
                $this->player1stCards[] = $card;
            } else {
                $this->player2ndCards[] = $card;
            }
        }
    }

    /**
     * Parser user cards.
     *
     * @param $cards
     *
     * @return array
     */
    public function parseUserCards($cards): array
    {
        $tmpCards = [];
        foreach ($cards as $card) {
            $card = str_split($card);
            $tmpCards['value'][] = $card[0];
            $tmpCards['type'][] = $card[1];
        }
        $tmpCards['value'] = str_replace($this->specialCars['original_data'], $this->specialCars['fake_data'], $tmpCards['value']);
        rsort($tmpCards['value']);
        return $tmpCards;
    }

    /**
     * Find hand's rank.
     *
     * @param $hand
     *
     * @return array|null
     */
    public function findRank($hand): ?array
    {
        if ($this->isRoyalFlash($hand)) {
            return self::ROYAL_FLUSH;
        } elseif ($this->isStraightFlush($hand)) {
            return self::STRAIGHT_FLUSH;
        } elseif ($this->isFourOfAKind($hand)) {
            return self::FOUR_OF_A_KIND;
        } elseif ($this->isFullHouse($hand)) {
            return self::FULL_HOUSE;
        } elseif ($this->isFlush($hand)) {
            return self::FLUSH;
        } elseif ($this->isStraight($hand)) {
            return self::STRAIGHT;
        } elseif ($this->isThreeOfAKind($hand)) {
            return self::THREE_OF_A_KIND;
        } elseif ($this->isTwoPairs($hand)) {
            return self::TWO_PAIRS;
        } elseif ($this->isOnePair($hand)) {
            return self::ONE_PAIR;
        } else {
            return self::HIGH_CARD;
        }
    }

    /**
     * Check if the hand is royal flush.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isRoyalFlash($hand): bool
    {
        if ($this->areCardsTypeSame($hand) === false) {
            return false;
        }
        foreach ($hand['value'] as $key => $value) {
            if (self::ROYAL_FLUSH['value'][$key] != $value) {
                return false;
            };
        }
        return true;
    }

    /**
     * Check if cards type are same.

     * @param $hand
     *
     * @return bool
     */
    public function areCardsTypeSame($hand): bool
    {
        return count(array_count_values($hand['type'])) > 1 ? false : true;
    }

    /**
     * Check if the hand is straight flush.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isStraightFlush($hand): bool
    {
        if ($this->areCardsTypeSame($hand) === false) {
            return false;
        }
        $values = $hand['value'];
        for ($i = 0; $i < 5; $i++) {
            if ($i === count($values) - 1) {
                break;
            }
            if ($values[$i] != $values[$i + 1] + 1) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the hand is four of a kind.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isFourOfAKind($hand): bool
    {
        $values = $hand['value'];
        $arrayCount = array_count_values($values);
        $rankRuleValueCount = array_count_values(self::FOUR_OF_A_KIND['value']);
        rsort($arrayCount);
        rsort($rankRuleValueCount);
        if ($arrayCount === $rankRuleValueCount) {
            return true;
        }
        return false;
    }

    /**
     * Check if the hand is flush house.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isFullHouse($hand): bool
    {
        $values = $hand['value'];
        $arrayCount = array_count_values($values);
        $rankRuleValueCount = array_count_values(self::FULL_HOUSE['value']);
        rsort($arrayCount);
        rsort($rankRuleValueCount);
        if ($arrayCount === $rankRuleValueCount) {
            return true;
        }
        return false;
    }

    /**
     * Check if the hand is flush.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isFlush($hand): bool
    {
        if ($this->areCardsTypeSame($hand) === true) {
            return true;
        }
        return false;
    }

    /**
     * Check if the hand is straight.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isStraight($hand): bool
    {
        $values = $hand['value'];
        foreach ($values as $key => $value) {
            if ($key === 0) {
                continue;
            }
            if ($value != $values[0] - $key) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if the hand is three of a kind.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isThreeOfAKind($hand): bool
    {
        $arrayCountValues = array_count_values($hand['value']);
        $neededCount = count(self::THREE_OF_A_KIND['value']);
        if (array_search($neededCount, $arrayCountValues, true)) {
            return true;
        }
        return false;
    }

    /**
     * Check if the hand is two pairs.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isTwoPairs($hand): bool
    {
        $arrayCountValues = array_count_values($hand['value']);
        $arrayCountValues = array_filter($arrayCountValues, static function ($item) {
            return $item > 1;
        });
        $rankRuleCountValues = array_count_values(self::TWO_PAIRS['value']);
        rsort($rankRuleCountValues);
        rsort($arrayCountValues);
        if ($arrayCountValues === $rankRuleCountValues) {
            return true;
        }
        return false;
    }

    /**
     * Check if the hand is one pair.
     *
     * @param $hand
     *
     * @return bool
     */
    public function isOnePair($hand): bool
    {
        $arrayCountValues = array_count_values($hand['value']);
        $arrayCountValues = array_filter($arrayCountValues, function ($item) {
            return $item > 1;
        });
        $rankRuleCountValues = array_count_values(self::ONE_PAIR['value']);
        rsort($arrayCountValues);
        rsort($rankRuleCountValues);
        if ($arrayCountValues === $rankRuleCountValues) {
            return true;
        }
        return false;
    }


    /**
     * Find the winner.
     *
     * @param $firstPlayerRank
     * @param $secondPlayerRank
     *
     * @return string|null
     */
    public function findWinner($firstPlayerRank, $secondPlayerRank): ?string
    {
        if ($firstPlayerRank['priority'] === $secondPlayerRank['priority']) {
            $this->tmpArray[$firstPlayerRank['name']] = [
                $this->player1stCards,
                $this->player2ndCards,
                $firstPlayerRank['name']
            ];
            $type = $firstPlayerRank['name'];
            switch ($type) {
                case self::ONE_PAIR['name']:
                    return $this->findWinnerForOnePair();
                    break;
                case self::HIGH_CARD['name']:
                    return $this->findWinnerForHighCard();
                case self::STRAIGHT_FLUSH['name'];
                    return $this->findWinnerForStraightFlush();
            }
        }
        if ($firstPlayerRank['priority'] < $secondPlayerRank['priority']) {
            return self::PLAYER_1;
        } else {
            return self::PLAYER_2;
        }
    }

    /**
     * In case of equality, find the winner for one pair.      
     * 
     * @return string|null
     */
    public function findWinnerForOnePair(): ?string
    {
        $player1stCardsCountValues = $this->arrayFilter(array_count_values($this->player1stCards['value']), 1);
        $player2ndCardsCountValues = $this->arrayFilter(array_count_values($this->player2ndCards['value']), 1);
        $player1stPairValue = array_keys($player1stCardsCountValues);
        $player2ndPairValue = array_keys($player2ndCardsCountValues);
        if ($player1stPairValue[0] > $player2ndPairValue[0]) {
            return self::PLAYER_1;
        } elseif ($player1stPairValue[0] < $player2ndPairValue[0]) {
            return self::PLAYER_2;
        } else {
            $player1stOtherCardsValues = array_keys($this->arrayFilter(array_count_values($this->player1stCards['value']), 2, '<'));
            $player2ndOtherCardsValues = array_keys($this->arrayFilter(array_count_values($this->player2ndCards['value']), 2, '<'));
            for ($i = 0; $i < 3; $i++) {
                if ($player1stOtherCardsValues[$i] === $player2ndOtherCardsValues[$i]) {
                    continue;
                }
                if ($player1stOtherCardsValues[$i] > $player2ndOtherCardsValues[$i]) {
                    return self::PLAYER_1;
                } else {
                    return self::PLAYER_2;
                }
            }
        }
    }


    /**
     * Custom array filter function.
     * 
     * @param $array
     * @param $delimiter
     * @param string $operator
     * 
     * @return array
     */
    public function arrayFilter($array, $delimiter, $operator = '>'): array
    {
        return array_filter($array, static function ($item) use ($delimiter, $operator) {
            if ($operator === '>') {
                return $item > $delimiter;
            }
            return $item < $delimiter;
        });
    }

    /**
     * In case of equality, find the winner for high card.
     * 
     * @return string|null
     */
    public function findWinnerForHighCard(): ?string
    {
        $player1stCardValues = $this->player1stCards['value'];
        $player2ndCardValues = $this->player2ndCards['value'];
        for ($i = 0; $i < 5; $i++) {
            if ($player1stCardValues[$i] === $player2ndCardValues[$i]) {
                continue;
            }
            if ($player1stCardValues[$i] > $player2ndCardValues[$i]) {
                return self::PLAYER_1;
            } else {
                return self::PLAYER_2;
            }
        }
    }

    /**
     * In case of equality, find the winner for straight flush.
     * 
     * @return string|null
     */
    public function findWinnerForStraightFlush(): ?string
    {
        $player1stCardValues = $this->player1stCards['value'];
        $player2ndCardValues = $this->player2ndCards['value'];
        for ($i = 0; $i < 5; $i++) {
            if ($player1stCardValues[$i] === $player2ndCardValues[$i]) {
                continue;
            }
            if ($player1stCardValues[$i] > $player2ndCardValues[$i]) {
                return self::PLAYER_1;
            } else {
                return self::PLAYER_2;
            }
        }
    }
}
