<?php
declare(strict_types=1);

namespace Zored\Dmns\Host\MacOS;


use League\Flysystem\FilesystemInterface;
use Zored\Dmns\Host\DnsUpdaterInterface;
use Zored\Dmns\Model\Address;

class AutoloaderUpdater implements DnsUpdaterInterface
{
    private const NAME = 'local.dmns';

    /** @var FilesystemInterface */
    private $fs;

    /** @var string */
    private $command;

    public function __construct(FilesystemInterface $fs, string $command)
    {
        $this->fs = $fs;
        $this->command = $command;
    }

    public function update(Address $address, string $tld): bool
    {
        $name = self::NAME;
        $path = $this->getPath($name);
        if (!$this->fs->put($path, $this->getContent($name, $address, $tld))) {
            return false;
        }

        exec("launchctl load $path", $output, $exitCode);
        return $exitCode === 0;
    }

    private function getContent(string $name, Address $address, string $tld): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
        <key>Disabled</key>
        <false/>
        <key>Label</key>
        <string>$name</string>
        <key>ProgramArguments</key>
        <array>
                <string>{$this->command}</string>
                <string>server:start</string>

                <string>--host</string>
                <string>{$address->getHost()}</string>
                
                <string>--port</string>
                <string>{$address->getPort()}</string>

                <string>--tld</string>
                <string>$tld</string>
        </array>
        <key>RunAtLoad</key>
        <true/>
</dict>
</plist>
XML;

    }

    private function getPath(string $name): string
    {
        return "~/Library/LaunchAgents/$name.plist";
    }

}