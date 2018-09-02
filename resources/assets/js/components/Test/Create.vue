<template>
    <form class="form-control"
          v-on:submit.prevent="send">
        <input type="hidden" name="_token" :value="csrf">
        <div>
            <label for="test-name">Название</label>
            <input class="form-control"
                   id="test-name"
                   required
                   autofocus
                   minLength="4"
                   placeholder="Введите название"
                   v-model="testOptions.name"
                   @input="$v.testOptions.name.$touch()"
                   :class="{ 'is-invalid': $v.testOptions.name.$error, 'is-valid': !$v.testOptions.name.$invalid && $v.testOptions.name.$dirty}">
            <span class="badge badge-danger"
                  v-if="!$v.testOptions.name.required && $v.testOptions.name.$dirty">Поле обязательно для заполнения</span>
            <span class="badge badge-danger"
                  v-if="!$v.testOptions.name.minLength && $v.testOptions.name.$dirty">Имя должно содержать более {{$v.testOptions.name.$params.minLength.min}} букв.</span>
        </div>
        <div>
            <label for="test-tags">Тэги</label>
            <input class="form-control"
                   id="test-tags"
                   required
                   placeholder="Введите тэги через запятую"
                   v-model.trim="testOptions.tags"
                   @input="delayTouch($v.testOptions.tags)"
                   :class="{ 'is-invalid': $v.testOptions.tags.$error, 'is-valid': !$v.testOptions.tags.$invalid && $v.testOptions.tags.$dirty}">
            <span class="badge badge-danger"
                  v-if="!$v.testOptions.tags.required && $v.testOptions.tags.$dirty">Поле обязательно для заполнения</span>
        </div>
        <div>
            <input type="checkbox"
                   id="test-answers-public"
                   v-model="testOptions.answersPublic">
            <label for="test-answers-public">Разрешить смотреть список правильных ответов после теста</label>
        </div>
        <div>
            <input type="checkbox"
                   id="test-passing-public"
                   v-model="testOptions.passingPublic">
            <label for="test-passing-public">Сделать все результаты прохождения теста публичными</label>
        </div>
        <div>
            <label for="time-on-test">Ограничение по времени минут</label>
            <input class="form-control col-3"
                   type="number"
                   min="0"
                   id="time-on-test"
                   v-model.number.trim="testOptions.duration">
        </div>
        <div>
            <label for="description">Описание теста </label>
            <textarea id="description"
                      required
                      class="form-control"
                      v-model="testOptions.description">
            </textarea>
        </div>
        <div class="card-body"
             align="center"><h4>Вопросы</h4>
        </div>
        <div>
            <ol class="list-group">
                <draggable v-model="testOptions.questions"
                           :options="draggableOptions">
                    <li class="list-group-item"
                        v-for="(question, key) in testOptions.questions"
                        :question.id="question.id = key">
                        <div class="d-flex flex-row-reverse">
                            <button class="btn-sm"
                                    type="button"
                                    @click="testOptions.questions.splice(key, 1)">
                                X
                            </button>
                        </div>
                        Номер вопроса {{question.id + 1}}
                        <textarea class="form-control"
                                  required
                                  v-model="question.question"
                                  placeholder="введите вопрос"></textarea>
                        <div class="row">
                            <label for="question-points">Баллов за вопрос:</label>
                            <input class="form-control col-3 m-1"
                                   type="number"
                                   required
                                   min="1"
                                   id="question-points"
                                   v-model.number.trim="question.points">
                        </div>
                        <div class="btn-group btn-group-toggle">
                            <label class="btn btn-secondary"
                                   :class="{'active' : question.typeAnswer === 'oneAnswer'}">
                                <input type="radio"
                                       value="oneAnswer"
                                       id="type-answer-one-answer"
                                       v-model="question.typeAnswer"> Один ответ
                            </label>
                            <label class="btn btn-secondary"
                                   :class="{'active' : question.typeAnswer === 'someAnswers'}">
                                <input type="radio"
                                       value="someAnswers"
                                       id="type-answer-some-answers"
                                       v-model="question.typeAnswer"> Несколько ответов
                            </label>
                            <label class="btn btn-secondary"
                                   :class="{'active' : question.typeAnswer === 'number'}">
                                <input type="radio"
                                       value="number"
                                       id="type-answer-number"
                                       v-model="question.typeAnswer"> Число
                            </label>
                            <label class="btn btn-secondary"
                                   :class="{'active' : question.typeAnswer === 'text'}">
                                <input type="radio"
                                       value="text"
                                       id="type-answer-text"
                                       v-model="question.typeAnswer"> Текст
                            </label>
                        </div>
                        <template v-if="question.typeAnswer === 'oneAnswer'">
                            <div class="row"
                                 v-for="(answer, key) in question.answers">
                                <div class="width-3">
                                    {{key + 1}}.
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           required
                                           :value="true"
                                           @click="makeAllResponsesIncorrect(question.answers)"
                                           v-model="answer.correct">
                                </div>
                                <input class="form-control col-3 m-010"
                                       v-model="answer.answer"
                                       required
                                       placeholder="Вариант ответа">
                                <button class="btn btn-secondary btn-sm"
                                        type="button"
                                        @click="question.answers.splice(key, 1)">x
                                </button>

                            </div>
                            <button class="btn btn-secondary"
                                    type="button"
                                    @click="addAnswer(question)">Добавить ответ
                            </button>
                        </template>
                        <template v-else-if="question.typeAnswer === 'someAnswers'">
                            <div class="row"
                                 v-for="(answer, key) in question.answers">
                                <div class="width-3">
                                    {{key + 1}}.
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           :value="true"
                                           v-model="answer.correct">
                                </div>
                                <input class="form-control col-3 m-010"
                                       v-model="answer.answer"
                                       required
                                       placeholder="Вариант ответа">
                                <button class="btn btn-secondary btn-sm"
                                        type="button"
                                        @click="question.answers.splice(key, 1)">x
                                </button>
                            </div>
                            <button class="btn btn-secondary"
                                    type="button"
                                    @click="addAnswer(question)">Добавить ответ
                            </button>
                        </template>
                        <template v-else-if="question.typeAnswer === 'number'">
                            <br>
                            <div>
                                <input class="form-control col-3 m-010"
                                       type="number"
                                       placeholder="Числовой ответ"
                                       @input="question.answers[0].correct = true"
                                       v-model="question.answers[0].answer">
                            </div>
                        </template>
                        <template v-else-if="question.typeAnswer === 'text'">
                            <br>
                            <div>
                                <input class="form-control col-3 m-010"
                                       placeholder="Текстовый ответ"
                                       @input="question.answers[0].correct = true"
                                       v-model="question.answers[0].answer">
                            </div>
                        </template>
                    </li>
                </draggable>
                <div class="row">
                    <button class="btn btn-secondary col item"
                            type="button"
                            @click="addQuestion">Добавить вопрос
                    </button>
                    <button class="btn btn-primary col item">Создать тест
                    </button>

                </div>
            </ol>
        </div>
    </form>
