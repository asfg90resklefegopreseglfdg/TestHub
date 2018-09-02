<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Auth;
use Kyslik\ColumnSortable\Sortable;

class Test extends Model
{
    use Sluggable, Sortable;
    protected $fillable = [
        'name', 'answers_public', 'passing_public', 'duration', 'user_id', 'description', 'link_to_stat_if_no_reg'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'test_tags');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function statistics()
    {
        return $this->hasMany(Statistics::class);
    }

    public $sortable = [
        'id',
        'name',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * @return string
     */
    public function generateTestEndTime(): string
    {
        $time = time();
        if ($this->getDuration() === 0) {
            $testEndTime = date('Y-m-d H:i:s', strtotime("+1 week"));
            return $testEndTime;
        }
        $testDurationSeconds = $this->duration * 60;
        $testEndTime = $time + $testDurationSeconds;
        $testEndTime = date('Y-m-d H:i:s', $testEndTime);
        return $testEndTime;
    }


    /**
     * @param array $questions
     */
    public function addQuestions(array $questions): void
    {
        foreach ($questions as $questionParam) {
            $question = $this->addQuestion($questionParam);
            $question->addAnswers($questionParam['answers']);
        }
    }

    /**
     * @param array $questionParam
     * @return Question
     */
    public function addQuestion(array $questionParam): Question
    {

        return $question = Question::create([
            'question' => $questionParam['question'],
            'points' => $questionParam['points'],
            'type_answer' => $questionParam['typeAnswer'],
            'test_id' => $this->id
        ]);
    }

    /**
     * @param String $data
     */
    public function addTags(String $data): void
    {
        $tags = $this->explodeTags($data);
        foreach ($tags as $tagName) {
            $this->addTag($tagName);
        }
    }


    /**
     * @param Tag $tag
     */
    public function attach(Tag $tag): void
    {
        $this->tags()->attach($tag->id);
    }

    /**
     * @param string $tagName
     * @return Tag
     */
    public function addTag(string $tagName): Tag
    {
        $tag = Tag::firstOrCreate(['tag' => $tagName]);
        $this->attach($tag);
        return $tag;
    }

    /**
     * @param string $data
     * @return array
     */
    public function explodeTags(string $data): array
    {
        $tags = array_map('trim', explode(",", $data));
        $tags = array_map('mb_strtolower', $tags);
        $tags = array_filter($tags);

        return $tags;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }


    /**
     * @return mixed
     */
    public function getLinkToStatIfNoReg()
    {
        return $this->link_to_stat_if_no_reg;
    }

    /**
     * @return bool
     */
    public function checkPassingPublic(): bool
    {
        if ($this->passing_public) {
            return true;
        }
        return false;
    }

    /**
     * Set link to statistics for unregistered users
     */
    public function setLinkToStatistics(): void
    {
        $linkToStat = $this->generateString();
        $this->update(['link_to_stat_if_no_reg' => $linkToStat]);
        $this->save();
    }

    /**
     *
     * @return string
     */
    private function generateString(): string
    {
        return str_random(40);
    }

    /**
     * Get Collection of questions
     *
     * @return mixed
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }


    /**
     * @return int
     */
        public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get test slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     *
     */
    public function clearLinkToStatIfNoReg(): void
    {
        $this->link_to_stat_if_no_reg = null;
        $this->save();

    }


}
