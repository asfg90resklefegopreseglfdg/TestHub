<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="center-block">
                    <div class="card card-default">
                        <div>
                            <div class="card-header">
                                <div align="center"><h3>Тест: {{testName}}</h3></div>
                            </div>
                            <div v-for="(question, questionKey) in questions"
                                 v-if="questionKey === questionId">
                                <div class="form-control">
                                    <div align="center"><h5>Вопрос номер {{questionKey + 1}}</h5></div>
                                    {{question.question}}
                                    <template v-if="question.type_answer === 'oneAnswer'">
                                        <div v-for="(answer, answerKey) in question.answers">
                                            {{answerKey + 1}}.
                                            <label class="form-check-label">
                                                <input type="radio"
                                                       :value="answer.answer"
                                                       v-model="userAnswers[questionId].answer">
                                                {{answer.answer}}
                                            </label>
                                        </div>
                                    </template>
                                    <template v-else-if="question.type_answer === 'someAnswers'">
                                        <div v-for="(answer, answerKey) in question.answers">
                                            {{answerKey + 1}}.
                                            <label class="form-check-label">
                                                <input type="checkbox"
                                                       :name="answerKey"
                                                       :true-value="answer.answer"
                                                       v-model="userAnswers[questionId].answers[answerKey]">
                                                {{answer.answer}}
                                            </label>
                                        </div>
                                    </template>
                                    <template v-else-if="question.type_answer === 'number'">
                                        <br>
                                        <div>
                                            <input class="form-control col-3"
                                                   type="number"
                                                   placeholder="Числовой ответ"
                                                   v-model="userAnswers[questionKey].answer">
                                        </div>
                                    </template>
                                    <template v-else-if="question.type_answer === 'text'">
                                        <br>
                                        <div>
                                            <input class="form-control col-3"
                                                   placeholder="Текстовый ответ"
                                                   v-model="userAnswers[questionKey].answer">
                                        </div>
                                    </template>
                                </div>
                                <button class="btn btn-primary"
                                        :class="{'disabled' : questionId === 0}"
                                        @click="(questionId == 0) ? 0 : questionId--"
                                >Предыдущий вопрос
                                </button>
                                <button class="btn btn-primary"
                                        :class="{'disabled' : questionId === (Object.keys(questions).length-1)}"
                                        @click="(questionId == Object.keys(questions).length-1) ?
                                         Object.keys(questions).length-1
                                         :
                                         questionId++"
                                >Следующий вопрос
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div v-if="duration !== 0" class="card card-default" align="center">
                    <h3>Осталось времени {{setTime}}</h3>
                </div>
                <div class="card card-default" align="center">
                    <button class="btn btn-success"
                            @click="endTest"
                    >Закончить тест
                    </button>
                </div>
                <div class="card card-default" align="center">
                    <p>Вы можете походить тесты без регистрации, но если вы зарегистрируетесь, то все ваши
                        созднные тесты и результаты пройденных тестов сохранятся.</p>
                    <p><a href="/register">Зарегистрироваться</a></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            test: {
                type: Object,
                required: true,
            },
            testName: {
                type: String,
                required: true,
            },
            testId: {
                type: Number,
                required: true,
            },
            duration: {
                type: Number,
                required: true,
            },
            questions: {
                type: Array,
                required: true
            },
            slug: {
                type: String,
                required: true
            }
        },

        data: function () {
            return {
                timerDisplay: '',
                timerLength: 1,
                questionId: 0,
                date: new Date(),
                userAnswers: [],
                urlToRedirectIfIdentifierError: '/test/' + this.slug
            }
        },

        methods: {
            endTest: function () {
                axios.post('/test/' + this.slug + '/questions', {
                    userAnswers: this.userAnswers,
                    identifier: localStorage.getItem('identifier' + this.testId),
                    withCredentials: true,
                })
                    .then(function (response) {
                        let urlToRedirect;
                        this.clearLocalStorage();
                        if (response.status === 200) {
                            urlToRedirect = response.data['redirectTo'];
                            window.location.href = urlToRedirect;
                        }
                    }.bind(this))
                    .catch(error => {
                        console.log(error);
                    });

            },

            clearLocalStorage: function () {
                localStorage.removeItem('durationTo' + this.testId);
                localStorage.removeItem('answers' + this.testId);
                localStorage.removeItem('identifier' + this.test.id);
            },

            setTimerLengthFromLocalStorage: function () {
                let testEndTime;
                testEndTime = new Date(localStorage.getItem('durationTo' + this.testId));
                this.timerLength = Math.floor((testEndTime - this.date) / 1000);
            },

            setTimerLengthFromDuration: function () {
                this.timerLength = this.duration * 60;
            },

            timer: function () {
                this.timerLength--;
            },

            setTimer: function () {
                let durationTo;
                durationTo = (localStorage.getItem('durationTo' + this.testId)) ? (
                    new Date(localStorage.getItem('durationTo' + this.testId))
                ) : (
                    0
                );
                if (durationTo > this.date) {
                    this.setTimerLengthFromLocalStorage();
                } else {
                    this.date.setMinutes(this.date.getMinutes() + this.duration);
                    localStorage.setItem('durationTo' + this.testId, this.date);
                    this.setTimerLengthFromDuration();
                }
                setInterval(this.timer, 1000)
            },

            setTemplateAnswers: function () {
                this.questions.forEach(function (question) {
                    if (question.type_answer === 'someAnswers') {
                        this.push({
                            questionId: question.id,
                            answers: []
                        });
                    } else {
                        this.push({
                            questionId: question.id,
                            answer: ''
                        });
                    }
                }, this.userAnswers)
            },

            saveAnswersInLocalStorage: function () {
                localStorage.setItem('answers' + this.testId, JSON.stringify(this.userAnswers));
            },

            checkIfStatisticsCreated: function () {
                let identifier;
                identifier = (localStorage.getItem('identifier' + this.testId)) ? localStorage.getItem('identifier' + this.testId) : 0;
                if (identifier) {
                    axios.post('/check-if-statistics-created', {
                        identifier: identifier,
                        withCredentials: true
                    })
                        .then(function (response) {
                            if (response.status === 200) {
                                let exists;
                                exists = response.data['exists'];
                                if (!exists) {
                                    this.clearLocalStorage();
                                    window.location.href = this.urlToRedirectIfIdentifierError;
                                }
                            }
                        }.bind(this))
                        .catch(error => {
                            console.log(error);
                        });
                } else {
                    window.location.href = this.urlToRedirectIfIdentifierError;
                }
            }
        },

        watch: {
            questionId: function () {
                this.saveAnswersInLocalStorage();
            },
        },

        computed: {
            setTime: function () {
                if (this.timerLength === 0) {
                    this.endTest();
                }
                let minutes = Math.floor(this.timerLength / 60);
                let seconds = this.timerLength % 60;
                if (seconds < 10) {
                    seconds = "0" + seconds;
                }
                if (minutes < 10) {
                    minutes = "0" + minutes;
                }
                return this.timerDisplay = minutes + ":" + seconds;
            },
        },

        mounted: function () {
            this.checkIfStatisticsCreated();
            if (+!this.duration === 0) {
                this.setTimer();
            }
            if (JSON.parse(localStorage.getItem('answers' + this.testId))) {
                this.userAnswers = JSON.parse(localStorage.getItem('answers' + this.testId));
            } else {
                this.setTemplateAnswers();
            }
        }
    }
</script>
