</template>

<script>
    import Vuelidate from 'vuelidate';
    import draggable from 'vuedraggable';

    window.Vue.use(Vuelidate, draggable);
    import {required, minLength, between} from 'vuelidate/lib/validators';

    const touchMap = new WeakMap();


    export default {
        components: {
            draggable
        },

        data: function () {
            return {

                asd: {},

                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),


                testOptions: {
                    name: '',
                    tags: '',
                    answersPublic: false,
                    passingPublic: false,
                    duration: 0,
                    description: '',
                    questions: [
                        {
                            id: 1, question: '', points: 0, typeAnswer: 'oneAnswer', answers: [
                            {answer: '', correct: false},
                            {answer: '', correct: false}
                        ]
                        },
                        {
                            id: 2, question: '', points: 0, typeAnswer: 'oneAnswer', answers: [
                            {answer: '', correct: false},
                            {answer: '', correct: false}
                        ]
                        },
                    ],
                },
                nextQuestionId: 3,

                draggableOptions: {
                    delay: 150,
                    animation: 100
                }
            }
        },

        methods: {
            send: function () {
                this.prepareAnswers();
                axios.post('/test', {
                    testOptions: this.testOptions,
                    withCredentials: true,
                })
                    .then(function (response) {
                        let urlToRedirect;
                        if (response.status === 200) {
                            urlToRedirect = response.data['redirectTo'];
                            window.location.href = urlToRedirect;
                        }
                    })
            },

            prepareAnswers: function () {
                this.testOptions.questions.forEach(function (question) {
                    if (question.typeAnswer === 'text' || question.typeAnswer === 'number') {
                        question.answers.splice(1);
                    }
                })
            },

            addQuestion: function () {
                this.testOptions.questions.push({
                    id: this.nextQuestionId++,
                    question: '',
                    questionPoints: 0,
                    typeAnswer: 'oneAnswer',
                    answers: [{answer: '', correct: false},
                        {answer: '', correct: false}],

                });
            },

            addAnswer: function (question) {
                question.answers.push({answer: '', correct: false});
            },

            delayTouch($v) {
                $v.$reset();
                if (touchMap.has($v)) {
                    clearTimeout(touchMap.get($v))
                }
                touchMap.set($v, setTimeout($v.$touch, 1000))
            },

            makeAllResponsesIncorrect(answers) {
                for (let answer in answers) {
                    if (answers[answer].correct) {
                        answers[answer].correct = false;
                    }
                }
            }
        },

        validations: {
            testOptions: {
                name: {
                    required,
                    minLength: minLength(4)
                },

                tags: {
                    required
                },
            }
        }
    };

</script>

<style>
    .m-010 {
        margin: 0.10rem !important;
    }

    .width-3 {
        width: 3%
    }

    .sortable-ghost {
        opacity: .8;
        background: #C8EBFB;
    }
</style>