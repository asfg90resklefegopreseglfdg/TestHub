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
                    <button class="page-link "
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
        <table class="table">
            <thead>
            <tr>
                <th scope="col"><a @click.prevent="sortId()" href="sortIdUri">#</a></th>
                <th scope="col"><a @click.prevent="sortNames()" href="sortNamesUri">Название</a></th>
                <th scope="col">Тэги</th>
                <th scope="col">Стата</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(data, key) in params.data">
                <td>{{data.id}}</td>
                <td>{{data.name}}</td>
                <td><span v-for="tag in data.tags">{{ tag.tag }} </span></td>
                <td>
                    {{(data.statistics_count) ? 'Тест прошло ' + data.statistics_count + ' человек' : 'Тест еще никто не проходил'}}
                </td>
                <td>
                    <a :href="'/test/'+data.slug">
                        <button class="btn btn-primary">Пройти тест</button>
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
                sortNamesUri: 'api/getTestNames?page=1&descName=true',
                sortIdUri: 'api/getTestNames?page=1&descId=true',
                uri: 'api/getTestNames?page=1',
                params: {}

            }
        },

        methods: {
            searchData: function () {
                if (!this.search) {
                    return this.uri = 'api/getTestNames?page=1';
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
                    this.uri = (this.uri === 'api/getTestNames?sort=name&order=desc&page=1') ?
                        'api/getTestNames?sort=name&order=asc&page=1'
                        :
                        'api/getTestNames?sort=name&order=desc&page=1';
                }
            },


            sortId: function () {
                if (this.search) {
                    this.uri = (this.uri === 'api/search?search=' + this.search + '&sort=id&order=desc') ?
                        'api/search?search=' + this.search + '&sort=id&order=asc'
                        :
                        'api/search?search=' + this.search + '&sort=id&order=desc';
                } else {
                    console.log(1);
                    this.uri = (this.uri === 'api/getTestNames?sort=id&order=desc&page=1') ?
                        'api/getTestNames?sort=id&order=asc&page=1'
                        :
                        'api/getTestNames?sort=id&order=desc&page=1';
                }
            }
        },

        watch: {
            uri: function () {
                axios.get(this.uri)
                    .then(response => this.params = response.data)
                    .catch(error => {
                        console.log(error);
                    });
            },
        },

        mounted: function () {
            axios.get(this.uri)
                .then(response => this.params = response.data)

        }

    }
</script>