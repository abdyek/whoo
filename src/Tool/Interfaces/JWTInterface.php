<?php

namespace Abdyek\Whoo\Tool\Interfaces;

interface JWTInterface
{
    public function generateToken(int $userId, int $signOutCount): string;
    public function payload(string $jwt): array;
    public function getSecretKey(): string;
    public function setSecretKey(string $secreyKey): void;
    public function getAlgorithm(): string;
    public function setAlgorithm(string $algorithm): void;
    public function getClaims(): array;
    public function setClaims(array $claims): void;
}
