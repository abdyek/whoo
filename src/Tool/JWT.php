<?php

namespace Abdyek\Whoo\Tool;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Tool\Interfaces\JWTInterface;

class JWT implements JWTInterface
{
    private string $secretKey = 's3cr3t';
    private string $algorithm = 'HS256';
    private array $claims = [];

    public function generateToken(int $userId, int $signOutCount): string
    {
        $payload = array_merge($this->claims, [
            'whoo' => [
                'userId' => $userId,
                'signOutCount' => $signOutCount,
            ]
        ]);
        return FirebaseJWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function payload(string $jwt): array
    {
        try {
            $payload = FirebaseJWT::decode($jwt, new Key($this->secretKey, $this->algorithm));
        } catch (\UnexpectedValueException $e) {
            throw new InvalidTokenException;
        }
        return (array) $payload;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function setAlgorithm(string $algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    public function getClaims(): array
    {
        return $this->claims;
    }

    public function setClaims(array $claims): void
    {
        $this->claims = $claims;
    }

}
