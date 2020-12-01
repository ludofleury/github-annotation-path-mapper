<?php

use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    private const SCRIPT = __DIR__.'/../bin/github-annotation-mapper';

    public function testPrefixesFileLocation()
    {
        $stdin = '::error file=app.js,line=10,col=15::Something went wrong';
        $prefix = 'src/';
        $command = sprintf('echo %s | %s %s', $stdin,  self::SCRIPT, $prefix);
        
        $output = exec ($command, $stdout, $sigterm);

        $this->assertEquals('::error file=src/app.js,line=10,col=15::Something went wrong', $output);
    }

    public function testExitsWithSigterm0()
    {
        $annotation = 'test';
        $prefix = 'src/';
        $command = sprintf('echo %s | %s %s', $annotation,  self::SCRIPT, $prefix);

        $output = exec ($command, $stdout, $sigterm);

        $this->assertEquals('test', $output);

        $this->assertIsInt($sigterm);
        $this->assertEquals(0, $sigterm);
    }

    public function testExitsWithSigterm2ToMarkGithubActionFailed()
    {
        $annotation = '::error file=app.js,line=10,col=15::Something went wrong';
        $prefix = 'src/';
        $command = sprintf('echo %s | %s %s', $annotation,  self::SCRIPT, $prefix);

        $output = exec ($command, $stdout, $sigterm);

        $this->assertEquals('::error file=src/app.js,line=10,col=15::Something went wrong', $output);

        $this->assertIsInt($sigterm);
        $this->assertEquals(2, $sigterm);
    }

    public function testExitsWithSigterm1WithSilentOption()
    {
        $annotation = '::error file=app.js,line=10,col=15::Something went wrong';
        $prefix = 'src/';
        $command = sprintf('echo %s | %s --silent %s', $annotation,  self::SCRIPT, $prefix);

        $output = exec($command, $stdout, $sigterm);

        $this->assertEquals('::error file=src/app.js,line=10,col=15::Something went wrong', $output);
    

        $this->assertIsInt($sigterm);
        $this->assertEquals(0, $sigterm);
    }
}