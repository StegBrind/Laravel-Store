<template>
    <div>
        <admin-content title="Список админов" head-title="Админы"/>

        <v-data-table
            :loading="loading"
            loading-text="Загрузка данных..."
            :headers="headers"
            :items="items"
            :options.sync="options"
            :server-items-length="totalItems"
            @page-count="pageCount = $event"
            :page.sync="currentPage"
            hide-default-footer
            class="elevation-2">
            <!-- Edit Email Column -->
            <template v-slot:item.name="{item}">
                <v-edit-dialog
                    :return-value.sync="item.name"
                    @save="setItem(item, 'name')"
                    large
                    save-text="Сохранить"
                    cancel-text="Отменить"
                >
                    {{ item.name }}
                    <template v-slot:input>
                        <v-text-field
                            v-model="item.name"
                            :rules="[v => v.length <= 50 || 'Слишком длинный текст',
                                     v => v.length != 0 || 'Поле не может быть пустым']"
                            label="Edit"
                            single-line
                            counter
                        />
                    </template>
                </v-edit-dialog>
            </template>
            <!-- Edit Email Column END -->

            <!-- Rows handling -->
            <template v-slot:footer="props">
                <v-divider/>
                <div style="display: flex;justify-content: flex-end">
                    <div class="caption mt-6 pr-3">
                        Количество рядов
                    </div>
                    <div style="width: 75px">
                        <v-select
                            :items="itemsPerPageOptions"
                            v-model="props.props.pagination.itemsPerPage"
                            @change="itemsPerPageSelected"
                            class="pr-3"/>
                    </div>
                    <div class="caption mt-6 pr-3">
                        Ряды с {{props.props.pagination.pageStart + 1}} по {{props.props.pagination.pageStop}}, всего {{props.props.pagination.itemsLength}}
                    </div>
                    <div class="pt-3">
                        <v-pagination
                            v-model="currentPage" :length="pageCount" total-visible="9"/>
                    </div>
                </div>
            </template>
            <!-- Rows handling END -->

        </v-data-table>
        <!-- snackbar -->
        <v-snackbar
            v-model="snackbarShow"
            :top="true"
            :color="snackbarColor"
        >
            {{ snackbarText }}
            <v-btn
                text
                @click="snackbarShow = false"
            >
                <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>
        <!-- snackbar END -->
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                tableName: 'admin',

                loading: false,
                fields: [
                    'id', 'name', 'email'
                ],
                itemsPerPageOptions: [10, 25, 50, 100],
                fieldsStr: '',
                headers: [],
                items: [],
                totalItems: 0,
                currentPage: 1,
                pageCount: 0,
                options: {},

                snackbarText: '',
                snackbarColor: '',
                snackbarShow: false,
            }
        },
        mounted() {
            this.fieldsStr = this.fields.join();
            this.headers = [
                { text: 'ID', divider: true, value: this.fields[0] },
                { text: 'Имя', divider: true, value: this.fields[1] },
                { text: 'Почта', divider: true, value: this.fields[2] }
            ];
        },
        watch: {
            options: {
                handler () {
                    this.getItems();
                }
            },
        },
        methods: {
            getItems() {
                this.loading = true;
                let url = `get/${this.tableName}/table?offset=${this.options.itemsPerPage * (this.options.page - 1)}&limit=${this.options.itemsPerPage}&fields=${this.fieldsStr}`;

                if (this.options.sortBy[0])
                    url += `&sortBy=${this.options.sortBy[0]}&sortDirection=${this.options.sortDesc[0] ? 'desc' : 'asc'}`;

                axios.get(url).then(response => {
                    this.items = response.data.items;
                    this.totalItems = response.data.count;
                    this.loading = false;
                }).catch(error => this.errorResponse(error));
            },
            itemsPerPageSelected(number) {
                this.options.itemsPerPage = number
            },
            setItem(item, field) {
                axios.get(`set/${this.tableName}/table?field=${field}&value=${item[field]}&key=id&id=${item['id']}`)
                     .then(() => {
                         this.showSnackbar('Поле успешно отредактировано', 'success');
                     })
                     .catch(error => this.errorResponse(error));
            },
            errorResponse(error) {
                let error_text;
                if (error.response)
                {
                    error_text = `Ошибка: Сервер ответил с ошибкой. Код: ${error.response.status}.`;
                }
                else if (error.request)
                {
                    error_text = `Ошибка: Сервер не ответил на запрос.`;
                }
                else
                {
                    error_text = `Ошибка: Произошла ошибка в формировании запроса.`;
                }
                this.showSnackbar(error_text, 'error');
            },
            showSnackbar(text, color) {
                this.snackbarShow = false;
                this.snackbarText = text;
                this.snackbarColor = color;
                this.snackbarShow = true;
            }
        }
    }
</script>
