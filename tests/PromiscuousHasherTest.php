<?php

    use App\Auth\PromiscuousHasher;

    class PromiscuousHasherTest extends TestCase
    {
        /**
         * @var PromiscuousHasher
         */
        private $hasher;

        protected function setUp()
        {
            parent::setUp();
            $this->hasher = new PromiscuousHasher();
        }

        public function testHashingCorrectResult()
        {
            $this->assertTrue($this->hasher->make('password') === 'password', 'Incorrect hash result');
        }

        public function testHashesMatching()
        {
            $this->assertTrue($this->hasher->check('password', 'password'), 'Hash matching failed');
        }
    }
