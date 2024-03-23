<?php

declare(strict_types=1);

namespace Jose\Tests\Bundle\JoseFramework\Functional\NestedToken;

use Jose\Bundle\JoseFramework\DependencyInjection\Configuration;
use Jose\Bundle\JoseFramework\DependencyInjection\Source\Checker\CheckerSource;
use Jose\Bundle\JoseFramework\DependencyInjection\Source\Core\CoreSource;
use Jose\Bundle\JoseFramework\DependencyInjection\Source\Encryption\EncryptionSource;
use Jose\Bundle\JoseFramework\DependencyInjection\Source\NestedToken\NestedToken;
use Jose\Bundle\JoseFramework\DependencyInjection\Source\Signature\SignatureSource;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NestedTokenBuilderConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    #[Test]
    public function theConfigurationIsValidIfNoConfigurationIsSet(): void
    {
        $this->assertConfigurationIsValid([]);
    }

    #[Test]
    public function theConfigurationIsValidIfConfigurationIsFalse(): void
    {
        $this->assertConfigurationIsValid([
            [
                'nested_token' => false,
            ],
        ]);
    }

    #[Test]
    public function theConfigurationIsValidIfConfigurationIsEmpty(): void
    {
        $this->assertConfigurationIsValid([
            [
                'nested_token' => [],
            ],
        ]);
    }

    #[Test]
    public function theConfigurationIsValidIfNoBuilderIsSet(): void
    {
        $this->assertConfigurationIsValid([
            [
                'nested_token' => [
                    'builders' => [],
                ],
            ],
        ]);
    }

    #[Test]
    public function theConfigurationIsInvalidIfNoSignatureAlgorithmIsSet(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'nested_token' => [
                        'builders' => [
                            'foo' => [],
                        ],
                    ],
                ],
            ],
            'The child config "signature_algorithms" under "jose.nested_token.builders.foo" must be configured:'
        );
    }

    #[Test]
    public function theConfigurationIsInvalidIfNoKeyEncryptionAlgorithmIsSet(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'nested_token' => [
                        'builders' => [
                            'foo' => [
                                'signature_algorithms' => ['RS256'],
                            ],
                        ],
                    ],
                ],
            ],
            'The child config "key_encryption_algorithms" under "jose.nested_token.builders.foo" must be configured:'
        );
    }

    #[Test]
    public function theConfigurationIsInvalidIfNoJwsSerializerIsSet(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'nested_token' => [
                        'builders' => [
                            'foo' => [
                                'signature_algorithms' => ['RS256'],
                                'key_encryption_algorithms' => ['RSA-OAEP'],
                                'content_encryption_algorithms' => ['A128GCM'],
                            ],
                        ],
                    ],
                ],
            ],
            'The child config "jws_serializers" under "jose.nested_token.builders.foo" must be configured:'
        );
    }

    #[Test]
    public function theConfigurationIsInvalidIfNoJweSerializerIsSet(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'nested_token' => [
                        'builders' => [
                            'foo' => [
                                'signature_algorithms' => ['RS256'],
                                'key_encryption_algorithms' => ['RSA-OAEP'],
                                'content_encryption_algorithms' => ['A128GCM'],
                                'jws_serializers' => ['jws_compact'],
                            ],
                        ],
                    ],
                ],
            ],
            'The child config "jwe_serializers" under "jose.nested_token.builders.foo" must be configured:'
        );
    }

    #[Test]
    public function theConfigurationIsValid(): void
    {
        $this->assertConfigurationIsValid(
            [
                [
                    'nested_token' => [
                        'builders' => [
                            'foo' => [
                                'signature_algorithms' => ['RS256'],
                                'key_encryption_algorithms' => ['RSA-OAEP'],
                                'content_encryption_algorithms' => ['A128GCM'],
                                'jws_serializers' => ['jws_compact'],
                                'jwe_serializers' => ['jwe_compact'],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration('jose', [
            new CoreSource(),
            new CheckerSource(),
            new SignatureSource(),
            new EncryptionSource(),
            new NestedToken(),
        ]);
    }
}
