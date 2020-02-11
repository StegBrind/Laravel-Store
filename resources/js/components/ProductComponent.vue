<template>
    <div style="width: 100%">
        <div v-show="showLoadingBar" style="top: 100px; left:47%; position: fixed">
            <img :src="loadingBarUrl">
        </div>

        <div class="input-group" style="width: 300px; float: right; margin-right: 20px; margin-top: 30px">
            <input class="form-control my-0 py-1 red-border" type="text" ref="live_search" :placeholder="translateSearchWord" aria-label="Search">
            <div class="input-group-append">
                <button class="input-group-text red lighten-3" ref="searchButton" @click="clickSearch">{{ translateSearchWordButton }}</button>
            </div>
        </div>

        <div style="padding-top: 100px; text-align: center" ref="products"></div>

        <div class="pagination text-center" style="margin-left: 30px; margin-bottom: 20px" ref="pages"></div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "ProductComponent",
        props: {
            'translateSearchWord': "",
            'translateSearchWordButton': "",
            'loadingBarUrl': "",
            'categoryId': ""
        },
        data: function () {
            return {
                showLoadingBar: true
            }
        },
        mounted() {
            let page_number = this.GET_Parameter("page");
            let search_query = this.GET_Parameter("search_query");
            this.fetch_products(search_query, page_number == null ? 1 : page_number);
            if (search_query != null) this.$refs['live_search'].value = search_query; //for saving search query when transition between pages
        },
        methods: {
            GET_Parameter: function (name) {
                if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search)) return decodeURIComponent(name[1]);
            },
            prepare_for_loading: function () {
                this.showLoadingBar = true;
                this.$refs['products'].innerHTML = "";
                this.$refs['pages'].innerHTML = "";
            },
            fetch_products: function (query = '', page_start = 1) {
                axios.get('/api/category/live/search', {
                    params: {
                        query: query,
                        category_id: this.categoryId,
                        page: page_start
                    }
                   })
                    .then(response => {
                        if (this.renderProducts(response.data['products']))
                            this.renderPages(response.data['pages']);
                        this.showLoadingBar = false;
                    });
            },
            renderProducts: function (products) {
                if (typeof products == 'string') // bad search
                {
                    this.$refs['products'].innerHTML = '<div style="text-align: center; padding-top: 50px"><h2>' + products + '</h2></div>';
                    return false;
                }
                for (let i = 0; i < products.length; i++)
                {
                   this.$refs['products'].innerHTML +=
                  '<div class="product">' +
                   '<div style="text-align: center">' +
                       '<h3>' + products[i].name + '</h3>' +
                   '</div>' +
                   '<hr>' +
                   '<div style="text-align: center">' +
                       '<img src="' + products[i].img + '" width="200px" height="200px">' +
                   '</div>' +
                   '<b>' + 'Описание' + '</b>:' +
                   '<div style="float:left; width: inherit - 8; word-wrap: break-word;">' + products[i].description + '</div><br><b>' + 'Автор' + '</b>:' +
                   '<div style="float:right">' + products[i].author + '</div><br><b>' + 'Цена' + '</b>:' +
                   '<div style="float:right;">' + products[i].price + '<b style="color: green">$</b></div>' +
                   '<br><a href="' + products[i].conversation + '">Начать переписку...</a>' +
                  '</div>';
                }
                return true;
            },
            renderPages: function (pages_json) {
                for (const [page_text, href] of Object.entries(pages_json))
                {
                    if (page_text == '&laquo;')
                        this.$refs['pages'].innerHTML = this.getPageItemHtml(page_text, href) + this.$refs['pages'].innerHTML;
                    else
                        this.$refs['pages'].innerHTML += this.getPageItemHtml(page_text, href);
                }
            },
            getPageItemHtml: function (text, href) {
                return '<li class="page-item">' +
                            '<router-link class="page-link" to="' + href + '">' + text + '</router-link>' +
                        '</li>';
            },
            clickSearch: function () {
                this.prepare_for_loading();
                this.fetch_products(this.$refs['live_search'].value);
                this.$refs['searchButton'].style.pointerEvents = 'none';
                setTimeout(() => {this.$refs['searchButton'].style.pointerEvents = 'auto'}, 3000); //disable clicking for 3 sec
            }
        }
    }
</script>

<style>
    .product
    {
        text-align: justify;
        border: 10px solid #0056b3;
        margin-right: 3%;
        margin-bottom: 3%;
        margin-left: 3%;
        padding: 10px;
        display: inline-block;
        width: 300px;
    }
</style>
