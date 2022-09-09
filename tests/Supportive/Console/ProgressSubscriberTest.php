<?php

declare(strict_types=1);

namespace Tests\Qossmic\Deptrac\Supportive\Console;

use PHPUnit\Framework\TestCase;
use Qossmic\Deptrac\Contract\Ast\AstFileAnalysedEvent;
use Qossmic\Deptrac\Contract\Ast\PostCreateAstMapEvent;
use Qossmic\Deptrac\Contract\Ast\PreCreateAstMapEvent;
use Qossmic\Deptrac\Supportive\Console\Subscriber\ProgressSubscriber;
use Qossmic\Deptrac\Supportive\Console\Symfony\Style;
use Qossmic\Deptrac\Supportive\Console\Symfony\SymfonyOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ProgressSubscriberTest extends TestCase
{
    public function testSubscribedEvents(): void
    {
        self::assertSame(
            [
                PreCreateAstMapEvent::class => 'onPreCreateAstMapEvent',
                PostCreateAstMapEvent::class => ['onPostCreateAstMapEvent', 1],
                AstFileAnalysedEvent::class => 'onAstFileAnalysedEvent',
            ],
            ProgressSubscriber::getSubscribedEvents()
        );
    }

    public function testProgress(): void
    {
        $bufferedOutput = new BufferedOutput();
        $subscriber = new ProgressSubscriber($this->createSymfonyOutput($bufferedOutput));

        $subscriber->onPreCreateAstMapEvent(new PreCreateAstMapEvent(1));
        $subscriber->onAstFileAnalysedEvent(new AstFileAnalysedEvent('foo.php'));
        $subscriber->onPostCreateAstMapEvent(new PostCreateAstMapEvent());

        $expectedOutput = <<<OUT
 0/1 [░░░░░░░░░░░░░░░░░░░░░░░░░░░░]   0%
 1/1 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%


OUT;

        self::assertSame($expectedOutput, $bufferedOutput->fetch());
    }

    public function testOnPostCreateAstMapEvent(): void
    {
        $formatter = new BufferedOutput();
        $subscriber = new ProgressSubscriber($this->createSymfonyOutput($formatter));

        $subscriber->onPreCreateAstMapEvent(new PreCreateAstMapEvent(1));
        $subscriber->onPostCreateAstMapEvent(new PostCreateAstMapEvent());

        $expectedOutput = <<<OUT
 0/1 [░░░░░░░░░░░░░░░░░░░░░░░░░░░░]   0%
 1/1 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%


OUT;

        self::assertSame($expectedOutput, $formatter->fetch());
    }

    private function createSymfonyOutput(BufferedOutput $bufferedOutput): SymfonyOutput
    {
        return new SymfonyOutput(
            $bufferedOutput,
            new Style(new SymfonyStyle($this->createMock(InputInterface::class), $bufferedOutput))
        );
    }
}