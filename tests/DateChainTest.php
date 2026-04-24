<?php

declare(strict_types=1);

namespace Oophp\Tests;

use DateInterval;
use DateTimeImmutable;
use Oophp\Chain\DateChain;
use Oophp\Chain\MixedChain;
use Oophp\Chain\StringChain;
use Oophp\Date;
use PHPUnit\Framework\TestCase;

final class DateChainTest extends TestCase
{
    public function testImmutableTransformMethodsReturnNewDateChain(): void
    {
        $base = Date::of('2024-01-10 14:30:00', 'UTC');
        $modified = $base
            ->timezone('Europe/Berlin')
            ->modify('+1 day')
            ->setDate(2024, 1, 20)
            ->setTime(8, 15, 0)
            ->startOfDay()
            ->endOfDay()
            ->add('P1D')
            ->sub(new DateInterval('P1D'));

        self::assertInstanceOf(DateChain::class, $modified);
        self::assertNotSame($base->get(), $modified->get());
        self::assertSame('2024-01-20 23:59:59.999999', $modified->get()->format('Y-m-d H:i:s.u'));
        self::assertSame('2024-01-10 14:30:00.000000', $base->get()->format('Y-m-d H:i:s.u'));
    }

    public function testTerminalConversionsReturnTypedChains(): void
    {
        $chain = Date::of('2024-01-10 14:30:00', 'UTC');

        $formatted = $chain->format('Y-m-d');
        $timestamp = $chain->timestamp();

        self::assertInstanceOf(StringChain::class, $formatted);
        self::assertSame('2024-01-10', $formatted->get());
        self::assertInstanceOf(MixedChain::class, $timestamp);
        self::assertSame($chain->get()->getTimestamp(), $timestamp->get());
    }

    public function testComparisonsAndDiffUseNormalizedInputs(): void
    {
        $chain = Date::of('2024-01-10 14:30:00', 'UTC');

        $diff = $chain->diff('2024-01-12 14:30:00');
        self::assertInstanceOf(MixedChain::class, $diff);
        self::assertInstanceOf(DateInterval::class, $diff->get());
        self::assertSame('2', $diff->get()->format('%a'));

        self::assertTrue($chain->isBefore('2024-01-11 00:00:00')->get());
        self::assertTrue($chain->isAfter(1_704_800_000)->get());
    }

    public function testInvokeReturnsSameAsGet(): void
    {
        $chain = Date::of('2024-01-10 14:30:00', 'UTC');

        self::assertInstanceOf(DateTimeImmutable::class, $chain->get());
        self::assertEquals($chain->get(), $chain());
    }
}
