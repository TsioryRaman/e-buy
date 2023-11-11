<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class Unique extends \Symfony\Component\Validator\Constraint
{

    public string $message = 'Cette valeur est déjà utilisée';

    /**
     * @var class-string<object>|null
     */
    public ?string $entityClass = null;

    public string $field = '';

    #[HasNamedArguments]
    public function __construct(
        string $field = '',
        string $message = 'Cette valeur est déjà utilisée',
        string $entityClass = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([
            'field' => $field,
            'message' => $message,
            'entityClass' => $entityClass,
        ], $groups, $payload);
    }

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }

}