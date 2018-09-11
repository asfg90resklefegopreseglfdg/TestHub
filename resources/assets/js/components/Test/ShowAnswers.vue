<template>
    <div class="card card-default">
        <div>
            <div class="card-header">
                <div align="center"><h3>Ответы на тест: {{testName}}</h3></div>
            </div>
            <div v-for="(question, questionKey) in questions"
                 v-if="questionKey === questionId">
                <div class="form-control">
                    <div align="center"><h5>Вопрос номер {{questionKey + 1}}</h5></div>
                    {{question.question}}
                    <div v-for="(answer, answerKey) in question.answers">
                        {{answerKey + 1}}.
                        <label class="form-check-label">
                            <template v-if="answer.correct">
                                <h4><span class="badge badge-success">{{answer.answer}}</span></h4>
                            </template>
                            <template v-else="">
                                {{answer.answer}}
                            </template>
                        </label>
                    </div>
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

</template>

<script>
    export default {
        props: {
            testName: {
                type: String,
                required: true
            },
            questions: {
                type: Array,
                required: true
            }
        },

        data: function () {
            return {
                questionId: 0,
            }
        },
    }
</script>
