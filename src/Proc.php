<?php

declare(strict_types=1);

namespace Oophp;

final class Proc
{
    private function __construct()
    {
    }

    public static function exec(string $command, mixed &$output = null, mixed &$resultCode = null): string|false
    {
        return exec($command, $output, $resultCode);
    }

    public static function shellExec(string $command): string|false|null
    {
        return shell_exec($command);
    }

    public static function system(string $command, mixed &$resultCode = null): string|false
    {
        return system($command, $resultCode);
    }

    public static function passThru(string $command, mixed &$resultCode = null): null|false
    {
        return passthru($command, $resultCode);
    }

    public static function open(
        string|array $command,
        array $descriptorSpec,
        mixed &$pipes,
        ?string $cwd = null,
        ?array $envVars = null,
        ?array $options = null,
    ): mixed {
        return proc_open($command, $descriptorSpec, $pipes, $cwd, $envVars, $options);
    }

    public static function close(mixed $process): int
    {
        return proc_close($process);
    }

    public static function getStatus(mixed $process): array
    {
        return proc_get_status($process);
    }
}
