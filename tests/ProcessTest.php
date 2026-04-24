<?php

declare(strict_types=1);

namespace Oophp\Tests;

use Oophp\Process;
use PHPUnit\Framework\TestCase;

final class ProcessTest extends TestCase
{
    public function testProcessDomainRemainsStaticOnly(): void
    {
        self::assertFalse(method_exists(Process::class, 'of'));
    }

    public function testExecConformanceWithOutputParameters(): void
    {
        $command = $this->phpEchoCommand('exec-ok');

        $nativeOutput = [];
        $nativeCode = 0;
        $wrappedOutput = [];
        $wrappedCode = 0;

        $expected = exec($command, $nativeOutput, $nativeCode);
        $actual = Process::exec($command, $wrappedOutput, $wrappedCode);

        self::assertSame($expected, $actual);
        self::assertSame($nativeOutput, $wrappedOutput);
        self::assertSame($nativeCode, $wrappedCode);
    }

    public function testShellExecConformance(): void
    {
        $command = $this->phpEchoCommand('shell-ok');

        self::assertSame(shell_exec($command), Process::shellExec($command));
    }

    public function testSystemConformanceWithCapturedOutput(): void
    {
        $command = $this->phpEchoCommand('system-ok');

        $nativeCode = 0;
        ob_start();
        $expected = system($command, $nativeCode);
        $nativeOutput = ob_get_clean();

        $wrappedCode = 0;
        ob_start();
        $actual = Process::system($command, $wrappedCode);
        $wrappedOutput = ob_get_clean();

        self::assertSame($expected, $actual);
        self::assertSame($nativeCode, $wrappedCode);
        self::assertSame($nativeOutput, $wrappedOutput);
    }

    public function testPassthruConformanceWithCapturedOutput(): void
    {
        $command = $this->phpEchoCommand('passthru-ok');

        $nativeCode = 0;
        ob_start();
        $expected = passthru($command, $nativeCode);
        $nativeOutput = ob_get_clean();

        $wrappedCode = 0;
        ob_start();
        $actual = Process::passthru($command, $wrappedCode);
        $wrappedOutput = ob_get_clean();

        self::assertSame($expected, $actual);
        self::assertSame($nativeCode, $wrappedCode);
        self::assertSame($nativeOutput, $wrappedOutput);
    }

    public function testProcOpenLifecycleConformance(): void
    {
        $command = $this->phpEchoCommand('proc-ok');
        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $nativePipes = [];
        $nativeProcess = proc_open($command, $descriptors, $nativePipes);
        self::assertIsResource($nativeProcess);

        $wrappedPipes = [];
        $wrappedProcess = Process::procOpen($command, $descriptors, $wrappedPipes);
        self::assertIsResource($wrappedProcess);

        $nativeStatus = proc_get_status($nativeProcess);
        $wrappedStatus = Process::procGetStatus($wrappedProcess);
        self::assertIsArray($nativeStatus);
        self::assertIsArray($wrappedStatus);
        self::assertSame($nativeStatus['running'], $wrappedStatus['running']);

        $nativeStdout = stream_get_contents($nativePipes[1]);
        $wrappedStdout = stream_get_contents($wrappedPipes[1]);

        fclose($nativePipes[1]);
        fclose($nativePipes[2]);
        fclose($wrappedPipes[1]);
        fclose($wrappedPipes[2]);

        $nativeCode = proc_close($nativeProcess);
        $wrappedCode = Process::procClose($wrappedProcess);

        self::assertSame($nativeStdout, $wrappedStdout);
        self::assertSame($nativeCode, $wrappedCode);
    }

    private function phpEchoCommand(string $text): string
    {
        return escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg('echo "' . $text . '";');
    }
}
