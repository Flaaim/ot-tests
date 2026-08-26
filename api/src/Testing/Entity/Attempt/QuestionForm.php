<?php

declare(strict_types=1);

namespace App\Testing\Entity\Attempt;

enum QuestionForm: string
{
    case SINGLE_CHOICE = 'single_choice';
    case MULTIPLE_CHOICE = 'multiple_choice';
    case SEQUENCE = 'sequence';
    case MATCHING = 'matching';
}
