<?php

namespace Jasny;

use org\bovigo\vfs\vfsStream;

/**
 * Tests for Jasny\InviteCode
 * 
 * @package Test
 */
class InviteCodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $dir = vfsStream::setup('invite-codes');
        vfsStream::newFile('UNUSED')->at($dir);
        vfsStream::newFile('USED')->at($dir)->setContent("user@example.com\n2014-12-01T02:15:00+00:00\n");
        
        InviteCode::setDir(vfsStream::url('invite-codes'));
    }
    
    /**
     * Test InviteCode::getDir()
     */
    public function testGetDir()
    {
        $this->assertEquals(vfsStream::url('invite-codes'), InviteCode::getDir());
    }
    
    /**
     * Test InviteCode::isValid()
     */
    public function testIsValid()
    {
        $unused = new InviteCode('UNUSED');
        $used = new InviteCode('USED');
        $invalid = new InviteCode('INVALID');
        
        $this->assertTrue($unused->isValid());
        $this->assertTrue($used->isValid());
        $this->assertFalse($invalid->isValid());
    }
    
    /**
     * Test InviteCode::isUsed()
     */
    public function testIsUsed()
    {
        $unused = new InviteCode('UNUSED');
        $used = new InviteCode('USED');
        $invalid = new InviteCode('INVALID');
        
        $this->assertFalse($unused->isUsed());
        $this->assertTrue($used->isUsed());
        $this->assertFalse($invalid->isUsed());
    }
    
    /**
     * Test InviteCode::useBy()
     */
    public function testUseBy()
    {
        $code = new InviteCode('UNUSED');
        
        // Only using a partial date, but there is still a (minor) chance that the time stamp is changed
        $expect = "foo@bar.com\n" . date('Y-m-d\TH:');
        
        $code->useBy("foo@bar.com");
        
        $this->assertTrue(file_exists(vfsStream::url('invite-codes/UNUSED')));
        
        $contents = file_get_contents(vfsStream::url('invite-codes/UNUSED'));
        $this->assertStringStartsWith($expect, $contents);
    }
    
    /**
     * Test InviteCode::useBy() with invalid code
     */
    public function testUseBy_InvalidException()
    {
        $code = new InviteCode('INVALID');

        $this->setExpectedException('Exception', "Invalid invitation code");
        $code->useBy("foo@bar.com");
    }
    
    /**
     * Test InviteCode::useBy() with used code
     */
    public function testUseBy_UsedException()
    {
        $code = new InviteCode('USED');

        $this->setExpectedException('Exception', "Invitation has already been used");
        $code->useBy("foo@bar.com");
    }
    
    /**
     * Test InviteCode::__toString()
     */
    public function testToString()
    {
        $foobar = new InviteCode("foobar");
        $this->assertEquals("foobar", (string)$foobar);
        
        $mixedcase = new InviteCode("MixedCase1234");
        $this->assertEquals("MixedCase1234", (string)$mixedcase);
    }    
}
