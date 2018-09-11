<template>
    <div class="card card-default">
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
                questionId: 0,
                userAnswers: [],
                urlToRedirect: '/test/' + this.slug
            }
        },

        methods: {

            redirect: function () {
                window.location.href = this.urlToRedirect;
            },

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
                                    this.redirect();
                                }
                            }
                        }.bind(this))
                        .catch(error => {
                            console.log(error);
                        });
                } else {
                    this.redirect();
                }
            }
        },

        created: function() {
            this.$eventBus.$on('end-test', function () {
                this.endTest()
            }.bind(this));
            this.$eventBus.$on('uncorrect-time', function () {
                this.clearLocalStorage();
                this.redirect();
            }.bind(this));
        },

        watch: {
            questionId: function () {
                this.saveAnswersInLocalStorage();
            },
        },

        mounted: function () {
            this.checkIfStatisticsCreated();
            if (JSON.parse(localStorage.getItem('answers' + this.testId))) {
                this.userAnswers = JSON.parse(localStorage.getItem('answers' + this.testId));
            } else {
                this.setTemplateAnswers();
            }
        }
    }
</script>
















