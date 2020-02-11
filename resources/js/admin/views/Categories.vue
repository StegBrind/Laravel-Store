<template>
    <div>
        <admin-content title="Категории" head-title="Список категорий"/>

        <v-treeview
            :items="items"
            multiple-active
            hoverable
            transition
            class="elevation-2"
        >
            <template v-slot:label="props">
                <div v-if="props.item.count_products != 0 && props.item.children.length == 0">
                    {{ props.item.name }}<small> (Кол-во товаров: {{ props.item.count_products }})</small>
                </div>
                <div v-else-if="props.item.children.length != 0">
                   {{ props.item.name }}<small> (Кол-во подкатегорий: {{ props.item.children.length }})</small>
                </div>
            </template>
        </v-treeview>

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
                tableName: 'category',
                fields: [
                    'id', 'name', 'parent_id', 'count_products'
                ],
                fieldsStr: '',
                items: [],

                snackbarText: '',
                snackbarColor: '',
                snackbarShow: false,
            }
        },
        mounted() {
            this.fieldsStr = this.fields.join();
            this.getItems();
        },
        methods: {
            getItems() {
                let url = `get/${this.tableName}/tree?&fields=${this.fieldsStr}`;

                axios.get(url).then(response => {
                    let items = response.data;
                    let items_arr = [];
                    items.forEach(item => {
                        items_arr[item.id] = {
                            name: item.name,
                            count_products: item.count_products,
                            children: [],
                            parent_id: item.parent_id
                        };
                    });
                    items_arr.forEach(item => {
                        if (item.parent_id != null)
                            items_arr[item.parent_id].children.push(item);
                    });

                    this.items = items_arr.filter(item => item.parent_id === null);

                }).catch(error => this.errorResponse(error));
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
