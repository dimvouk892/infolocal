<?php

namespace App\Services;

class ProfanityFilter
{
    /**
     * Basic moderation list for rejecting abusive review text.
     * Keep patterns simple and case-insensitive to avoid overcomplicating moderation.
     */
    private array $blockedTerms = [
        'μαλακα',
        'μαλακασ',
        'μαλακια',
        'γαμω',
        'γαμησου',
        'πουτανα',
        'πουστησ',
        'σκατα',
        'βλακα',
        'malaka',
        'malakas',
        'malakias',
        'gamisou',
        'gamo',
        'poutana',
        'poustis',
        'skata',
        'vlakas',
        'mpastard',
        'bastard',
        'idiot',
        'moron',
        'stupid',
        'fuck',
        'fucking',
        'shit',
        'bitch',
        'asshole',
        'motherfucker',
        'cunt',
        'dick',
        'retard',
    ];

    public function containsProfanity(?string $text): bool
    {
        $text = $this->normalize($text);

        if ($text === '') {
            return false;
        }

        foreach ($this->blockedTerms as $term) {
            if (preg_match('/(^|[^a-z])' . preg_quote($term, '/') . '([^a-z]|$)/', $text)) {
                return true;
            }
        }

        return false;
    }

    private function normalize(?string $text): string
    {
        if (! is_string($text)) {
            return '';
        }

        $text = mb_strtolower($text, 'UTF-8');
        $text = strtr($text, [
            'ά' => 'α', 'έ' => 'ε', 'ή' => 'η', 'ί' => 'ι', 'ϊ' => 'ι', 'ΐ' => 'ι',
            'ό' => 'ο', 'ύ' => 'υ', 'ϋ' => 'υ', 'ΰ' => 'υ', 'ώ' => 'ω',
            'ς' => 'σ',
        ]);
        $text = preg_replace('/[^[:alnum:]\s]/u', ' ', $text) ?? $text;
        $text = preg_replace('/\s+/', ' ', $text) ?? $text;

        return trim($text);
    }
}
