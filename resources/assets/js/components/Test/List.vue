<template>
    <div class="form-control">
        <label class="label-default" for="search">Поиск тестов</label>
        <input class="form-control"
               id="search"
               autofocus
               placeholder="Поиск"
               @keyup="searchData()"
               v-model="search">
        <br>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item "
                    :class="{'disabled' : params.current_page === 1, 'text-primary' : params.current_page>1 }">
                    <button class="page-link"
                            @click="uri = params.prev_page_url"
                            v-model="uri">Предыдущая страница
                    </button>
                </li>
                <li class="page-item"
                    :class="{'disabled' : params.next_page_url === null}">
                    <button class="page-link"
                            :class="{'text-primary' : params.next_page_url !== null}"
                            @click="uri = params.next_page_url">Следующая страница
                    </button>
                </li>
            </ul>
        </nav>
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th scope="col"><a @click.prevent="sortId()" href="">#</a></th>
                <th scope="col"><a @click.prevent="sortNames()" href="">Название</a></th>
                <th scope="col">Тэги</th>
                <th scope="col">Стата</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <tr    @click="redirectOnTest(test)"
                   v-for="(test, key) in params.data">
                <td>{{test.id}}</td>
                <td>{{test.name}}</td>
                <td><span v-for="tag in test.tags">{{ tag.tag }} </span></td>
                <td>
                    {{(test.statistics_count) ? 'Тест прошло ' + test.statistics_count + ' человек' : 'Тест еще никто не проходил'}}
                </td>
                <td>
                    <a class="btn btn-primary" :href="'/test/' + test.slug">
                        Пройти тест
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

    export default {

        data: function () {
            return {
                search: '',
                sortNamesUri: 'api/get-tests-names?page=1&descName=true',
                sortIdUri: 'api/get-tests-names?page=1&descId=true',
                uri: 'api/get-tests-names?page=1',
                params: {}

            }
        },

        methods: {
            redirectOnTest: function (test) {
                window.location.href = '/test/' + test.slug;
            },

            searchData: function () {
                if (!this.search) {
                    return this.uri = 'api/get-tests-names?page=1';
                }
                this.uri = 'api/search?search=' + this.search;

            },

            sortNames: function () {
                if (this.search) {
                    this.uri = (this.uri === 'api/search?search=' + this.search + '&sort=name&order=desc') ?
                        'api/search?search=' + this.search + '&sort=name&order=asc'
                        :
                        'api/search?search=' + this.search + '&sort=name&order=desc';
                } else {
                    this.uri = (this.uri === 'api/get-tests-names?sort=name&order=desc&page=1') ?
                        'api/get-tests-names?sort=name&order=asc&page=1'
                        :
                        'api/get-tests-names?sort=name&order=desc&page=1';
                }
            },


            sortId: function () {
                if (this.search) {
                    this.uri = (this.uri === 'api/search?search=' + this.search + '&sort=id&order=desc') ?
                        'api/search?search=' + this.search + '&sort=id&order=asc'
                        :
                        'api/search?search=' + this.search + '&sort=id&order=desc';
                } else {
                    this.uri = (this.uri === 'api/get-tests-names?sort=id&order=desc&page=1') ?
                        'api/get-tests-names?sort=id&order=asc&page=1'
                        :
                        'api/get-tests-names?sort=id&order=desc&page=1';
                }
            }
        },

        watch: {
            uri: function () {
                axios.get(this.uri)
                    .then(response => this.params = response.data)
                    .catch(error => {
                    });
            },
        },

        mounted: function () {
            axios.get(this.uri)
                .then(response => this.params = response.data)

        }

    }
</script>