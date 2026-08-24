<?php

declare(strict_types=1);

namespace App\Course\Test\Unit\Service;

use App\Course\Service\AnswerChecker;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class AnswerCheckerTest extends TestCase
{
    public function testSingleChoiceIsCorrect(): void
    {
        $checker = new AnswerChecker();

        $result = $checker->check($this->singleChoiceAnswers(), ['bbc14085f1e34ca93ccbbbd5ee9b5a01']);
        self::assertTrue($result);
    }

    public function testSingleChoiceIsNotCorrect(): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->singleChoiceAnswers(), ['5a81b5f1089cee2b44809bfda245da59']);
        self::assertFalse($result);
    }

    public function testSingleChoiceOneMoreAnswers(): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->singleChoiceAnswers(), ['bbc14085f1e34ca93ccbbbd5ee9b5a01', '5a81b5f1089cee2b44809bfda245da59']);
        self::assertFalse($result);
    }

    public function testSingleChoiceEmptyAnswers(): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->singleChoiceAnswers(), []);

        self::assertFalse($result);
    }

    public function testMultipleChoiceIsCorrect(): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->multipleChoiceAnswers(), ['bbc14085f1e34ca93ccbbbd5ee9b5a01', '5a81b5f1089cee2b44809bfda245da59']);

        self::assertTrue($result);
    }

    public function testMultipleChoiceIsNotCorrect(): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->multipleChoiceAnswers(), ['bbc14085f1e34ca93ccbbbd5ee9b5a01']);

        self::assertFalse($result);
    }

    public function testMultipleChoiceNoAnswers(): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->multipleChoiceAnswers(), []);
        self::assertFalse($result);
    }

    public function testUnknownFormReturnsFalse(): void
    {
        $checker = new AnswerChecker();
        $unknownForm = [
            'form' => 'some_weird_form',
            'answers' => [],
        ];

        $result = $checker->check($unknownForm, ['any_id']);

        self::assertFalse($result);
    }

    public function testSequenceChoiceIsCorrect(): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->sequenceChoiceAnswers(), [
            'bbc14085f1e34ca93ccbbbd5ee9b5a01', '5a81b5f1089cee2b44809bfda245da59', 'a320df35029816f426dde35848e588bb',
        ]);

        self::assertTrue($result);
    }

    #[DataProvider('provideSequenceChoiceIsNotCorrectCases')]
    public function testSequenceChoiceIsNotCorrect(array $data): void
    {
        $checker = new AnswerChecker();
        $result = $checker->check($this->sequenceChoiceAnswers(), $data);

        self::assertFalse($result);
    }

    public static function provideSequenceChoiceIsNotCorrectCases(): iterable
    {
        return [
            [
                ['bbc14085f1e34ca93ccbbbd5ee9b5a01', 'a320df35029816f426dde35848e588bb', '5a81b5f1089cee2b44809bfda245da59'],
            ],
            [
                ['a320df35029816f426dde35848e588bb', 'bbc14085f1e34ca93ccbbbd5ee9b5a01', '5a81b5f1089cee2b44809bfda245da59'],
            ],
            [
                ['5a81b5f1089cee2b44809bfda245da59', 'bbc14085f1e34ca93ccbbbd5ee9b5a01', 'a320df35029816f426dde35848e588bb'],
            ],
        ];
    }

    private function singleChoiceAnswers(): array
    {
        return [
            'form' => 'single_choice',
            'answers' => [
                [
                    'id' => 'bbc14085f1e34ca93ccbbbd5ee9b5a01',
                    'is_correct' => true,
                ],
                [
                    'id' => '5a81b5f1089cee2b44809bfda245da59',
                    'is_correct' => false,
                ],
                [
                    'id' => 'a320df35029816f426dde35848e588bb',
                    'is_correct' => false,
                ],
            ],
        ];
    }

    private function multipleChoiceAnswers(): array
    {
        return [
            'form' => 'multiple_choice',
            'answers' => [
                [
                    'id' => 'bbc14085f1e34ca93ccbbbd5ee9b5a01',
                    'is_correct' => true,
                ],
                [
                    'id' => '5a81b5f1089cee2b44809bfda245da59',
                    'is_correct' => true,
                ],
                [
                    'id' => 'a320df35029816f426dde35848e588bb',
                    'is_correct' => false,
                ],
            ],
        ];
    }

    private function sequenceChoiceAnswers(): array
    {
        return [
            'form' => 'sequence',
            'answers' => [
                [
                    'id' => 'bbc14085f1e34ca93ccbbbd5ee9b5a01',
                    'is_correct' => true,
                ],
                [
                    'id' => '5a81b5f1089cee2b44809bfda245da59',
                    'is_correct' => true,
                ],
                [
                    'id' => 'a320df35029816f426dde35848e588bb',
                    'is_correct' => true,
                ],
            ],
        ];
    }
}
