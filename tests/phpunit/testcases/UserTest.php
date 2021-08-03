<?php
use MLUT\PublicArea\User;
use Brain\Monkey;

class UserTest extends \PluginTestCase
{
    private $class;
    public function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();

        $this->class = new User('1','test','emirugljanin@gmail.com','test','test','test','test','test','test');
    }
    public function testAddedTransformHook()
    {
        $this->assertNotFalse(has_filter('my_lovely_users_table_transform_all'));
    }
    
    public function testNumberOfElementsInArray()
    {
        $this->assertCount(9, $this->class->export(),'The number of elements in array is not 9 as expected');
    }
    public function testArrayKeys()
    {
        $this->assertArrayHasKey('id', $this->class->export(), "Array doesn't contains 'ID' as key");
        $this->assertArrayHasKey('name', $this->class->export(), "Array doesn't contains 'name' as key");
        $this->assertArrayHasKey('username', $this->class->export(), "Array doesn't contains 'username' as key");
        $this->assertArrayHasKey('phone', $this->class->export(), "Array doesn't contains 'phone' as key");
        $this->assertArrayHasKey('street', $this->class->export(), "Array doesn't contains 'street' as key");
        $this->assertArrayHasKey('suite', $this->class->export(), "Array doesn't contains 'suite' as key");
        $this->assertArrayHasKey('city', $this->class->export(), "Array doesn't contains 'city' as key");
        $this->assertArrayHasKey('email', $this->class->export(), "Array doesn't contains 'email' as key");
        $this->assertArrayHasKey('company', $this->class->export(), "Array doesn't contains 'company' as key");

    }
    /**
     * @dataProvider emailProvider
     */
    public function testValidateEmail(string $email)
    {
        $this->assertTrue($this->class->validate_users_email($email),'Error validating Email');

    }
    public function emailProvider(): array
    {
        return [['emirugljanin@gmail.com'],['ugljanin@google.com'],['emirugljanin@inpsyde.com']];
    }
    /**
     * @dataProvider idProvider
     */
    public function testValidateID(int $id):void
    {
        $this->assertTrue($this->class->validate_users_id($id),'Error validating ID');
    }
    public function idProvider(): array
    {
        return [[1],[12],[14]];
    }


}
