<div>
    <vue-headful title="{{ $category_name }}"/>
    <product-component loading-bar-url="{{ asset('img/loading_bar.gif') }}"
                       category-id="{{ $category_id }}"
                       translate-search-word="{{ __('general.search_by_name') }}"
                       translate-search-word-button="{{ __('general.search') }}"
    ></product-component>
    <p>
        <router-link to="/"><--- {{ __('general.back_to_main_page') }}</router-link>
    </p>
</div>