<template>
    <div class="form-control">
        <h5> Описание </h5>
        <p>{{test.description}}</p>
        <h5> Опции </h5>
        <p>
            {{test.duration ? 'Времени на тест: ' + test.duration + ' минут' : 'Время на тест: неограничено'}}
            <br>
            Вопросов: {{test.questions_count}}
            <br>
            Просмотр неправильных ответов после теста: {{test.answers_public ? 'разрешен' : 'запрещен'}}
            <a v-if="test.answers_public" :href="'/test/answers/' + test.slug"> <br>Посмотреть ответы</a>
            <br>
            Все результаты теста: {{test.passing_public ? 'публичные' : 'закрытые'}}
            <a v-if="test.answers_public" :href="'/stats/test/' + test.id"> <br>Посмотреть статистику</a>
            <br>
            Дата создания: {{test.created_at}}
        </p>

        <h5> Статистика </h5>
        <p>
            {{test.statistics_count ? 'ТЕСТ сдало ' + test.statistics_count + ' человек' : 'Тест еще никто не сдавал, будь первым!'}}
        </p>


        <button class="btn btn-primary col"
                @click="createStatisticalData">
            Начать тест
        </button>

    </div>
</template>

<script>
    export default {
        props: {
            test: {
                type: Object,
                required: true,
            }
        },

        data: function () {
            return {
                urlToQuestions: '/test/'+ this.test.slug +'/questions'
            }
        },

        methods: {
            createStatisticalData: function () {
                axios.post('/test/' + this.test.slug, {
                    withCredentials: true,
                })
                    .then(function (response) {
                        if (response.status === 200) {
                            let identifier;
                            console.log(response.data)
                            if (response.data['identifier']) {
                                identifier = response.data['identifier'];
                            } else {
                                throw error;
                            }
                            this.clearTestLocalStorage();
                            this.setIdentifierInLocalStorage(identifier);
                            window.location.href = this.urlToQuestions;
                        }
                    }.bind(this))
                    .catch(error => {
                        /*ПРоизошла ошибка повторите позже;*/
                    });
            },

            setIdentifierInLocalStorage: function (identifier) {
                localStorage.setItem('identifier' + this.test.id, identifier);
            },

            clearTestLocalStorage: function () {
                localStorage.removeItem('durationTo' + this.test.id);
                localStorage.removeItem('duration' + this.test.id);
                localStorage.removeItem('answers' + this.test.id);
            }
        }
    }
</script>




























