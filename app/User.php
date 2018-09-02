<?php

namespace App;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Mockery\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'unique_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function statistics()
    {
        return $this->hasMany(Statistics::class);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        if (Auth::check()) {
            $user = Auth::user();
        } elseif (Cookie::get('uniqueId')) {
            $uniqueId = Cookie::get('uniqueId');
            try {
                $user = $this->getUserByUniqueId($uniqueId);
            } catch (ModelNotFoundException $e) {
                $user = $this->createAnonymous();
            }
        } else {
            $user = $this->createAnonymous();
        }

        return $user;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $uniqueId
     * @return User
     */
    public function getUserByUniqueId(string $uniqueId): User
    {
        return User::where('unique_id', '=', $uniqueId)->firstOrFail();
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function createNewUser(array $attributes): User
    {
        $user = new User();
        $user = $user->create([
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
        ]);

        return $user;
    }

    /**
     * @return User
     */
    public function createAnonymous(): User
    {
        $user = new User();
        $uniqueId = $user->generateUniqueStr();
        $user->setUniqueId($uniqueId);
        $user->setUniqueIdInCookie($uniqueId);

        return $user;
    }

    /**
     * @param int $length
     * @return string
     */
    private function generateUniqueStr($length = 40): string
    {
        return str_random($length);
    }

    /**
     * @param String $uniqueId
     */
    private function setUniqueIdInCookie(String $uniqueId): void
    {
        Cookie::queue('uniqueId', $uniqueId, 10080);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function updateEmail($email): void
    {
        $this->email = $email;
        $this->saveOrFail();
    }

    /**
     * @param $uniqueId
     */
    public function setUniqueId($uniqueId): void
    {
        $this->unique_id = $uniqueId;
        $this->saveOrFail();
    }

    /**
     * @return bool
     */
    public function existsUniqueId(): bool
    {
        if ($this->unique_id) {
            return true;
        }
        return false;
    }

    /**
     * Create new Test
     *
     * @param array $testOptions
     * @return Test
     */
    public function addTest(array $testOptions): Test
    {
        $test = Test::create([
            'name' => $testOptions['name'],
            'answers_public' => $testOptions['answersPublic'],
            'passing_public' => $testOptions['passingPublic'],
            'duration' => $testOptions['duration'],
            'description' => $testOptions['description'],
            'user_id' => $this->getId()
        ]);
        if ($this->existsUniqueId() and $test->checkPassingPublic() === false) {
            $test->setLinkToStatistics();
        }
        $test->addTags($testOptions['tags']);
        $test->addQuestions($testOptions['questions']);
        $test->save();

        return $test;
    }

    /**
     * @param Test $test
     * @return Statistics
     */
    public function addStatistic(Test $test): Statistics
    {
        $testEndTime = $test->generateTestEndTime($test);
        return $statistics = Statistics::create([
            'user_id' => $this->id,
            'test_id' => $test->id,
            'test_end_time' => $testEndTime,
            'test_complete' => false
        ]);
    }


    public function clearAllLinkToStatIfNoReg(): void
    {
        $tests = $this->tests;
        foreach ($tests as $test) {
            $test->clearLinkToStatIfNoReg();
        }
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        return $this->unique_id;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param array $attributes
     */
    public function updateAnonymousUser(array $attributes): void
    {
        $this->update([
            'username' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
        ]);
        $this->save();
    }

    /**
     *
     */
    public function clearUniqueId(): void
    {
        $this->unique_id = null;
        $this->save();
    }

}
