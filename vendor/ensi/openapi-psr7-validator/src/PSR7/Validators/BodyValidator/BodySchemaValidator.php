<?php

declare(strict_types=1);

namespace League\OpenAPIValidation\PSR7\Validators\BodyValidator;

use cebe\openapi\spec\Schema;
use League\OpenAPIValidation\Foundation\ArrayHelper;
use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidBody;
use League\OpenAPIValidation\PSR7\MessageValidator;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\Validators\ValidationStrategy;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\KeywordMismatch;
use League\OpenAPIValidation\Schema\SchemaValidator;
use Psr\Http\Message\MessageInterface;

class BodySchemaValidator implements MessageValidator
{
    use ValidationStrategy;

    public const SKIP_VALUE = 'x-skip-response-validation';

    /** @var Schema */
    protected $schema;

    /** @var OperationAddress */
    protected $addr;

    /** @var string */
    protected $contentType;

    /** @var AbstractBodyValidator */
    protected $baseValidator;

    public function __construct(
        Schema $schema,
        string $contentType,
        AbstractBodyValidator $baseValidator
    ) {
        $this->schema = $schema;
        $this->contentType = $contentType;
        $this->baseValidator = $baseValidator;
    }

    public function validate(OperationAddress $addr, MessageInterface $message): void
    {
        $this->addr = $addr;

        switch ($this->detectValidationStrategy($message)) {
            case SchemaValidator::VALIDATE_AS_RESPONSE:
                $this->validateResponse($message);
        }
    }

    protected function validateResponse(MessageInterface $message)
    {
        $body = $this->baseValidator->getBody($this->addr, $message);

        if (is_array($body)) {
            $this->check($body, $this->schema);
        }
    }

    protected function getCollapseProperties(Schema $schema): array
    {
        $properties = $schema->properties ?? [];
        foreach ($schema->allOf ?? [] as $subSchema) {
            $properties = array_merge($properties, $this->getCollapseProperties($subSchema));
        }

        foreach ($schema->oneOf ?? [] as $subSchema) {
            $properties = array_merge($properties, $this->getCollapseProperties($subSchema));
        }

        return $properties;
    }

    private function check(array $body, Schema $schema, ?BreadCrumb $breadCrumb = null): void
    {
        $breadCrumb = $breadCrumb ?? new BreadCrumb();

        try {
            $properties = $this->getCollapseProperties($schema);
            foreach ($body as $prop => $value) {
                if (!array_key_exists($prop, $properties)) {
                    $this->throw($prop, $body);
                }

                if (!is_array($value) || empty($value)) {
                    continue;
                }

                $subSchema = $properties[$prop];
                if ($this->isSkip($subSchema)) {
                    continue;
                }

                if (ArrayHelper::isAssoc($value)) {
                    $this->check($value, $subSchema, $breadCrumb->addCrumb($prop));
                } elseif (is_array($value[0]) && $this->isNotSkip($subSchema->items)) {
                    // check only first item
                    $this->check($value[0], $subSchema->items, $breadCrumb->addCrumb($prop));
                }
            }
        } catch (KeywordMismatch $e) {
            $e->hydrateDataBreadCrumb($breadCrumb);

            throw InvalidBody::becauseBodyDoesNotMatchSchema($this->contentType, $this->addr, $e);
        }
    }

    private function isSkip(Schema $schema): bool
    {
        return $schema->{self::SKIP_VALUE} ?? false;
    }

    private function isNotSkip(Schema $schema): bool
    {
        return !$this->isSkip($schema);
    }

    private function throw(string $prop, array $body)
    {
        throw KeywordMismatch::fromKeyword(
            'properties',
            $body,
            "Property ({$prop}) not found in scheme"
        );
    }
}
