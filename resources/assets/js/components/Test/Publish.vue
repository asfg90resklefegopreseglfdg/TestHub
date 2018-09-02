<template>
    <div class="form-control">
        <p>Вы можете ввести свой e-mail, чтобы получать на него уведомления
            о прохождение тестов.</p>
        <div class="row mr-auto">
            <div class="col-auto">
                e-mail
            </div>
            <div class="col-sm-auto">
                <input class="form-control"
                       type="email"
                       id="emailAddress"
                       placeholder="Введите e-mail"
                       v-model="user.email"
                       @input="update = false"
                       :class="{ 'is-invalid': !update, 'is-valid': update}">
            </div>
            <div class="col-sm-auto">
                    <button class="btn"
                            @click="saveEmail()"
                            :class="{'btn-danger' : !update, 'btn-success' : update}">Сохранить
                    </button>
            </div>
        </div>
        <br>
        Ссылка для просмотра результатов:
        <a :href="getLinkForStat()"> {{getLinkForStat()}}</a>
        <br>
        Ссылка для прохождения теста:
        <input  class="form-control"
                :value="'/test/'+test.slug">
        <br>
        Поделиться ссылкой в соц. сетях:
    </div>

</template>

<script>
    export default {
        props: {
            user: {
                type: Object,
                required: true,
            },
            test: {
                type: Object,
                required: true,
            },
        },

        data: function () {
            return {
                update: false

            }
        },


        methods: {
            saveEmail: function () {
                axios.put('/user/update-email', {
                    email: this.user.email
                })
                    .then(response => this.update = response.data)
                    .catch(error => {
                        console.log(error);
                    });
            },

            getLinkForStat: function () {
                if (this.test.link_to_stat_if_no_reg) {
                    return '/stats/test/'+this.test.link_to_stat_if_no_reg;
                }
                return '/stats/test/'+this.test.id;
            }
        }
    }
</script>