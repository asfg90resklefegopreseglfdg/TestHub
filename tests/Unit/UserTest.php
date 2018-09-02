<?php

namespace Tests\Unit;

use App\Test;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function test_adding_test_to_registered_user()
    {
        $test = $this->user->addTest([
            'name' => 'exampleName1',
            'answersPublic' => true,
            'passingPublic' => true,
            'duration' => 10,
            'description' => 'asdasd',
            'tags' => 'example,asd',
            'user_id' => $this->user->getId(),
            'questions' => [
                [
                    'question' => 'exampleQuestionName',
                    'points' => 10,
                    'typeAnswer' => 'oneAnswer',
                    'answers' => [
                        [
                            'answer' => 'correctAnswer',
                            'correct' => true,
                        ],
                        [
                            'answer' => 'notCorrectAnswer',
                            'correct' => false,
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertInstanceOf(Test::class, $test);
        $this->assertDatabaseHas('tests', [
            'name' => 'exampleName1',
            'answers_public' => true,
            'passing_public' => true,
            'duration' => 10,
            'description' => 'asdasd',
            'user_id' => $this->user->getId(),
            'link_to_stat_if_no_reg' => null
        ]);
    }

    public function test_clear_all_link_to_stat_if_no_reg()
    {

        $this->user->setUniqueId(str_random(10));
        $test = $this->user->addTest([
            'name' => 'example',
            'answersPublic' => true,
            'passingPublic' => false,
            'duration' => 5,
            'description' => 'example',
            'tags' => 'example,asd',
            'questions' => [
                [
                    'question' => 'exampleQuestionName',
                    'points' => 10,
                    'typeAnswer' => 'oneAnswer',
                    'answers' => [
                        [
                            'answer' => 'correctAnswer',
                            'correct' => true,
                        ],
                    ]
                ]
            ]
        ]);

        $test2 = $this->user->addTest([
            'name' => 'example2',
            'answersPublic' => true,
            'passingPublic' => false,
            'duration' => 5,
            'description' => 'example',
            'tags' => 'example,asd',
            'questions' => [
                [
                    'question' => 'exampleQuestionName',
                    'points' => 10,
                    'typeAnswer' => 'oneAnswer',
                    'answers' => [
                        [
                            'answer' => 'correctAnswer',
                            'correct' => true,
                        ],
                    ]
                ]
            ]
        ]);
        $this->assertNotEmpty($test->getLinkToStatIfNoReg());
        $this->assertNotEmpty($test2->getLinkToStatIfNoReg());

        $this->user->clearAllLinkToStatIfNoReg();

        $this->assertDatabaseHas('tests', [
            'id' => $test->getId(),
            'link_to_stat_if_no_reg' => null,
        ]);
        $this->assertDatabaseHas('tests', [
            'id' => $test2->getId(),
            'link_to_stat_if_no_reg' => null,
        ]);
    }

    public function test_adding_statistic()
    {
        $test = factory(Test::class)->create([
            'duration' => 5,

        ]);
        $this->user->addStatistic($test);
        $this->assertDatabaseHas('statistical_data', [
            'user_id' => $this->user->getId(),
            'test_id' => $test->getId(),
            'test_end_time' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'test_complete' => false
        ]);
    }

    public function test_adding_test_without_reg_without_passing_public()
    {
        $user = factory(User::class)->create(['unique_id' => '123']);


        $test = $user->addTest([
            'name' => 'exampleName2',
            'answersPublic' => true,
            'passingPublic' => false,
            'duration' => 0,
            'description' => 'qweqweqwe',
            'tags' => 'example,asd',
            'user_id' => $user->getId(),
            'questions' => [
                [
                    'question' => 'exampleQuestionName',
                    'points' => 10,
                    'typeAnswer' => 'oneAnswer',
                    'answers' => [
                        [
                            'answer' => 'correctAnswer',
                            'correct' => true,
                        ],
                        [
                            'answer' => 'notCorrectAnswer',
                            'correct' => false,
                        ]
                    ]
                ]
            ]
        ]);


        $this->assertInstanceOf(Test::class, $test);
        $this->assertDatabaseHas('tests', [
            'name' => 'exampleName2',
            'answers_public' => true,
            'passing_public' => false,
            'duration' => 0,
            'description' => 'qweqweqwe',
            'user_id' => $user->getId(),
            'link_to_stat_if_no_reg' => $test->getLinkToStatIfNoReg(),
        ]);
    }




    public function test_creating_anonymous_user()
    {

        $anonymousUser = $this->user->createAnonymous();

        $this->assertInstanceOf(User::class, $anonymousUser);
        $this->assertDatabaseHas('users', ['id' => $anonymousUser->getId()]);
    }


    public function test_add_and_cleaning_unique_id()
    {

        $this->user->setUniqueId('string');

        $this->assertEquals('string', $this->user->getUniqueId());

        $this->assertEquals(null, $this->user->clearUniqueId());
    }

    public function test_update_email()
    {
        $user = factory(User::class)->create(['email' => 'example@mail.com']);

        $user->updateEmail('new@mail.com');

        $this->assertEquals('new@mail.com', $user->getEmail());
    }

    public function test_creating_new_user()
    {

        $newUser = $this->user->createNewUser(
            [
                'username' => 'example',
                'email' => 'example@mail.ru',
                'password' => 'example',
            ]
        );
        $this->assertInstanceOf(User::class, $newUser);
        $this->assertEquals(['example', 'example@mail.ru'], [$newUser->getUsername(), $newUser->getEmail()]);
        $this->assertDatabaseHas('users', [
            'id' => $newUser->getId(),
            'username' => 'example',
            'email' => 'example@mail.ru',
        ]);
    }

    public function test_getting_user()
    {
        $user = $this->user->getUser();

        $this->assertInstanceOf(User::class, $user);

    }
}
