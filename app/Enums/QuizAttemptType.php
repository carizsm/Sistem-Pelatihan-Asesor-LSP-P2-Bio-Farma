<?php

namespace App\Enums;

/**
 * Mendefinisikan tipe kuis yang dikerjakan peserta.
 */
enum QuizAttemptType: string
{
    case PRE_TEST = 'pre-test';
    case POST_TEST = 'post-test';
}